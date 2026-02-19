<?php
class VentasController
{
    public $ventasModel;
    public $pagoModel;
    public $clienteModel;
    public $cargoAdicionalModel;

    public function __construct()
    {
        // Require models if not already loaded (defensive)
        if (!class_exists('VentasModel'))
            require_once __DIR__ . '/../models/VentasModel.php';
        if (!class_exists('PagoModel'))
            require_once __DIR__ . '/../models/PagoModel.php';
        if (!class_exists('ClienteModel'))
            require_once __DIR__ . '/../models/ClienteModel.php';
        if (!class_exists('CargoAdicionalModel'))
            require_once __DIR__ . '/../models/CargoAdicionalModel.php';

        $this->ventasModel = new VentasModel();
        $this->pagoModel = new PagoModel();
        $this->clienteModel = new ClienteModel();
        $this->cargoAdicionalModel = new CargoAdicionalModel();
    }

    // RF-V01: Validar Pagos Reportados por Clientes
    public function obtenerPagosPendientes($filtros = [])
    {
        return $this->pagoModel->getPagosPendientes($filtros);
    }

    public function validarPago($pagoId, $accion, $usuarioId, $motivoRechazo = null)
    {
        if ($accion === 'aprobar') {
            $resultado = $this->pagoModel->aprobarPago($pagoId, $usuarioId);

            if ($resultado) {
                // Reactivar servicio si estaba suspendido
                $this->reactivarServicioSiSuspenso($pagoId);

                // Generar comprobante PDF
                $this->generarComprobantePago($pagoId);

                return ['success' => true, 'mensaje' => 'Pago aprobado exitosamente'];
            }
        } elseif ($accion === 'rechazar') {
            $resultado = $this->pagoModel->rechazarPago($pagoId, $usuarioId, $motivoRechazo);

            if ($resultado) {
                // Notificar al cliente
                $this->notificarClienteRechazoPago($pagoId, $motivoRechazo);

                return ['success' => true, 'mensaje' => 'Pago rechazado'];
            }
        }

        return ['success' => false, 'error' => 'Error al procesar la validación'];
    }

    // RF-V02: Registrar Pagos Manuales
    public function registrarPagoManual($datosPago, $usuarioId)
    {
        $datosPago['id_usuario'] = $usuarioId;
        $resultado = $this->ventasModel->registrarPagoManual($datosPago);

        if ($resultado && isset($resultado['success']) && $resultado['success']) {
            // Generar comprobante
            $this->generarComprobantePago($resultado['id_pago']);

            // Reactivar servicio si estaba suspendido
            if (isset($datosPago['id_servicio'])) {
                $this->reactivarServicio($datosPago['id_servicio']);
            }

            return ['success' => true, 'pago_id' => $resultado['id_pago']];
        }

        return ['success' => false, 'error' => $resultado['error'] ?? 'Error al registrar el pago'];
    }

    // RF-V03: Gestionar Comprobantes PDF
    public function obtenerComprobantesPendientesEnvio()
    {
        return $this->pagoModel->getComprobantesPendientesEnvio();
    }

    public function enviarComprobanteWhatsApp($comprobanteId, $numeroTelefono, $usuarioId)
    {
        $resultado = $this->pagoModel->registrarEnvioWhatsApp($comprobanteId, $usuarioId, $numeroTelefono);

        if ($resultado) {
            // Generar enlace de WhatsApp
            $enlaceWhatsApp = $this->generarEnlaceWhatsApp($numeroTelefono, $comprobanteId);

            return [
                'success' => true,
                'enlace_whatsapp' => $enlaceWhatsApp,
                'mensaje' => 'Comprobante listo para enviar por WhatsApp'
            ];
        }

        return ['success' => false, 'error' => 'Error al preparar el envío'];
    }

    // RF-V04: Registrar Cargos Adicionales
    public function obtenerMaterialesPendientes()
    {
        return $this->ventasModel->getMaterialesPendientes();
    }

    public function registrarCargoAdicional($datosCargo, $usuarioId)
    {
        $datosCargo['id_usuario'] = $usuarioId;

        $resultado = $this->cargoAdicionalModel->crearCargoManual($datosCargo);

        if ($resultado) {
            // Notificar al cliente
            $this->notificarClienteCargoAdicional($datosCargo['id_servicio'], $datosCargo);

            return ['success' => true, 'cargo_id' => $resultado];
        }

        return ['success' => false, 'error' => 'Error al registrar el cargo'];
    }

