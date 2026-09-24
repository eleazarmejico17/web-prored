<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

/**
 * Autenticación del panel administrativo.
 */
function lr_admin_user(): ?array
{
    $name = lr_config()['admin']['session_name'] ?? 'prored_libro_admin';
    if (empty($_SESSION[$name])) {
        return null;
    }
    $u = $_SESSION[$name];
    if (!is_array($u) || empty($u['id'])) {
        return null;
    }
    // Expiración por inactividad
    $ttl = ((int)(lr_config()['admin']['duracion_minutos'] ?? 60)) * 60;
    if (isset($u['ultimo_ok']) && (time() - (int)$u['ultimo_ok']) > $ttl) {
        lr_admin_logout();
        return null;
    }
    $u['ultimo_ok'] = time();
    $_SESSION[$name] = $u;
    return $u;
}

function lr_admin_require(): array
{
    $u = lr_admin_user();
    if (!$u) {
        header('Location: login.php');
        exit;
    }
    return $u;
}

function lr_admin_login(string $username, string $password): bool
{
    $pdo = lr_db();
    $stmt = $pdo->prepare(
        'SELECT id, username, password_hash, nombre, activo
         FROM admin_users WHERE username = ? LIMIT 1'
    );
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    if (!$row || !(int)$row['activo']) {
        // Verificación simulada: mismo coste que un usuario real (evita
        // enumerar usuarios midiendo el tiempo de respuesta)
        static $dummy = null;
        if ($dummy === null) {
            $dummy = password_hash('timing-equalization-not-a-real-password', PASSWORD_DEFAULT);
        }
        password_verify($password, $dummy);
        return false;
    }
    if (!password_verify($password, $row['password_hash'])) {
        return false;
    }
    // Rehash si el algoritmo cambió
    if (password_needs_rehash($row['password_hash'], PASSWORD_DEFAULT)) {
        $pdo->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $row['id']]);
    }
    $pdo->prepare('UPDATE admin_users SET ultimo_acceso = NOW() WHERE id = ?')
        ->execute([$row['id']]);

    $name = lr_config()['admin']['session_name'] ?? 'prored_libro_admin';
    session_regenerate_id(true);
    $_SESSION[$name] = [
        'id' => (int)$row['id'],
        'username' => $row['username'],
        'nombre' => $row['nombre'],
        'ultimo_ok' => time(),
    ];
    lr_admin_access_log('ADMIN_LOGIN', (int)$row['id'], $row['username']);
    return true;
}

function lr_admin_logout(): void
{
    $name = lr_config()['admin']['session_name'] ?? 'prored_libro_admin';
    $u = $_SESSION[$name] ?? null;
    if (is_array($u) && !empty($u['id'])) {
        lr_admin_access_log('ADMIN_LOGOUT', (int)$u['id'], (string)($u['username'] ?? ''));
    }
    unset($_SESSION[$name]);
}

function lr_admin_isset(): bool
{
    $name = lr_config()['admin']['session_name'] ?? 'prored_libro_admin';
    return !empty($_SESSION[$name]);
}
