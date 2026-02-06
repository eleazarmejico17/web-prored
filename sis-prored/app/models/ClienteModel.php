<?php
require_once 'models/BaseModel.php';

class ClienteModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct("cliente");
    }

    // RF-U05: Gestionar números de contacto
    public function getTelefonosByCliente($id_cliente)
    {
        $query = "SELECT * FROM cliente_telefono WHERE id_cliente = :id_cliente AND activo = 1 ORDER BY principal DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiciosByCliente($id_cliente)
    {
        $query = "SELECT * FROM vw_cliente_servicios WHERE id_cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstadoCuenta($id_cliente)
    {
        $query = "SELECT * FROM vw_estado_cuenta WHERE id_cliente = :id_cliente ORDER BY anio DESC, mes DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistorialPagos($id_cliente)
    {
        $query = "SELECT * FROM vw_historial_pagos_cliente WHERE cliente LIKE :cliente ORDER BY fecha_pago DESC";
        $stmt = $this->conn->prepare($query);
        $cliente = "%" . $this->getNombreCompleto($id_cliente) . "%";
        $stmt->bindParam(":cliente", $cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getNombreCompleto($id_cliente)
    {
        $query = "SELECT CONCAT(nombres, ' ', apellidos) as nombre FROM cliente WHERE id_cliente = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id_cliente);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nombre'];
    }

    // RF-V06: Alertas de morosidad
    public function getClientesEnMora()
    {
        $query = "SELECT * FROM vw_servicios_mora ORDER BY dias_mora DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>