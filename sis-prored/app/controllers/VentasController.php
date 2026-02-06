<?php
class VentasController
{
    private $ventasModel;
    private $pagoModel;
    private $clienteModel;
    private $cargoAdicionalModel;

    public function __construct()
    {
        require_once 'models/VentasModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ClienteModel.php';
        require_once 'models/CargoAdicionalModel.php';

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

        if ($resultado) {
            // Generar comprobante
            $this->generarComprobantePago($resultado['id_pago']);

            // Reactivar servicio si estaba suspendido
            if (isset($datosPago['id_servicio'])) {
                $this->reactivarServicio($datosPago['id_servicio']);
            }

            return ['success' => true, 'pago_id' => $resultado['id_pago']];
        }

        return ['success' => false, 'error' => 'Error al registrar el pago'];
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
        require_once 'models/ServicioModel.php';
        $servicioModel = new ServicioModel();
        $servicioModel->cambiarEstado($servicioId, 'ACTIVO');
    }

    private function generarComprobantePago($pagoId)
    {
        // Lógica para generar PDF del comprobante
        require_once 'libraries/PdfGenerator.php';
        $pdfGenerator = new PdfGenerator();

        $pago = $this->pagoModel->getPagoById($pagoId);
        $cliente = $this->clienteModel->getClienteByServicio($pago['id_servicio']);

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
        require_once 'libraries/Notificador.php';
        $notificador = new Notificador();

        $pago = $this->pagoModel->getPagoById($pagoId);
        $cliente = $this->clienteModel->getClienteByServicio($pago['id_servicio']);

        $mensaje = "Su pago con referencia {$pago['referencia']} ha sido rechazado. Motivo: $motivo";

        $notificador->enviarWhatsApp($cliente['telefono_principal'], $mensaje);
    }

    private function notificarClienteCargoAdicional($servicioId, $datosCargo)
    {
        require_once 'libraries/Notificador.php';
        $notificador = new Notificador();

        $cliente = $this->clienteModel->getClienteByServicio($servicioId);

        $mensaje = "Se ha registrado un cargo adicional a su servicio.\n"
            . "Concepto: {$datosCargo['concepto']}\n"
            . "Descripción: {$datosCargo['descripcion']}\n"
            . "Monto: S/ " . number_format($datosCargo['monto'], 2) . "\n"
            . "Este cargo se aplicará a su próxima factura.";

        $notificador->enviarWhatsApp($cliente['telefono_principal'], $mensaje);
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
?>