<?php
session_start();

// Incluir controladores
require_once 'controllers/AuthController.php';
require_once 'controllers/ClienteController.php';
require_once 'controllers/VentasController.php';
require_once 'controllers/SoporteController.php';
require_once 'controllers/TecnicoController.php';
require_once 'controllers/AdminController.php';

// Obtener acción desde URL
$action = $_GET['action'] ?? 'dashboard';
$controller = $_GET['controller'] ?? 'auth';

// Enrutamiento básico
switch ($controller) {
    case 'auth':
        $authController = new AuthController();
        switch ($action) {
            case 'login':
                $authController->login();
                break;
            case 'logout':
                $authController->logout();
                break;
            case 'cambiar-password':
                $authController->cambiarPassword();
                break;
        }
        break;

    case 'cliente':
        $authController = new AuthController();
        $authController->checkAuth(['CLIENTE']);

        $clienteController = new ClienteController();
        switch ($action) {
            case 'dashboard':
                include 'views/cliente/dashboard.php';
                break;
            case 'servicios':
                $servicios = $clienteController->obtenerServiciosCliente($_SESSION['cliente_id']);
                include 'views/cliente/servicios.php';
                break;
            case 'estado-cuenta':
                $estadoCuenta = $clienteController->obtenerEstadoCuenta($_SESSION['cliente_id']);
                include 'views/cliente/estado_cuenta.php';
                break;
            case 'reportar-pago':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $resultado = $clienteController->reportarPago($_POST, $_FILES['comprobante']);
                    $_SESSION['mensaje'] = $resultado['mensaje'];
                    header('Location: ?controller=cliente&action=reportar-pago');
                }
                include 'views/cliente/reportar_pago.php';
                break;
        }
        break;

    case 'ventas':
        $authController = new AuthController();
        $authController->checkAuth(['VENTAS', 'ADMIN']);

        $ventasController = new VentasController();
        switch ($action) {
            case 'dashboard':
                include 'views/ventas/dashboard.php';
                break;
            case 'validar-pagos':
                $pagosPendientes = $ventasController->obtenerPagosPendientes();
                include 'views/ventas/validar_pagos.php';
                break;
            case 'alertas-morosidad':
                $alertas = $ventasController->obtenerAlertasMorosidad();
                include 'views/ventas/alertas_morosidad.php';
                break;
        }
        break;

    // ... rutas para otros controladores
}

// Si no se encontró la ruta, mostrar error 404
http_response_code(404);
include 'views/errors/404.php';
?>