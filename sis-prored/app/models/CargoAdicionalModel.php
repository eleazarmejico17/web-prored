<?php
require_once 'models/BaseModel.php';

class CargoAdicionalModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct("cargo_adicional");
    }

    // RF-V04: Registrar cargos adicionales
    public function crearCargoManual($data)
    {
        $query = "INSERT INTO cargo_adicional (id_servicio, id_periodo, concepto, descripcion, monto, origen, estado) 
                  VALUES (:id_servicio, :id_periodo, :concepto, :descripcion, :monto, 'MANUAL', 'APLICADO')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_servicio", $data['id_servicio']);
        $stmt->bindParam(":id_periodo", $data['id_periodo']);
        $stmt->bindParam(":concepto", $data['concepto']);
        $stmt->bindParam(":descripcion", $data['descripcion']);
        $stmt->bindParam(":monto", $data['monto']);

        if ($stmt->execute()) {
            // Actualizar deuda del período
            $this->actualizarDeudaPeriodo($data['id_servicio'], $data['id_periodo']);
            return true;
        }
        return false;
    }

    private function actualizarDeudaPeriodo($id_servicio, $id_periodo)
    {
        // Calcular total de cargos adicionales
        $query = "SELECT SUM(monto) as total_cargos 
                  FROM cargo_adicional 
                  WHERE id_servicio = :id_servicio 
                  AND id_periodo = :id_periodo 
                  AND estado = 'APLICADO'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_servicio", $id_servicio);
        $stmt->bindParam(":id_periodo", $id_periodo);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $total_cargos = $result['total_cargos'] ?: 0;

        // Actualizar deuda
        $query = "UPDATE deuda SET total = monto_base + :total_cargos + mora 
                  WHERE id_servicio = :id_servicio AND id_periodo = :id_periodo";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":total_cargos", $total_cargos);
        $stmt->bindParam(":id_servicio", $id_servicio);
        $stmt->bindParam(":id_periodo", $id_periodo);
        $stmt->execute();
    }

    public function getCargosPorServicio($id_servicio, $id_periodo = null)
    {
        $where = " WHERE id_servicio = :id_servicio ";

        if ($id_periodo) {
            $where .= " AND id_periodo = :id_periodo ";
        }

        $query = "SELECT ca.*, p.mes, p.anio 
                  FROM cargo_adicional ca
                  JOIN periodos p ON ca.id_periodo = p.id_periodo
                  " . $where . "
                  ORDER BY p.anio DESC, p.mes DESC, ca.creado_en DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_servicio", $id_servicio);

        if ($id_periodo) {
            $stmt->bindParam(":id_periodo", $id_periodo);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>