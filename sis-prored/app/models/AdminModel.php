<?php
require_once 'models/BaseModel.php';

class AdminModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct("usuario");
    }

    // RF-A01: Gestionar empleados
    public function crearEmpleado($data)
    {
        // Generar contraseña temporal
        $password_temp = bin2hex(random_bytes(4));
        $password_hash = password_hash($password_temp, PASSWORD_BCRYPT);

        $query = "INSERT INTO usuario (id_rol, nombre, email, password, activo) 
                  VALUES (:id_rol, :nombre, :email, :password, 1)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_rol", $data['id_rol']);
        $stmt->bindParam(":nombre", $data['nombre']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":password", $password_hash);

        if ($stmt->execute()) {
            return $password_temp; // Retornar contraseña temporal para enviar al usuario
        }
        return false;
    }

    // RF-A06: Reportes y estadísticas
    public function getReporteFinanciero($mes = null, $anio = null)
    {
        $where = " WHERE 1=1 ";

        if ($mes && $anio) {
            $where .= " AND mes = :mes AND anio = :anio ";
        }

        $query = "SELECT * FROM vw_reporte_financiero_mensual" . $where . " ORDER BY anio DESC, mes DESC";
        $stmt = $this->conn->prepare($query);

        if ($mes && $anio) {
            $stmt->bindParam(":mes", $mes);
            $stmt->bindParam(":anio", $anio);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReporteMorosidad()
    {
        $query = "SELECT 
                    COUNT(*) as total_servicios_mora,
                    SUM(total_deuda) as monto_total_mora,
                    AVG(dias_mora) as promedio_dias_mora,
                    SUM(CASE WHEN dias_mora <= 3 THEN 1 ELSE 0 END) as mora_leve,
                    SUM(CASE WHEN dias_mora > 3 AND dias_mora <= 7 THEN 1 ELSE 0 END) as mora_media,
                    SUM(CASE WHEN dias_mora > 7 THEN 1 ELSE 0 END) as mora_alta
                  FROM vw_servicios_mora";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReporteTickets($fecha_inicio = null, $fecha_fin = null)
    {
        $where = " WHERE 1=1 ";

        if ($fecha_inicio && $fecha_fin) {
            $where .= " AND DATE(creado_en) BETWEEN :fecha_inicio AND :fecha_fin ";
        }

        $query = "SELECT 
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN estado = 'RESUELTO' OR estado = 'CERRADO' THEN 1 ELSE 0 END) as resueltos,
                    AVG(TIMESTAMPDIFF(HOUR, creado_en, 
                        CASE WHEN estado IN ('RESUELTO', 'CERRADO') THEN 
                            (SELECT MAX(creado_en) FROM ticket_mensaje WHERE id_ticket = t.id_ticket)
                        ELSE NULL END)) as tiempo_resolucion_promedio,
                    tipo_problema,
                    COUNT(*) as cantidad
                  FROM ticket t
                  " . $where . "
                  GROUP BY tipo_problema
                  ORDER BY cantidad DESC";

        $stmt = $this->conn->prepare($query);

        if ($fecha_inicio && $fecha_fin) {
            $stmt->bindParam(":fecha_inicio", $fecha_inicio);
            $stmt->bindParam(":fecha_fin", $fecha_fin);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductividadEmpleados()
    {
        $query = "SELECT * FROM vw_productividad_empleados ORDER BY pagos_validados DESC, tickets_atendidos DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // RF-A07: Auditoría del sistema
    public function getLogsAuditoria($fecha_inicio = null, $fecha_fin = null, $id_usuario = null)
    {
        $where = " WHERE 1=1 ";

        if ($fecha_inicio && $fecha_fin) {
            $where .= " AND DATE(al.creado_en) BETWEEN :fecha_inicio AND :fecha_fin ";
        }

        if ($id_usuario) {
            $where .= " AND al.id_usuario = :id_usuario ";
        }

        $query = "SELECT 
                    al.*,
                    u.nombre as usuario_nombre,
                    u.email as usuario_email
                  FROM auditoria_log al
                  JOIN usuario u ON al.id_usuario = u.id_usuario
                  " . $where . "
                  ORDER BY al.creado_en DESC
                  LIMIT 100";

        $stmt = $this->conn->prepare($query);

        if ($fecha_inicio && $fecha_fin) {
            $stmt->bindParam(":fecha_inicio", $fecha_inicio);
            $stmt->bindParam(":fecha_fin", $fecha_fin);
        }

        if ($id_usuario) {
            $stmt->bindParam(":id_usuario", $id_usuario);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>