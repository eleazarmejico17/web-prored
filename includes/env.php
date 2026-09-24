<?php
declare(strict_types=1);

/**
 * Carga de variables de entorno SIN dependencias.
 * Prioridad: variable real del sistema (getenv) → archivo .env en la raíz.
 * Uso: env('DB_PASS', '')
 */
function env(string $key, ?string $default = null): ?string
{
    static $parsed = null;

    $v = getenv($key);
    if ($v !== false) {
        return $v;
    }
    if (isset($_ENV[$key])) {
        return (string)$_ENV[$key];
    }
    if ($parsed === null) {
        $parsed = [];
        $file = dirname(__DIR__) . '/.env';
        if (is_file($file)) {
            foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#')) {
                    continue;
                }
                if (!preg_match('/^([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*)$/', $line, $m)) {
                    continue;
                }
                $val = trim($m[2]);
                $len = strlen($val);
                if ($len >= 2 && ($val[0] === '"' || $val[0] === "'") && $val[$len - 1] === $val[0]) {
                    // Valor entre comillas: se interpretan \n y \t del doble
                    $val = ($val[0] === '"') ? stripcslashes(substr($val, 1, -1)) : substr($val, 1, -1);
                } else {
                    $hash = strpos($val, ' #');
                    if ($hash !== false) {
                        $val = rtrim(substr($val, 0, $hash));
                    }
                }
                $parsed[$m[1]] = $val;
            }
        }
    }
    return $parsed[$key] ?? $default;
}
