<?php
require_once 'models/BaseModel.php';

class ServicioModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct("servicio");
    }

    public function getServicioCompleto($id_servicio)
    {
        $query = "SELECT 
                    s.*, 
                    c.nombres, 
                    c.apellidos, 
                    c.dni,
                    p.nombre as plan_nombre,
                    p.velocidad_bajada,
                    p.velocidad_subida,
                    p.precio as precio_plan,
                    d.nombre as distrito
                  FROM servicio s
                  JOIN cliente c ON s.id_cliente = c.id_cliente
                  JOIN plan p ON s.id_plan = p.id_plan
                  LEFT JOIN distrito d ON s.id_distrito = d.id_distrito
                  WHERE s.id_servicio = :id_servicio";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_servicio", $id_servicio);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cambiarEstado($id_servicio, $estado)
    {
        $query = "UPDATE servicio SET estado = :estado WHERE id_servicio = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":id", $id_servicio);
        return $stmt->execute();
    }

    // RF-V05: Cambio de titularidad
    public function cambiarTitularidad($id_servicio, $nuevo_cliente_id)
    {
        $query = "UPDATE servicio SET id_cliente = :nuevo_cliente WHERE id_servicio = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nuevo_cliente", $nuevo_cliente_id);
        $stmt->bindParam(":id", $id_servicio);
        return $stmt->execute();
    }

    public function verificarDeuda($id_servicio)
    {
        $query = "SELECT SUM(total) as deuda_total 
                  FROM deuda 
                  WHERE id_servicio = :id_servicio AND estado = 'PENDIENTE'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_servicio", $id_servicio);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['deuda_total'] > 0;
    }
}
?>