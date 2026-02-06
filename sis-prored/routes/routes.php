<?php
$routes = [
    // Rutas públicas
    'GET /' => 'AuthController@showLogin',
    'POST /login' => 'AuthController@login',
    'GET /logout' => 'AuthController@logout',

    // Rutas de cliente
    'GET /cliente/dashboard' => 'ClienteController@dashboard',
    'GET /cliente/servicios' => 'ClienteController@misServicios',
    'GET /cliente/estado-cuenta' => 'ClienteController@estadoCuenta',
    'GET /cliente/reportar-pago' => 'ClienteController@showReportarPago',
    'POST /cliente/reportar-pago' => 'ClienteController@reportarPago',
    'GET /cliente/historial-pagos' => 'ClienteController@historialPagos',
    'GET /cliente/tickets' => 'ClienteController@misTickets',
    'POST /cliente/tickets' => 'ClienteController@crearTicket',

    // Rutas de ventas
    'GET /ventas/dashboard' => 'VentasController@dashboard',
    'GET /ventas/pagos-pendientes' => 'VentasController@pagosPendientes',
    'POST /ventas/validar-pago' => 'VentasController@validarPago',
    'GET /ventas/alertas-morosidad' => 'VentasController@alertasMorosidad',
    'GET /ventas/comprobantes' => 'VentasController@comprobantes',

    // Rutas de soporte
    'GET /soporte/dashboard' => 'SoporteController@dashboard',
    'GET /soporte/tickets' => 'SoporteController@tickets',
    'POST /soporte/asignar-ticket' => 'SoporteController@asignarTicket',

    // Rutas de administrador
    'GET /admin/dashboard' => 'AdminController@dashboard',
    'GET /admin/empleados' => 'AdminController@gestionarEmpleados',
    'GET /admin/reportes' => 'AdminController@reportes',
];

function route($requestMethod, $requestUri)
{
    global $routes;

    $key = $requestMethod . ' ' . $requestUri;

    if (isset($routes[$key])) {
        list($controller, $method) = explode('@', $routes[$key]);

        require_once "controllers/$controller.php";
        $controllerInstance = new $controller();
        $controllerInstance->$method();
    } else {
        http_response_code(404);
        echo "Página no encontrada";
    }
}
?>