<?php
require_once 'models/BaseModel.php';

class PlanModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct("plan");
    }

    // RF-A03: Gestionar catálogo de planes
    public function getPlanesActivos()
    {
        $query = "SELECT * FROM plan WHERE estado = 'ACTIVO' ORDER BY precio ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function desactivarPlan($id_plan)
    {
        $query = "UPDATE plan SET estado = 'INACTIVO' WHERE id_plan = :id_plan";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_plan", $id_plan);
        return $stmt->execute();
    }

    public function getEstadisticasPlanes()
    {
        $query = "SELECT 
                    p.nombre,
                    p.precio,
                    COUNT(s.id_servicio) as total_clientes,
                    SUM(CASE WHEN s.estado = 'ACTIVO' THEN 1 ELSE 0 END) as activos,
                    SUM(CASE WHEN s.estado = 'SUSPENDIDO' THEN 1 ELSE 0 END) as suspendidos,
                    SUM(CASE WHEN s.estado = 'EN_MORA' THEN 1 ELSE 0 END) as en_mora
                  FROM plan p
                  LEFT JOIN servicio s ON p.id_plan = s.id_plan
                  GROUP BY p.id_plan
                  ORDER BY total_clientes DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>