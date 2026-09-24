<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
header('Content-Type: text/plain; charset=utf-8');
$c = lr_config()['db'];
echo "host={$c['host']}\n";
echo "name={$c['name']}\n";
echo "user={$c['user']}\n";
echo "pass=" . ((string)$c['pass'] !== '' ? '(' . strlen((string)$c['pass']) . ' chars)' : '(VACIO)') . "\n";
try {
    $pdo = lr_db();
    echo "conexion: OK\n";
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    echo 'tablas (' . count($tables) . '): ' . implode(', ', $tables) . "\n";
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
