<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/auth.php';

// Solo permite crear el PRIMER usuario; después se auto-deshabilita.
try {
    $n = (int)lr_db()->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
} catch (Throwable $e) {
    http_response_code(500);
    exit('Base de datos no disponible. Ejecute sql/schema.sql y revise config.php.');
}

if ($n > 0) {
    http_response_code(403);
    exit('Setup deshabilitado: ya existe al menos un administrador.');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!lr_csrf_check($_POST['csrf'] ?? null)) {
        $error = 'Sesión expirada.';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $password2 = (string)($_POST['password2'] ?? '');
        $nombre = trim((string)($_POST['nombre'] ?? ''));

        if (!preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $username)) {
            $error = 'Usuario inválido (3–50 caracteres: letras, números, _ . -).';
        } elseif (strlen($password) < 10) {
            $error = 'La contraseña debe tener al menos 10 caracteres.';
        } elseif ($password !== $password2) {
            $error = 'Las contraseñas no coinciden.';
        } else {
            try {
                lr_db()->prepare(
                    'INSERT INTO admin_users (username, password_hash, nombre) VALUES (?, ?, ?)'
                )->execute([
                    $username,
                    password_hash($password, PASSWORD_DEFAULT),
                    $nombre !== '' ? $nombre : null,
                ]);
                header('Location: login.php');
                exit;
            } catch (Throwable $e) {
                $error = 'No se pudo crear el usuario.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title>Setup — Libro de Reclamaciones</title>
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="auth-body">
<div class="auth-card">
  <h1>Primer administrador</h1>
  <p>Cree el usuario admin y luego elimine o proteja este archivo <code>setup.php</code>.</p>
  <?php if ($error): ?><div class="error"><?= lr_h($error) ?></div><?php endif; ?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= lr_csrf_token() ?>">
    <label>Usuario</label>
    <input name="username" required maxlength="50" autofocus>
    <label>Nombre visible</label>
    <input name="nombre" maxlength="100">
    <label>Contraseña (mín. 10)</label>
    <input name="password" type="password" required minlength="10">
    <label>Repetir contraseña</label>
    <input name="password2" type="password" required minlength="10">
    <button type="submit" class="btn-secondary">Crear usuario</button>
  </form>
</div>
</body>
</html>
