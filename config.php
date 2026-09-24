<?php
/**
 * Configuración del Libro de Reclamaciones.
 *
 * Este archivo NO contiene secretos: credenciales de BD, URL base y correo
 * se leen del archivo ".env" en la raíz (ver .env.example) o del entorno
 * real del servidor (variables de entorno / SetEnv).
 */
require_once __DIR__ . '/includes/env.php';

return [
    'db' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'name' => env('DB_NAME', 'prored_libro'),
        'user' => env('DB_USER', 'root'),
        'pass' => env('DB_PASS', ''),
        'charset' => 'utf8mb4',
    ],

    'base_url' => rtrim((string)env('BASE_URL', 'http://localhost/web-prored'), '/'),

    'proveedor' => [
        'razon_social' => 'INVERSIONES STARNET PERU SAC',
        'nombre_comercial' => 'ProRed',
        'ruc' => '20608786598',
        'domicilio' => 'AV. RAMON CASTILLA NRO 631 CONCEPCION',
        'email' => 'admin@proredperu.com',
    ],

    'dias_habiles_respuesta' => 15,

    'feriados' => [
        '2026-01-01', '2026-04-03', '2026-04-09', '2026-05-01',
        '2026-06-29', '2026-07-28', '2026-07-29', '2026-08-30',
        '2026-10-08', '2026-11-01', '2026-12-08', '2026-12-25',
        '2027-01-01', '2027-03-26', '2027-04-01', '2027-05-01',
        '2027-06-29', '2027-07-28', '2027-07-29', '2027-08-30',
        '2027-10-08', '2027-11-01', '2027-12-08', '2027-12-25',
    ],

    'rate_limit' => [
        'max_por_hora' => 5,
    ],

    'mail' => [
        'from' => env('MAIL_FROM', ''),
        'from_name' => env('MAIL_FROM_NAME', 'ProRed - Libro de Reclamaciones'),
        'enabled' => filter_var(env('MAIL_ENABLED', 'false'), FILTER_VALIDATE_BOOLEAN),
    ],

    'admin' => [
        'session_name' => 'prored_libro_admin',
        'duracion_minutos' => 60,
    ],
];
