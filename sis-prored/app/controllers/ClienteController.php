<?php
class ClienteController
{
    private $clienteModel;
    private $servicioModel;
    private $pagoModel;
    private $ticketModel;

    public function __construct()
    {
        require_once 'models/ClienteModel.php';
        require_once 'models/ServicioModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/TicketModel.php';

        $this->clienteModel = new ClienteModel();
        $this->servicioModel = new ServicioModel();
        $this->pagoModel = new PagoModel();
        $this->ticketModel = new TicketModel();
    }

    // RF-U01: Visualizar Servicios Contratados
    public function obtenerServiciosCliente($clienteId)
    {
        return $this->clienteModel->getServiciosByCliente($clienteId);
    }

    // RF-U02: Consultar Estado de Cuenta
    public function obtenerEstadoCuenta($clienteId)
    {
        return $this->clienteModel->getEstadoCuenta($clienteId);
    }

    // RF-U03: Reportar Pago con Comprobante
    public function reportarPago($data, $archivoComprobante)
    {
        // Validar datos según método de pago
        $metodoPago = $data['metodo_pago'];
        $validacion = $this->validarDatosPago($data, $metodoPago);

        if (!$validacion['valido']) {
            return ['success' => false, 'error' => $validacion['mensaje']];
        }

        // Subir comprobante
        $rutaComprobante = $this->subirComprobante($archivoComprobante);

        if (!$rutaComprobante) {
            return ['success' => false, 'error' => 'Error al subir el comprobante'];
        }

        // Generar referencia única
        $referencia = $this->generarReferenciaPago();

        // Registrar pago
        $data['referencia'] = $referencia;
        $data['ruta_comprobante'] = $rutaComprobante;

        $result = $this->pagoModel->reportarPago($data);

        if ($result) {
            return [
                'success' => true,
                'referencia' => $referencia,
                'mensaje' => 'Pago reportado exitosamente. Referencia: ' . $referencia
            ];
        }

        return ['success' => false, 'error' => 'Error al reportar el pago'];
    }

    // RF-U04: Consultar Historial de Pagos
    public function obtenerHistorialPagos($clienteId, $filtros = [])
    {
        return $this->clienteModel->getHistorialPagos($clienteId, $filtros);
    }

    // RF-U05: Gestionar Números de Contacto
    public function gestionarTelefonos($clienteId, $accion, $datosTelefono = [])
    {
        switch ($accion) {
            case 'agregar':
                return $this->clienteModel->agregarTelefono($clienteId, $datosTelefono);
            case 'editar':
                return $this->clienteModel->editarTelefono($datosTelefono['id_telefono'], $datosTelefono);
            case 'eliminar':
                return $this->clienteModel->eliminarTelefono($datosTelefono['id_telefono']);
            case 'marcar_principal':
                return $this->clienteModel->marcarTelefonoPrincipal($clienteId, $datosTelefono['id_telefono']);
            default:
                return false;
        }
    }

    // RF-U06: Crear Ticket de Soporte
    public function crearTicketSoporte($datosTicket)
    {
        // Verificar si ya existe ticket abierto para el mismo servicio
        $ticketAbierto = $this->ticketModel->verificarTicketAbierto($datosTicket['id_servicio']);

        if ($ticketAbierto) {
            return [
                'success' => false,
                'error' => 'Ya existe un ticket abierto para este servicio'
            ];
        }

        // Generar número de ticket único
        $numeroTicket = $this->generarNumeroTicket();
        $datosTicket['numero_ticket'] = $numeroTicket;

        // Crear ticket
        $ticketId = $this->ticketModel->crearTicket($datosTicket);

        if ($ticketId) {
            return [
                'success' => true,
                'ticket_id' => $ticketId,
                'numero_ticket' => $numeroTicket,
                'mensaje' => 'Ticket creado exitosamente. Número: ' . $numeroTicket
            ];
        }

        return ['success' => false, 'error' => 'Error al crear el ticket'];
    }

    // RF-U07: Seguimiento de Tickets
    public function obtenerTicketsCliente($clienteId, $estado = null)
    {
        return $this->ticketModel->getTicketsPorCliente($clienteId, $estado);
    }

    public function agregarComentarioTicket($ticketId, $clienteId, $comentario)
    {
        // Verificar que el ticket pertenece al cliente
        $ticket = $this->ticketModel->getTicketById($ticketId);

        if ($ticket['id_cliente'] != $clienteId) {
            return ['success' => false, 'error' => 'No tienes permisos para este ticket'];
        }

        return $this->ticketModel->agregarMensaje($ticketId, $clienteId, 'CLIENTE', $comentario);
    }

    public function calificarTicket($ticketId, $clienteId, $calificacion)
    {
        return $this->ticketModel->calificarTicket($ticketId, $calificacion);
    }

    // RF-U08: Visualizar Consumo de Internet
    public function obtenerConsumoInternet($servicioId)
    {
        // Esta función requeriría integración con el sistema de monitoreo
        // Por ahora, retornamos datos de ejemplo
        return [
            'consumo_mes_actual' => '120 GB',
            'consumo_mes_anterior' => '110 GB',
            'velocidad_promedio' => '45 Mbps',
            'horas_pico' => '19:00 - 22:00',
            'dia_mayor_consumo' => '15/02/2024 (15 GB)'
        ];
    }

    // Métodos auxiliares
    private function validarDatosPago($data, $metodoPago)
    {
        $camposRequeridos = [];

        switch ($metodoPago) {
            case 'YAPE':
            case 'PLIN':
                $camposRequeridos = ['numero_operacion', 'monto', 'fecha'];
                break;
            case 'TRANSFERENCIA':
            case 'DEPOSITO':
                $camposRequeridos = ['banco', 'numero_operacion', 'monto', 'fecha'];
                break;
            case 'EFECTIVO':
                $camposRequeridos = ['monto', 'fecha', 'lugar_pago'];
                break;
        }

        foreach ($camposRequeridos as $campo) {
            if (empty($data[$campo])) {
                return ['valido' => false, 'mensaje' => "El campo $campo es requerido"];
            }
        }

        return ['valido' => true];
    }

    private function subirComprobante($archivo)
    {
        $directorio = 'uploads/comprobantes/';
        $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
        $rutaCompleta = $directorio . $nombreArchivo;

        // Validar tipo de archivo
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'pdf'];
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas)) {
            return false;
        }

        // Validar tamaño (máximo 5MB)
        if ($archivo['size'] > 5 * 1024 * 1024) {
            return false;
        }

        if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            return $rutaCompleta;
        }

        return false;
    }

    private function generarReferenciaPago()
    {
        return 'PAG-' . date('Y') . '-' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function generarNumeroTicket()
    {
        return 'TKT-' . date('Ymd') . '-' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    }
}
?>