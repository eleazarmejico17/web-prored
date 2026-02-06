<?php
require_once 'models/BaseModel.php';

class TicketModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct("ticket");
    }

    // RF-U06: Crear ticket de soporte
    public function crearTicket($data)
    {
        // Verificar si ya existe ticket abierto para el mismo servicio
        $query = "SELECT id_ticket FROM ticket 
                  WHERE id_servicio = :id_servicio 
                  AND estado IN ('ABIERTO', 'ASIGNADO', 'EN_PROCESO', 'DERIVADO')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_servicio", $data['id_servicio']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "EXISTE";
        }

        // Crear nuevo ticket
        $query = "INSERT INTO ticket (id_cliente, id_servicio, id_telefono, tipo_problema, urgencia, descripcion) 
                  VALUES (:id_cliente, :id_servicio, :id_telefono, :tipo_problema, :urgencia, :descripcion)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $data['id_cliente']);
        $stmt->bindParam(":id_servicio", $data['id_servicio']);
        $stmt->bindParam(":id_telefono", $data['id_telefono']);
        $stmt->bindParam(":tipo_problema", $data['tipo_problema']);
        $stmt->bindParam(":urgencia", $data['urgencia']);
        $stmt->bindParam(":descripcion", $data['descripcion']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // RF-S01: Gestionar tickets de soporte
    public function getTicketsPorEstado($estado = null, $id_usuario = null)
    {
        $where = " WHERE 1=1 ";

        if ($estado) {
            $where .= " AND estado = :estado ";
        }

        if ($id_usuario) {
            $where .= " AND id_usuario_asignado = :id_usuario ";
        }

        $query = "SELECT * FROM vw_tickets_completo" . $where . " ORDER BY creado_en DESC";
        $stmt = $this->conn->prepare($query);

        if ($estado) {
            $stmt->bindParam(":estado", $estado);
        }

        if ($id_usuario) {
            $stmt->bindParam(":id_usuario", $id_usuario);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function asignarTicket($id_ticket, $id_usuario)
    {
        $query = "UPDATE ticket SET estado = 'ASIGNADO', id_usuario_asignado = :id_usuario WHERE id_ticket = :id_ticket";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":id_ticket", $id_ticket);
        return $stmt->execute();
    }

    public function agregarMensaje($id_ticket, $id_usuario, $tipo, $mensaje)
    {
        $query = "INSERT INTO ticket_mensaje (id_ticket, id_usuario, tipo, mensaje) 
                  VALUES (:id_ticket, :id_usuario, :tipo, :mensaje)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_ticket", $id_ticket);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":tipo", $tipo);
        $stmt->bindParam(":mensaje", $mensaje);
        return $stmt->execute();
    }

    // RF-U07: Seguimiento de tickets
    public function getMensajesTicket($id_ticket)
    {
        $query = "SELECT tm.*, u.nombre as usuario_nombre 
                  FROM ticket_mensaje tm
                  JOIN usuario u ON tm.id_usuario = u.id_usuario
                  WHERE tm.id_ticket = :id_ticket
                  ORDER BY tm.creado_en ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_ticket", $id_ticket);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function derivarAVisitaTecnica($id_ticket, $id_tecnico, $fecha_programada)
    {
        // Actualizar estado del ticket
        $query = "UPDATE ticket SET estado = 'DERIVADO' WHERE id_ticket = :id_ticket";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_ticket", $id_ticket);
        $stmt->execute();

        // Crear visita técnica
        $query = "INSERT INTO visita_tecnica (id_ticket, id_tecnico, estado, fecha_programada) 
                  VALUES (:id_ticket, :id_tecnico, 'PROGRAMADA', :fecha_programada)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_ticket", $id_ticket);
        $stmt->bindParam(":id_tecnico", $id_tecnico);
        $stmt->bindParam(":fecha_programada", $fecha_programada);
        return $stmt->execute();
    }
}
?>