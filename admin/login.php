<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/auth.php';

if (lr_admin_isset()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!lr_csrf_check($_POST['csrf'] ?? null)) {
        $error = 'Sesión expirada. Intente de nuevo.';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        if ($username === '' || $password === '') {
            $error = 'Ingrese usuario y contraseña.';
        } elseif (lr_admin_login($username, $password)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Credenciales incorrectas.';
        }
    }
}

$needSetup = false;
try {
    $needSetup = (int)lr_db()->query('SELECT COUNT(*) FROM admin_users')->fetchColumn() === 0;
} catch (Throwable $e) {
    $error = 'No hay conexión con la base de datos. Revise config.php y ejecute sql/schema.sql.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title>Iniciar sesión — Libro de Reclamaciones</title>
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="auth-body">
<div class="auth-card">
  <h1>Libro de Reclamaciones</h1>
  <p class="sub">Panel administrativo — ProRed</p>
  <?php if ($needSetup): ?>
    <div class="setup">No hay usuarios administrativos. Abra <a href="setup.php">setup.php</a> para crear el primero.</div>
  <?php endif; ?>
  <?php if ($error): ?><div class="error"><?= lr_h($error) ?></div><?php endif; ?>
  <form method="post" action="login.php" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= lr_csrf_token() ?>">
    <label for="username">Usuario</label>
    <input id="username" name="username" required maxlength="50" autofocus>
    <label for="password">Contraseña</label>
    <input id="password" name="password" type="password" required>
    <button type="submit">Ingresar</button>
  </form>
</div>
</body>
</html>
