<?php
class TicketController
{
    private $ticketModel;

    public function __construct()
    {
        require_once 'models/TicketModel.php';
        $this->ticketModel = new TicketModel();
    }

    // Obtener estadísticas de tickets
    public function obtenerEstadisticasTickets($periodo = 'mensual')
    {
        return $this->ticketModel->getEstadisticasTickets($periodo);
    }

    // Calcular SLA (Service Level Agreement)
    public function calcularSLATickets()
    {
        $tickets = $this->ticketModel->getTicketsSLA();

        $estadisticas = [
            'total_tickets' => 0,
            'dentro_sla' => 0,
            'fuera_sla' => 0,
            'tasa_cumplimiento' => 0
        ];

        foreach ($tickets as $ticket) {
            $estadisticas['total_tickets']++;

            if ($this->verificarSLA($ticket)) {
                $estadisticas['dentro_sla']++;
            } else {
                $estadisticas['fuera_sla']++;
            }
        }

        if ($estadisticas['total_tickets'] > 0) {
            $estadisticas['tasa_cumplimiento'] =
                ($estadisticas['dentro_sla'] / $estadisticas['total_tickets']) * 100;
        }

        return $estadisticas;
    }

    private function verificarSLA($ticket)
    {
        $creadoEn = strtotime($ticket['creado_en']);
        $primeraRespuesta = strtotime($ticket['primera_respuesta']);

        if (!$primeraRespuesta) {
            return false; // Sin respuesta aún
        }

        $horasTranscurridas = ($primeraRespuesta - $creadoEn) / 3600;

        // SLA: Primera respuesta en máximo 2 horas
        return $horasTranscurridas <= 2;
    }

    // Cerrar tickets antiguos automáticamente
    public function cerrarTicketsInactivos()
    {
        $tickets = $this->ticketModel->getTicketsInactivos();

        $cerrados = 0;
        foreach ($tickets as $ticket) {
            if ($this->ticketModel->cerrarTicketAutomatico($ticket['id_ticket'])) {
                $cerrados++;
            }
        }

        return ['cerrados' => $cerrados, 'total' => count($tickets)];
    }
}
?>