    public function procesarMaterialesTecnicos($visitaId, $periodoId, $usuarioId)
    {
        $materiales = $this->ventasModel->obtenerMaterialesVisita($visitaId);

        if (empty($materiales)) {
            return ['success' => false, 'error' => 'No hay materiales para procesar'];
        }

        // Calcular total
        $total = 0;
        $descripcion = '';
        foreach ($materiales as $material) {
            $total += $material['total'];
            $descripcion .= $material['nombre'] . ' (' . $material['cantidad'] . '), ';
        }
        $descripcion = rtrim($descripcion, ', ');

        // Crear cargo adicional
        $datosCargo = [
            'id_servicio' => $materiales[0]['id_servicio'],
            'id_periodo' => $periodoId,
            'concepto' => 'MATERIALES_TECNICOS',
            'descripcion' => $descripcion,
            'monto' => $total,
            'origen' => 'VISITA_TECNICA',
            'id_visita' => $visitaId,
            'id_usuario' => $usuarioId
        ];

        $resultado = $this->cargoAdicionalModel->crearCargoVisita($datosCargo);

        if ($resultado) {
            // Marcar materiales como procesados
            $this->ventasModel->marcarMaterialesProcesados($visitaId);

            // Notificar al cliente
            $this->notificarClienteMateriales($datosCargo['id_servicio'], $total, $descripcion);

            return ['success' => true, 'cargo_id' => $resultado];
        }

        return ['success' => false, 'error' => 'Error al procesar los materiales'];
    }

    // RF-V05: Gestionar Cambios de Titularidad
    public function cambiarTitularidadServicio($servicioId, $nuevoClienteId, $datosCambio, $usuarioId)
    {
        // Verificar que el servicio no tenga deudas
        $tieneDeudas = $this->servicioModel->verificarDeuda($servicioId);

        if ($tieneDeudas) {
            return ['success' => false, 'error' => 'El servicio tiene deudas pendientes'];
        }

        $resultado = $this->ventasModel->cambiarTitularidad($servicioId, $nuevoClienteId, $datosCambio, $usuarioId);

        if ($resultado) {
            // Crear cargo por trámite si aplica
            if (isset($datosCambio['costo_tramite']) && $datosCambio['costo_tramite'] > 0) {
                $this->crearCargoCambioTitularidad($servicioId, $datosCambio['costo_tramite']);
            }

            // Notificar a ambos clientes
            $this->notificarCambioTitularidad($servicioId, $nuevoClienteId);

            return ['success' => true];
        }

        return ['success' => false, 'error' => 'Error al cambiar la titularidad'];
    }

    // RF-V06: Gestionar Alertas de Morosidad
    public function obtenerAlertasMorosidad($filtros = [])
    {
        return $this->clienteModel->getClientesEnMora($filtros);
    }

    public function registrarContactoCliente($datosContacto, $usuarioId)
    {
        $datosContacto['id_usuario'] = $usuarioId;
        return $this->ventasModel->registrarContactoCliente($datosContacto);
    }

    public function obtenerHistorialContactos($clienteId)
    {
        return $this->ventasModel->getHistorialContactos($clienteId);
    }

    // RF-V07: Conciliación Bancaria
    public function obtenerConciliacionBancaria($fechaInicio, $fechaFin)
    {
        return $this->ventasModel->getConciliacionBancaria($fechaInicio, $fechaFin);
    }

