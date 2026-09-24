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
    if (!lr_rate_ok('admin_login', 10)) {
        $error = 'Demasiados intentos de acceso. Espere unos minutos e intente de nuevo.';
    } elseif (!lr_csrf_check($_POST['csrf'] ?? null)) {
        $error = 'Sesión expirada. Intente de nuevo.';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        if ($username === '' || $password === '') {
            $error = 'Ingrese usuario y contraseña.';
        } elseif (!preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $username)) {
            $error = 'Credenciales incorrectas.';
            lr_admin_access_log('ADMIN_LOGIN_FALLIDO', null, mb_substr($username, 0, 50));
        } elseif (lr_admin_login($username, $password)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Credenciales incorrectas.';
            lr_admin_access_log('ADMIN_LOGIN_FALLIDO', null, mb_substr($username, 0, 50));
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
<link rel="icon" href="../public/assets/img/logo.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="auth-body">
<aside class="auth-brand">
  <img class="brand-logo" src="../public/assets/img/logo-ProRed.png" alt="ProRed">
  <h1>Libro de Reclamaciones</h1>
  <p class="brand-lead">Sistema de registro y atención de reclamos y quejas de ProRed — panel administrativo seguro.</p>
  <ul class="brand-points">
    <li><i class="fas fa-check-circle"></i> Registro de Hojas de Reclamación con código de seguimiento</li>
    <li><i class="fas fa-check-circle"></i> Control de plazos de respuesta y estados de atención</li>
    <li><i class="fas fa-check-circle"></i> Generación de constancia PDF oficial</li>
  </ul>
  <p class="brand-legal">
    Conforme al Decreto Supremo N.º 011-2011-PCM y la Ley N.º 29571 — Código de Protección y Defensa del Consumidor.
    Atención de reclamos ante <strong>INVERSIONES STARNET PERU SAC (ProRed)</strong>.
  </p>
  <p class="brand-foot">&copy; <?= date('Y') ?> ProRed — Uso exclusivo del personal autorizado</p>
  <img class="brand-watermark" src="../public/assets/img/logo.png" alt="">
</aside>
<div class="auth-side">
  <div class="auth-card">
    <img class="auth-logo" src="../public/assets/img/logo-ProRed-color.png" alt="ProRed">
    <h1>Panel administrativo</h1>
    <p class="sub">Ingrese sus credenciales para continuar</p>
    <?php if ($needSetup): ?>
      <div class="setup">No hay usuarios administrativos. Abra <a href="setup.php">setup.php</a> para crear el primero.</div>
    <?php endif; ?>
    <?php if ($error): ?><div class="error"><?= lr_h($error) ?></div><?php endif; ?>
    <form method="post" action="login.php" autocomplete="off">
      <input type="hidden" name="csrf" value="<?= lr_csrf_token() ?>">
      <label for="username">Usuario</label>
      <input id="username" name="username" required maxlength="50" pattern="[a-zA-Z0-9_.\-]{3,50}" title="3–50 caracteres: letras, números, _ . -" autofocus>
      <label for="password">Contraseña</label>
      <input id="password" name="password" type="password" required>
      <button type="submit">Ingresar</button>
    </form>
    <p class="auth-foot">Sistema confidencial — toda acción queda registrada.</p>
  </div>
</div>
</body>
</html>
