<?php
declare(strict_types=1);

/**
 * Bootstrap compartido: config, DB PDO, helpers.
 * Rutas: este archivo vive en /includes/ → raíz del sitio es dirname(__DIR__).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}

$ROOT = dirname(__DIR__);
$CONFIG_FILE = $ROOT . '/config.php';

if (!is_file($CONFIG_FILE)) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'message' => 'Configuración no encontrada. Copie config.php.example a config.php.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/** @return array */
function lr_config(): array
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require dirname(__DIR__) . '/config.php';
    }
    return $cfg;
}

function lr_db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $c = lr_config()['db'];
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $c['host'],
        $c['name'],
        $c['charset'] ?? 'utf8mb4'
    );
    $pdo = new PDO($dsn, $c['user'], $c['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function lr_json_out(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function lr_h(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function lr_client_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// ---------------------------------------------------------------------------
// CSRF
// ---------------------------------------------------------------------------
function lr_csrf_token(): string
{
    if (empty($_SESSION['lr_csrf'])) {
        $_SESSION['lr_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['lr_csrf'];
}

function lr_csrf_check(?string $token): bool
{
    return is_string($token)
        && !empty($_SESSION['lr_csrf'])
        && hash_equals($_SESSION['lr_csrf'], $token);
}

// ---------------------------------------------------------------------------
// Rate limit (por IP + acción)
// ---------------------------------------------------------------------------
function lr_rate_ok(string $accion, int $maxPorHora): bool
{
    $ip = lr_client_ip();
    $pdo = lr_db();
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM rate_limit
         WHERE ip = ? AND accion = ? AND created_at > (NOW() - INTERVAL 1 HOUR)'
    );
    $stmt->execute([$ip, $accion]);
    $n = (int)$stmt->fetchColumn();
    if ($n >= $maxPorHora) {
        return false;
    }
    $pdo->prepare('INSERT INTO rate_limit (ip, accion) VALUES (?, ?)')
        ->execute([$ip, $accion]);
    return true;
}

// ---------------------------------------------------------------------------
// Días hábiles (lun–vie, excluye feriados configurados)
// ---------------------------------------------------------------------------
function lr_es_feriado(string $ymd): bool
{
    return in_array($ymd, lr_config()['feriados'] ?? [], true);
}

function lr_fecha_limite_habiles(int $dias): string
{
    $d = new DateTimeImmutable('now', new DateTimeZone('America/Lima'));
    $count = 0;
    $max = 400;
    $i = 0;
    while ($count < $dias && $i < $max) {
        $d = $d->modify('+1 day');
        $i++;
        $w = (int)$d->format('w'); // 0 dom … 6 sáb
        if ($w === 0 || $w === 6) {
            continue;
        }
        if (lr_es_feriado($d->format('Y-m-d'))) {
            continue;
        }
        $count++;
    }
    return $d->format('Y-m-d');
}

// ---------------------------------------------------------------------------
// Auditoría
// ---------------------------------------------------------------------------
function lr_historial(int $reclamacionId, string $accion, ?string $desc = null, ?int $usuarioId = null): void
{
    if ($reclamacionId <= 0) {
        return; // acciones sin reclamación no van a la tabla con FK a reclamaciones
    }
    lr_db()->prepare(
        'INSERT INTO reclamacion_historial (reclamacion_id, usuario_id, accion, descripcion)
         VALUES (?, ?, ?, ?)'
    )->execute([$reclamacionId, $usuarioId, $accion, $desc]);
}

/**
 * Log de acceso al panel (login/logout). No depende de reclamaciones.
 */
function lr_admin_access_log(string $accion, ?int $usuarioId = null, ?string $username = null): void
{
    try {
        lr_db()->prepare(
            'INSERT INTO admin_access_log (usuario_id, username, accion, ip)
             VALUES (?, ?, ?, ?)'
        )->execute([$usuarioId, $username, $accion, lr_client_ip()]);
    } catch (Throwable $e) {
        error_log('admin_access_log: ' . $e->getMessage());
    }
}