    // RF-V08: Dashboard Data
    // RF-V08: Dashboard Data
    public function getDashboardData()
    {
        header('Content-Type: application/json');
        try {
            $stats = $this->ventasModel->getDashboardStats();
            $chart = $this->ventasModel->getNewSalesChart();

            echo json_encode([
                'stats' => $stats,
                'chart' => $chart
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // RF-V09: Busqueda de Servicios
    // RF-V09: Busqueda de Servicios
    public function searchServices()
    {
        header('Content-Type: application/json');
        try {
            $search = $_GET['search'] ?? '';
            $estado = $_GET['estado'] ?? '';

            $filtros = [
                'search' => $search,
                'estado' => $estado
            ];

            $results = $this->ventasModel->getServiceDirectory($filtros);
            echo json_encode($results);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // RF-V10: Busqueda de Clientes
    public function searchClients()
    {
        header('Content-Type: application/json');
        try {
            $search = $_GET['search'] ?? '';

            $filtros = [
                'search' => $search
            ];

            $results = $this->ventasModel->getClientDirectory($filtros);
            echo json_encode($results);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Métodos auxiliares
    private function reactivarServicioSiSuspenso($pagoId)
    {
        // Obtener información del pago y servicio
        $pago = $this->pagoModel->getPagoById($pagoId);

        if ($pago && isset($pago['id_servicio'])) {
            $this->reactivarServicio($pago['id_servicio']);
        }
    }

    private function reactivarServicio($servicioId)
    {
        if (!class_exists('ServicioModel')) {
            require_once __DIR__ . '/../models/ServicioModel.php';
        }
        $servicioModel = new ServicioModel();
        $servicioModel->cambiarEstado($servicioId, 'ACTIVO');
    }

    private function generarComprobantePago($pagoId)
    {
        // Lógica para generar PDF del comprobante
        // require_once 'libraries/PdfGenerator.php';
        // $pdfGenerator = new PdfGenerator();

        // $pago = $this->pagoModel->getPagoById($pagoId);
        // $cliente = $this->clienteModel->getClienteByServicio($pago['id_servicio']);

        /*
        $datosComprobante = [
            'numero' => 'COMP-' . date('Y') . '-' . str_pad($pagoId, 6, '0', STR_PAD_LEFT),
            'fecha' => date('d/m/Y'),
            'cliente' => $cliente['nombre_completo'],
            'dni' => $cliente['dni'],
            'direccion' => $pago['direccion_servicio'],
            'monto' => $pago['monto'],
            'periodo' => $pago['periodo'],
            'metodo_pago' => $pago['metodo_pago'],
            'numero_operacion' => $pago['numero_operacion']
        ];

        $rutaPdf = $pdfGenerator->generarComprobantePago($datosComprobante);

        // Guardar en base de datos
        $this->pagoModel->guardarComprobante($pagoId, $rutaPdf);

        return $rutaPdf;
        */
        return null; // PDF gen disabled
    }

    private function generarEnlaceWhatsApp($numeroTelefono, $comprobanteId)
    {
        $comprobante = $this->pagoModel->getComprobanteById($comprobanteId);

        $mensaje = rawurlencode("Hola, adjuntamos el comprobante de pago de su servicio de internet.\n"
            . "Periodo: " . $comprobante['periodo'] . "\n"
            . "Monto: S/ " . number_format($comprobante['monto'], 2) . "\n"
            . "Próximo vencimiento: " . $comprobante['proximo_vencimiento'] . "\n"
            . "Gracias por su pago puntual.");

        return "https://wa.me/51" . $numeroTelefono . "?text=" . $mensaje;
    }

    private function notificarClienteRechazoPago($pagoId, $motivo)
    {
        // Lógica para enviar notificación al cliente
        /*
        require_once 'libraries/Notificador.php';
        $notificador = new Notificador();

        $pago = $this->pagoModel->getPagoById($pagoId);
        $cliente = $this->clienteModel->getClienteByServicio($pago['id_servicio']);

        $mensaje = "Su pago con referencia {$pago['referencia']} ha sido rechazado. Motivo: $motivo";

        $notificador->enviarWhatsApp($cliente['telefono_principal'], $mensaje);
        */
    }

    private function notificarClienteCargoAdicional($servicioId, $datosCargo)
    {
        /*
        require_once 'libraries/Notificador.php';
        $notificador = new Notificador();

        $cliente = $this->clienteModel->getClienteByServicio($servicioId);

        $mensaje = "Se ha registrado un cargo adicional a su servicio.\n"
            . "Concepto: {$datosCargo['concepto']}\n"
            . "Descripción: {$datosCargo['descripcion']}\n"
            . "Monto: S/ " . number_format($datosCargo['monto'], 2) . "\n"
            . "Este cargo se aplicará a su próxima factura.";

        $notificador->enviarWhatsApp($cliente['telefono_principal'], $mensaje);
        */
    }

    private function crearCargoCambioTitularidad($servicioId, $costo)
    {
        $datosCargo = [
            'id_servicio' => $servicioId,
            'concepto' => 'CAM_TITULARIDAD',
            'descripcion' => 'Costo por trámite de cambio de titularidad',
            'monto' => $costo,
            'origen' => 'MANUAL'
        ];

        $this->cargoAdicionalModel->crearCargoManual($datosCargo);
    }
}
// Router simple
if (isset($_GET['op'])) {
    $controller = new VentasController();
    switch ($_GET['op']) {
        case 'dashboard':
            $controller->getDashboardData();
            break;
        case 'search':
            $controller->searchServices();
            break;
        case 'search_clients':
            $controller->searchClients();
            break;
        case 'get_plans':
            header('Content-Type: application/json');
            try {
                $plans = $controller->ventasModel->getPlans();
                echo json_encode($plans);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;
        case 'get_client_stats':
            header('Content-Type: application/json');
            try {
                $stats = $controller->ventasModel->getClientStats();
                echo json_encode($stats);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;
        case 'get_plan_options':
            header('Content-Type: application/json');
            try {
                $internet = $controller->ventasModel->getInternetOptions();
                $tv = $controller->ventasModel->getTvOptions();
                echo json_encode(['internet' => $internet, 'tv' => $tv]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;
        case 'create_plan':
            header('Content-Type: application/json');
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                try {
                    // Basic validation - at least name and price required
                    if (empty($data['nombre']) || empty($data['precio'])) {
                        throw new Exception("Datos incompletos");
                    }
                    $id = $controller->ventasModel->createPlan($data);
                    if ($id)
                        echo json_encode(['success' => true, 'id' => $id]);
                    else
                        throw new Exception("Error al crear plan");
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => $e->getMessage()]);
                }
            }
            break;
        case 'get_service_details':
            header('Content-Type: application/json');
            try {
                $id = $_GET['id'];
                $service = $controller->ventasModel->getServiceById($id);
                if ($service)
                    echo json_encode($service);
                else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Servicio no encontrado']);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;
        case 'update_service':
            header('Content-Type: application/json');
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                try {
                    // Validar datos mínimos
                    if (!isset($data['id_servicio']) || !isset($data['id_plan'])) {
                        throw new Exception("Datos incompletos");
                    }

                    // Como el modelo pide dirección pero el modal tal vez no lo envía,
                    // primero recuperamos el servicio actual para mantener la dirección si no se envía nueva
                    $current = $controller->ventasModel->getServiceById($data['id_servicio']);

                    $updateData = [
                        'id_plan' => $data['id_plan'],
                        'ip' => $data['ip'] ?? $current['ip_asignada'],
                        'direccion' => $data['direccion'] ?? $current['direccion']
                    ];

                    $res = $controller->ventasModel->updateService($data['id_servicio'], $updateData);

                    if ($res)
                        echo json_encode(['success' => true]);
                    else
                        throw new Exception("Error al actualizar");
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => $e->getMessage()]);
                }
            }
            break;
        case 'validar_pago':
            // ... Logic for existing ops if called via AJAX ...
            break;
        case 'get_client_details':
            header('Content-Type: application/json');
            try {
                if (empty($_GET['id']))
                    throw new Exception("ID Requerido");
                $client = $controller->ventasModel->getClientFullDetails($_GET['id']);
                echo json_encode($client ?: ['error' => 'No encontrado']);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;

        case 'save_client':
            header('Content-Type: application/json');
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                try {
                    if (empty($data['dni']))
                        throw new Exception("DNI requerido");

                    if (!empty($data['id_cliente'])) {
                        // Update
                        $res = $controller->ventasModel->updateClient($data['id_cliente'], $data);
                        echo json_encode(['success' => true, 'message' => 'Cliente actualizado']);
                    } else {
                        // Create
                        $id = $controller->ventasModel->createClient($data);
                        if ($id)
                            echo json_encode(['success' => true, 'id' => $id, 'message' => 'Cliente creado']);
                        else
                            throw new Exception("Error al crear cliente");
                    }
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => $e->getMessage()]);
                }
            }
            break;
        case 'search_client_payment':
            header('Content-Type: application/json');
            try {
                $term = $_GET['term'] ?? '';
                if (strlen($term) < 3)
                    throw new Exception("Ingrese al menos 3 caracteres");
                $results = $controller->ventasModel->searchClientForPayment($term);
                echo json_encode($results);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;

        case 'get_payment_methods':
            header('Content-Type: application/json');
            echo json_encode($controller->ventasModel->getPaymentMethods());
            break;

        case 'register_quick_payment':
            header('Content-Type: application/json');
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                // Wrap existing logic
                // We need id_deuda. If client has no debt, we can't pay with current logic easily.
                // Frontend should force selection of a debt or we auto-select the oldest.
                $res = $controller->registrarPagoManual($data, 1); // User ID fixed to 1 for now
                if ($res['success']) {
                    echo json_encode($res);
                } else {
                    http_response_code(500);
                    echo json_encode($res);
                }
            }
            break;

        case 'get_receipt':
            header('Content-Type: application/json');
            try {
                $id = $_GET['id'];
                $data = $controller->ventasModel->getReceiptData($id);
                echo json_encode($data);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;
        case 'get_service_full_details':
            header('Content-Type: application/json');
            try {
                $id = $_GET['id'];
                $service = $controller->ventasModel->getServiceById($id);

                if ($service) {
                    $debts = $controller->ventasModel->getServiceDebtHistory($id);
                    $service['deudas'] = $debts;

                    // Add logic to calculate total debt if not in service object
                    $total_deuda = 0;
                    foreach ($debts as $d) {
                        if ($d['estado'] != 'PAGADO')
                            $total_deuda += $d['total'];
                    }
                    $service['deuda_total'] = $total_deuda;

                    echo json_encode($service);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Servicio no encontrado']);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;

        case 'get_payment_details':
            header('Content-Type: application/json');
            try {
                $id_servicio = $_GET['id_servicio'] ?? 0;
                if (!$id_servicio)
                    throw new Exception("ID Servicio requerido");

                $details = $controller->ventasModel->getNextPaymentDetails($id_servicio);
                if ($details) {
                    echo json_encode($details);
                } else {
                    echo json_encode(['error' => 'No se pudo calcular detalles']); // Or just return empty
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;
    }
}
