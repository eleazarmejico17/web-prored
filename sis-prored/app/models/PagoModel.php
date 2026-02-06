<?php
require_once 'models/BaseModel.php';

class PagoModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct("pagos");
    }

    // RF-U03: Reportar pago con comprobante
    public function reportarPago($data)
    {
        // Generar referencia única
        $referencia = "PAG-" . date('Y') . "-" . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $query = "INSERT INTO pagos (id_deuda, id_metodo_pago, monto, numero_operacion, banco, estado, fecha_pago, referencia) 
                  VALUES (:id_deuda, :id_metodo_pago, :monto, :numero_operacion, :banco, 'PENDIENTE', :fecha_pago, :referencia)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_deuda", $data['id_deuda']);
        $stmt->bindParam(":id_metodo_pago", $data['id_metodo_pago']);
        $stmt->bindParam(":monto", $data['monto']);
        $stmt->bindParam(":numero_operacion", $data['numero_operacion']);
        $stmt->bindParam(":banco", $data['banco']);
        $stmt->bindParam(":fecha_pago", $data['fecha_pago']);
        $stmt->bindParam(":referencia", $referencia);

        if ($stmt->execute()) {
            return $referencia;
        }
        return false;
    }

    // RF-V01: Validar pagos reportados
    public function getPagosPendientes()
    {
        $query = "SELECT * FROM vw_pagos_pendientes ORDER BY fecha_pago ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validarPago($id_pago, $id_usuario, $accion)
    {
        $estado = ($accion == 'aprobar') ? 'VALIDADO' : 'RECHAZADO';

        $query = "UPDATE pagos SET estado = :estado, id_usuario = :id_usuario WHERE id_pago = :id_pago";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":id_pago", $id_pago);

        if ($stmt->execute() && $accion == 'aprobar') {
            // Actualizar deuda
            $this->actualizarDeuda($id_pago);
            // Generar comprobante
            $this->generarComprobante($id_pago);
        }

        return $stmt->rowCount();
    }

    private function actualizarDeuda($id_pago)
    {
        // Obtener información del pago
        $query = "SELECT id_deuda, monto FROM pagos WHERE id_pago = :id_pago";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_pago", $id_pago);
        $stmt->execute();
        $pago = $stmt->fetch(PDO::FETCH_ASSOC);

        // Actualizar deuda
        $query = "UPDATE deuda SET estado = 'PAGADO' WHERE id_deuda = :id_deuda";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_deuda", $pago['id_deuda']);
        $stmt->execute();
    }

    private function generarComprobante($id_pago)
    {
        // Generar número de comprobante
        $numero = "COMP-" . date('Y') . "-" . str_pad($id_pago, 6, '0', STR_PAD_LEFT);

        $query = "INSERT INTO pago_comprobante (id_pago, numero, fecha_emision) 
                  VALUES (:id_pago, :numero, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_pago", $id_pago);
        $stmt->bindParam(":numero", $numero);
        $stmt->execute();
    }

    // RF-V03: Envío por WhatsApp
    public function getComprobantesPendientesEnvio()
    {
        $query = "SELECT 
                    pc.*,
                    p.monto,
                    p.fecha_pago,
                    c.nombres,
                    c.apellidos,
                    ct.numero as telefono
                  FROM pago_comprobante pc
                  JOIN pagos p ON pc.id_pago = p.id_pago
                  JOIN deuda d ON p.id_deuda = d.id_deuda
                  JOIN servicio s ON d.id_servicio = s.id_servicio
                  JOIN cliente c ON s.id_cliente = c.id_cliente
                  LEFT JOIN cliente_telefono ct ON c.id_cliente = ct.id_cliente AND ct.principal = 1
                  WHERE NOT EXISTS (
                    SELECT 1 FROM envio_whatsapp ew WHERE ew.id_comprobante = pc.id_comprobante
                  )";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarEnvioWhatsApp($id_comprobante, $id_usuario, $telefono)
    {
        $query = "INSERT INTO envio_whatsapp (id_comprobante, id_usuario, telefono, estado, fecha_envio) 
                  VALUES (:id_comprobante, :id_usuario, :telefono, 'ENVIADO', NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_comprobante", $id_comprobante);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":telefono", $telefono);
        return $stmt->execute();
    }
}
?>