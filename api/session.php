<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

// Emite/renueva sesión + token CSRF para el formulario público.
// Evita que el HTML estático del sitio necesite PHP para generar el token.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

lr_json_out([
    'success' => true,
    'csrf' => lr_csrf_token(),
]);
