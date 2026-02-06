<?php
class SoporteController
{
    private $ticketModel;
    private $clienteModel;
    private $visitaTecnicaModel;

    public function __construct()
    {
        require_once 'models/TicketModel.php';
        require_once 'models/ClienteModel.php';
        require_once 'models/VisitaTecnicaModel.php';

        $this->ticketModel = new TicketModel();
        $this->clienteModel = new ClienteModel();
        $this->visitaTecnicaModel = new VisitaTecnicaModel();
    }

    // RF-S01: Gestionar Tickets de Soporte
    public function obtenerTickets($filtros = [])
    {
        return $this->ticketModel->getTicketsPorEstado($filtros);
    }

    public function asignarTicket($ticketId, $usuarioId, $asignarAMi = true)
    {
        if ($asignarAMi) {
            $tecnicoId = $usuarioId;
        } else {
            $tecnicoId = $_POST['id_tecnico'];
        }

        return $this->ticketModel->asignarTicket($ticketId, $tecnicoId);
    }

    public function cambiarEstadoTicket($ticketId, $nuevoEstado)
    {
        return $this->ticketModel->cambiarEstado($ticketId, $nuevoEstado);
    }

    // RF-S02: Comunicación con Cliente en Tickets
    public function agregarMensajeTicket($ticketId, $usuarioId, $mensaje, $tipo = 'ACTUALIZACION')
    {
        return $this->ticketModel->agregarMensaje($ticketId, $usuarioId, $tipo, $mensaje);
    }

    public function obtenerMensajesTicket($ticketId)
    {
        return $this->ticketModel->getMensajesTicket($ticketId);
    }

    // RF-S03: Marcar Ticket como Resuelto
    public function marcarTicketResuelto($ticketId, $mensajeResolucion, $usuarioId)
    {
        // Cambiar estado a resuelto
        $this->ticketModel->cambiarEstado($ticketId, 'RESUELTO');

        // Agregar mensaje de resolución
        $this->ticketModel->agregarMensaje($ticketId, $usuarioId, 'RESOLUCION', $mensajeResolucion);

        // Notificar al cliente
        $this->notificarClienteTicketResuelto($ticketId);

        return true;
    }

    public function cerrarTicket($ticketId)
    {
        return $this->ticketModel->cambiarEstado($ticketId, 'CERRADO');
    }

    // RF-S04: Consultar Datos del Cliente y Servicio
    public function obtenerInformacionCliente($clienteId)
    {
        $cliente = $this->clienteModel->getClienteById($clienteId);
        $servicios = $this->clienteModel->getServiciosByCliente($clienteId);
        $tickets = $this->ticketModel->getTicketsPorCliente($clienteId);
        $estadoCuenta = $this->clienteModel->getEstadoCuenta($clienteId);

        return [
            'cliente' => $cliente,
            'servicios' => $servicios,
            'tickets' => $tickets,
            'estado_cuenta' => $estadoCuenta
        ];
    }

    public function derivarAVisitaTecnica($ticketId, $tecnicoId, $fechaProgramada, $observaciones)
    {
        $resultado = $this->visitaTecnicaModel->crearVisita($ticketId, $tecnicoId, $fechaProgramada, $observaciones);

        if ($resultado) {
            // Cambiar estado del ticket
            $this->ticketModel->cambiarEstado($ticketId, 'DERIVADO');

            // Notificar al cliente
            $this->notificarClienteVisitaProgramada($ticketId, $fechaProgramada);

            return ['success' => true, 'visita_id' => $resultado];
        }

        return ['success' => false, 'error' => 'Error al derivar a visita técnica'];
    }

    // Métodos auxiliares
    private function notificarClienteTicketResuelto($ticketId)
    {
        require_once 'libraries/Notificador.php';
        $notificador = new Notificador();

        $ticket = $this->ticketModel->getTicketById($ticketId);
        $cliente = $this->clienteModel->getClienteById($ticket['id_cliente']);

        $mensaje = "Su ticket #{$ticket['numero_ticket']} ha sido marcado como resuelto.\n"
            . "Por favor, confirme si su problema ha sido solucionado en los próximos 48 horas.";

        $notificador->enviarWhatsApp($cliente['telefono_principal'], $mensaje);
    }

    private function notificarClienteVisitaProgramada($ticketId, $fechaProgramada)
    {
        require_once 'libraries/Notificador.php';
        $notificador = new Notificador();

        $ticket = $this->ticketModel->getTicketById($ticketId);
        $cliente = $this->clienteModel->getClienteById($ticket['id_cliente']);

        $fechaFormateada = date('d/m/Y H:i', strtotime($fechaProgramada));

        $mensaje = "Su ticket #{$ticket['numero_ticket']} ha sido derivado a visita técnica.\n"
            . "Fecha programada: $fechaFormateada\n"
            . "Un técnico se comunicará con usted antes de la visita.";

        $notificador->enviarWhatsApp($cliente['telefono_principal'], $mensaje);
    }
}
?>