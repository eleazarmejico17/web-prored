<?php
require_once 'models/BaseModel.php';

class VisitaTecnicaModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct("visita_tecnica");
    }

    // RF-T01: Recibir y ver visitas asignadas
    public function getVisitasPorTecnico($id_tecnico, $estado = null)
    {
        $where = " WHERE id_tecnico = :id_tecnico ";

        if ($estado) {
            $where .= " AND estado = :estado ";
        }

        $query = "SELECT * FROM vw_visitas_tecnicas" . $where . " ORDER BY fecha_programada ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_tecnico", $id_tecnico);

        if ($estado) {
            $stmt->bindParam(":estado", $estado);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // RF-T02: Actualizar estado de visita técnica
    public function actualizarEstado($id_visita, $estado)
    {
        $query = "UPDATE visita_tecnica SET estado = :estado ";

        if ($estado == 'EN_CAMINO') {
            $query .= ", inicio = NOW() ";
        } elseif ($estado == 'CONCLUIDA') {
            $query .= ", fin = NOW() ";
        }

        $query .= " WHERE id_visita = :id_visita";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":id_visita", $id_visita);
        return $stmt->execute();
    }

    // RF-T03: Reportar materiales utilizados
    public function reportarMateriales($id_visita, $materiales)
    {
        try {
            $this->conn->beginTransaction();

            foreach ($materiales as $material) {
                $query = "INSERT INTO visita_material (id_visita, id_material, cantidad, precio_unitario, total) 
                          VALUES (:id_visita, :id_material, :cantidad, :precio_unitario, :total)";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":id_visita", $id_visita);
                $stmt->bindParam(":id_material", $material['id_material']);
                $stmt->bindParam(":cantidad", $material['cantidad']);
                $stmt->bindParam(":precio_unitario", $material['precio_unitario']);

                $total = $material['cantidad'] * $material['precio_unitario'];
                $stmt->bindParam(":total", $total);
                $stmt->execute();
            }

            // Crear cargo adicional pendiente
            $this->crearCargoAdicional($id_visita, $materiales);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    private function crearCargoAdicional($id_visita, $materiales)
    {
        // Obtener información de la visita
        $query = "SELECT t.id_servicio FROM visita_tecnica vt
                  JOIN ticket t ON vt.id_ticket = t.id_ticket
                  WHERE vt.id_visita = :id_visita";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_visita", $id_visita);
        $stmt->execute();
        $visita = $stmt->fetch(PDO::FETCH_ASSOC);

        // Calcular total
        $total = 0;
        $descripcion = "Materiales utilizados: ";
        foreach ($materiales as $material) {
            $total += $material['cantidad'] * $material['precio_unitario'];
            $descripcion .= $material['nombre'] . " (" . $material['cantidad'] . "), ";
        }
        $descripcion = rtrim($descripcion, ", ");

        // Obtener próximo período
        $query = "SELECT id_periodo FROM periodos WHERE fecha_inicio > CURDATE() ORDER BY fecha_inicio ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $periodo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Crear cargo adicional
        $query = "INSERT INTO cargo_adicional (id_servicio, id_periodo, concepto, descripcion, monto, origen, estado) 
                  VALUES (:id_servicio, :id_periodo, 'MATERIALES_TECNICOS', :descripcion, :monto, 'VISITA_TECNICA', 'PENDIENTE')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_servicio", $visita['id_servicio']);
        $stmt->bindParam(":id_periodo", $periodo['id_periodo']);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":monto", $total);
        $stmt->execute();
    }

    // RF-V04: Materiales pendientes de cobro
    public function getMaterialesPendientes()
    {
        $query = "SELECT * FROM vw_materiales_pendientes WHERE estado = 'CONCLUIDA'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function procesarMateriales($id_visita)
    {
        // Actualizar estado de los cargos adicionales
        $query = "UPDATE cargo_adicional ca
                  JOIN visita_tecnica vt ON ca.descripcion LIKE CONCAT('%Visita ', vt.id_visita, '%')
                  SET ca.estado = 'APLICADO'
                  WHERE vt.id_visita = :id_visita AND ca.origen = 'VISITA_TECNICA'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_visita", $id_visita);
        return $stmt->execute();
    }
}
?>