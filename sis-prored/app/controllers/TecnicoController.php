<?php
class TecnicoController
{
    private $visitaTecnicaModel;
    private $ticketModel;
    private $materialModel;

    public function __construct()
    {
        require_once 'models/VisitaTecnicaModel.php';
        require_once 'models/TicketModel.php';
        require_once 'models/MaterialModel.php';

        $this->visitaTecnicaModel = new VisitaTecnicaModel();
        $this->ticketModel = new TicketModel();
        $this->materialModel = new MaterialModel();
    }

    // RF-T01: Recibir y Ver Visitas Asignadas
    public function obtenerVisitasTecnico($tecnicoId, $estado = null)
    {
        return $this->visitaTecnicaModel->getVisitasPorTecnico($tecnicoId, $estado);
    }

    public function obtenerDetalleVisita($visitaId)
    {
        $visita = $this->visitaTecnicaModel->getVisitaById($visitaId);
        $ticket = $this->ticketModel->getTicketById($visita['id_ticket']);
        $cliente = $this->clienteModel->getClienteById($ticket['id_cliente']);
        $historial = $this->visitaTecnicaModel->getHistorialVisitas($ticket['id_servicio']);

        return [
            'visita' => $visita,
            'ticket' => $ticket,
            'cliente' => $cliente,
            'historial' => $historial
        ];
    }

    // RF-T02: Actualizar Estado de Visita Técnica
    public function actualizarEstadoVisita($visitaId, $estado, $datosAdicionales = [])
    {
        $resultado = $this->visitaTecnicaModel->actualizarEstado($visitaId, $estado);

        if ($resultado) {
            // Notificar al cliente según el estado
            $this->notificarClienteCambioEstado($visitaId, $estado, $datosAdicionales);

            return ['success' => true];
        }

        return ['success' => false, 'error' => 'Error al actualizar el estado'];
    }

    // RF-T03: Reportar Materiales y Elementos Utilizados
    public function obtenerCatalogoMateriales()
    {
        return $this->materialModel->getMaterialesActivos();
    }

    public function reportarMateriales($visitaId, $materiales, $descripcionTrabajo)
    {
        $resultado = $this->visitaTecnicaModel->reportarMateriales($visitaId, $materiales, $descripcionTrabajo);

        if ($resultado) {
            // Notificar al área de ventas
            $this->notificarVentasMateriales($visitaId);

            // Notificar al cliente
            $this->notificarClienteMateriales($visitaId);

            return ['success' => true];
        }

        return ['success' => false, 'error' => 'Error al reportar materiales'];
    }

    // RF-T04: Completar Reporte de Visita
    public function completarReporteVisita($visitaId, $datosReporte)
    {
        $resultado = $this->visitaTecnicaModel->completarReporte($visitaId, $datosReporte);

        if ($resultado) {
            // Actualizar estado del ticket si se resolvió
            if ($datosReporte['problema_resuelto'] === 'SI') {
                $visita = $this->visitaTecnicaModel->getVisitaById($visitaId);
                $this->ticketModel->cambiarEstado($visita['id_ticket'], 'RESUELTO');
            }

            // Enviar reporte al cliente
            $this->enviarReporteCliente($visitaId, $datosReporte);

            return ['success' => true];
        }

        return ['success' => false, 'error' => 'Error al completar el reporte'];
    }

    // RF-T05: Consultar Historial Técnico del Servicio
    public function obtenerHistorialTecnico($servicioId)
    {
        return $this->visitaTecnicaModel->getHistorialVisitas($servicioId);
    }

    // Métodos auxiliares
    private function notificarClienteCambioEstado($visitaId, $estado, $datosAdicionales)
    {
        require_once 'libraries/Notificador.php';
        $notificador = new Notificador();

        $visita = $this->visitaTecnicaModel->getVisitaById($visitaId);
        $ticket = $this->ticketModel->getTicketById($visita['id_ticket']);
        $cliente = $this->clienteModel->getClienteById($ticket['id_cliente']);

        $mensajes = [
            'EN_CAMINO' => "El técnico está en camino a su dirección. Llegada estimada en 15-30 minutos.",
            'ATENDIENDO' => "El técnico ha llegado y está atendiendo su servicio.",
            'CONCLUIDA' => "La visita técnica ha sido completada. Recibirá un reporte detallado pronto."
        ];

        if (isset($mensajes[$estado])) {
            $notificador->enviarWhatsApp($cliente['telefono_principal'], $mensajes[$estado]);
        }
    }

    private function notificarVentasMateriales($visitaId)
    {
        // Implementar notificación interna al área de ventas
        // Puede ser mediante email, notificación en sistema, etc.
    }

    private function notificarClienteMateriales($visitaId)
    {
        require_once 'libraries/Notificador.php';
        $notificador = new Notificador();

        $visita = $this->visitaTecnicaModel->getVisitaById($visitaId);
        $materiales = $this->visitaTecnicaModel->obtenerMaterialesVisita($visitaId);
        $ticket = $this->ticketModel->getTicketById($visita['id_ticket']);
        $cliente = $this->clienteModel->getClienteById($ticket['id_cliente']);

        $total = 0;
        $detalle = "";
        foreach ($materiales as $material) {
            $total += $material['total'];
            $detalle .= "• {$material['nombre']}: {$material['cantidad']} x S/ {$material['precio_unitario']} = S/ {$material['total']}\n";
        }

        $mensaje = "Durante su visita técnica se utilizaron materiales adicionales:\n"
            . $detalle
            . "Total: S/ " . number_format($total, 2) . "\n"
            . "Este monto será agregado a su próxima factura.";

        $notificador->enviarWhatsApp($cliente['telefono_principal'], $mensaje);
    }

    private function enviarReporteCliente($visitaId, $datosReporte)
    {
        require_once 'libraries/Notificador.php';
        require_once 'libraries/PdfGenerator.php';

        $notificador = new Notificador();
        $pdfGenerator = new PdfGenerator();

        $visita = $this->visitaTecnicaModel->getVisitaById($visitaId);
        $ticket = $this->ticketModel->getTicketById($visita['id_ticket']);
        $cliente = $this->clienteModel->getClienteById($ticket['id_cliente']);

        // Generar PDF del reporte
        $datosReportePdf = array_merge($datosReporte, [
            'cliente' => $cliente['nombre_completo'],
            'direccion' => $ticket['direccion_servicio'],
            'fecha_visita' => date('d/m/Y H:i', strtotime($visita['fecha_programada'])),
            'tecnico' => $visita['nombre_tecnico']
        ]);

        $rutaPdf = $pdfGenerator->generarReporteVisita($datosReportePdf);

        // Enviar por WhatsApp
        $mensaje = "Adjuntamos el reporte de su visita técnica realizada el " . date('d/m/Y') . ".\n"
            . "Estado: " . $datosReporte['estado_final'] . "\n"
            . "Técnico: " . $visita['nombre_tecnico'];

        $notificador->enviarWhatsAppConArchivo($cliente['telefono_principal'], $mensaje, $rutaPdf);
    }
}
?>