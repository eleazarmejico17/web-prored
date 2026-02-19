<?php
require_once __DIR__ . '/BaseModel.php';

class VentasModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct("ventas"); // Tabla dummy, realmente usaremos queries complejos
    }

    public function getDashboardStats()
    {
        $stats = [];

        // 1. Crecimiento de Red (Nuevos servicios este mes)
        $sql = "SELECT COUNT(*) as total FROM servicio 
                WHERE MONTH(fecha_instalacion) = MONTH(CURRENT_DATE()) 
                AND YEAR(fecha_instalacion) = YEAR(CURRENT_DATE())
                AND estado = 'ACTIVO'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stats['crecimiento'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 2. Pagos por Validar (Reportados no validados)
        $sql = "SELECT COUNT(*) as total FROM pagos WHERE estado = 'PENDIENTE'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stats['pagos_por_validar'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 3. Cartera en Mora (Total deuda vencida)
        $sql = "SELECT COALESCE(SUM(total), 0) as total FROM deuda WHERE estado IN ('PENDIENTE', 'PARCIAL')"; // Asumiendo que toda deuda pendiente es cartera
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stats['cartera_mora'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 4. Suspendidos y Cortes
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN estado = 'SUSPENDIDO' THEN 1 ELSE 0 END), 0) as suspendidos,
                    COALESCE(SUM(CASE WHEN estado = 'CORTADO' THEN 1 ELSE 0 END), 0) as cortados
                FROM servicio";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $counts = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['suspendidos'] = $counts['suspendidos'];
        $stats['cortados'] = $counts['cortados'];

        return $stats;
    }

    public function getNewSalesChart()
    {
        // Últimas 4 semanas (simplificado a últimos 4 meses o semanas según requerimiento, haremos semanas del mes actual)
        // Por simplicidad, devolveremos últimos 6 meses
        $sql = "SELECT 
                    DATE_FORMAT(fecha_instalacion, '%b') as mes,
                    COUNT(*) as total
                FROM servicio
                WHERE fecha_instalacion >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY YEAR(fecha_instalacion), MONTH(fecha_instalacion)
                ORDER BY fecha_instalacion ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiceDirectory($filtros = [])
    {
        $sql = "SELECT 
                    s.id_servicio,
                    s.estado,
                    c.id_cliente,
                    COALESCE(c.razon_social, CONCAT(c.nombres, ' ', c.apellidos)) as nombre_cliente,
                    c.dni,
                    p.nombre as nombre_plan,
                    p.velocidad_bajada,
                    p.precio as precio_plan,
                    s.direccion,
                    s.ip_asignada,
                    w.nombre as winbox,
                    (SELECT COALESCE(SUM(total), 0) FROM deuda d WHERE d.id_servicio = s.id_servicio AND d.estado != 'PAGADO') as deuda_total
                FROM servicio s
                JOIN cliente c ON s.id_cliente = c.id_cliente
                JOIN plan p ON s.id_plan = p.id_plan
                LEFT JOIN winbox w ON s.id_winbox = w.id_winbox
                WHERE 1=1";

        $params = [];

        if (!empty($filtros['search'])) {
            $term = "%" . $filtros['search'] . "%";
            $sql .= " AND (c.nombres LIKE :term OR c.apellidos LIKE :term OR c.razon_social LIKE :term OR c.dni LIKE :term OR s.ip_asignada LIKE :term)";
            $params[':term'] = $term;
        }

        if (!empty($filtros['estado'])) {
            $sql .= " AND s.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        $sql .= " ORDER BY c.nombres ASC LIMIT 20"; // Limit para no saturar

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientStats()
    {
        $stats = [];

        // 1. Clientes Nuevos (Mes Actual - Basado en fecha_instalacion del primer servicio)
        // Simplificado: Clientes con servicio instalado este mes
        $sql = "SELECT COUNT(DISTINCT id_cliente) as total FROM servicio 
                WHERE MONTH(fecha_instalacion) = MONTH(CURRENT_DATE()) 
                AND YEAR(fecha_instalacion) = YEAR(CURRENT_DATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stats['nuevos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 2. Acceso Habilitado (Usuarios activos asociados a clientes)
        $sql = "SELECT COUNT(DISTINCT id_cliente) as total FROM usuario WHERE id_cliente IS NOT NULL AND activo = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stats['con_acceso'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 3. Sin Acceso (Clientes activos sin usuario activo)
        $sql = "SELECT COUNT(*) as total FROM cliente c 
                WHERE c.activo = 1 
                AND NOT EXISTS (SELECT 1 FROM usuario u WHERE u.id_cliente = c.id_cliente AND u.activo = 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stats['sin_acceso'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        return $stats;
    }

    public function getClientDirectory($filtros = [])
    {
        $sql = "SELECT 
                    c.id_cliente,
                    c.dni,
                    c.nombres,
                    c.apellidos,
                    c.razon_social,
                    (SELECT numero FROM cliente_telefono WHERE id_cliente = c.id_cliente AND principal = 1 LIMIT 1) as telefono,
                    (SELECT p.nombre FROM servicio s JOIN plan p ON s.id_plan = p.id_plan WHERE s.id_cliente = c.id_cliente AND s.estado = 'ACTIVO' LIMIT 1) as plan,
                    (SELECT COUNT(*) FROM usuario u WHERE u.id_cliente = c.id_cliente AND u.activo = 1) as has_access
                FROM cliente c
                WHERE c.activo = 1";

        $params = [];

        if (!empty($filtros['search'])) {
            $term = "%" . $filtros['search'] . "%";
            $sql .= " AND (c.nombres LIKE :term OR c.apellidos LIKE :term OR c.razon_social LIKE :term OR c.dni LIKE :term)";
            $params[':term'] = $term;
        }

        $sql .= " ORDER BY c.nombres ASC LIMIT 50";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarPagoManual($datos)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Automatic Debt Generation if id_deuda is missing
            if (empty($datos['id_deuda'])) {
                if (empty($datos['id_servicio'])) {
                    throw new Exception("Se requiere ID de servicio para generar deuda automática.");
                }

                // Calculate amount based on plan + charges
                $details = $this->getNextPaymentDetails($datos['id_servicio']);
                if (!$details)
                    throw new Exception("Error al calcular monto del servicio.");

                $monto_esperado = $details['total'];

                // Get current/next period
                // Simplification: Get the current active period or the next one
                $sqlPeriodo = "SELECT id_periodo FROM periodos WHERE fecha_inicio <= CURDATE() AND fecha_fin >= CURDATE() LIMIT 1";
                $stmtP = $this->conn->prepare($sqlPeriodo);
                $stmtP->execute();
                $periodo = $stmtP->fetch(PDO::FETCH_ASSOC);

                if (!$periodo) {
                    // Fallback: Get the latest period or create one? 
                    // For now, let's grab the very last period in DB to be safe
                    $sqlPeriodo = "SELECT id_periodo FROM periodos ORDER BY id_periodo DESC LIMIT 1";
                    $stmtP = $this->conn->prepare($sqlPeriodo);
                    $stmtP->execute();
                    $periodo = $stmtP->fetch(PDO::FETCH_ASSOC);
                }

                $id_periodo = $periodo['id_periodo'];

                // Create Deuda
                $sqlDeuda = "INSERT INTO deuda (id_servicio, id_periodo, monto_base, mora, total, estado, fecha_vencimiento) 
                            VALUES (:id_servicio, :id_periodo, :monto_base, 0, :total, 'PENDIENTE', DATE_ADD(CURDATE(), INTERVAL 1 MONTH))"; // Dummy fecha_venc

                // Note: The schema for deuda might not have fecha_vencimiento directly if it's joined? 
                // Wait, in previous step I SAW 'fecha_vencimiento' was missing from DEUDA table and was in PERIODOS.
                // Checking insert.sql... 
                // INSERT INTO deuda (id_servicio, id_periodo, monto_base, mora, total, estado) ...
                // Correct, no fecha_vencimiento in deuda table.

                $sqlDeuda = "INSERT INTO deuda (id_servicio, id_periodo, monto_base, mora, total, estado) 
                            VALUES (:id_servicio, :id_periodo, :monto_base, 0, :total, 'PENDIENTE')";

                $stmtD = $this->conn->prepare($sqlDeuda);
                $stmtD->execute([
                    ':id_servicio' => $datos['id_servicio'],
                    ':id_periodo' => $id_periodo,
                    ':monto_base' => $details['plan_precio'],
                    ':total' => $monto_esperado
                ]);

                $datos['id_deuda'] = $this->conn->lastInsertId();

                // Mark charges as applied/processed?
                // Ideally we link them or mark them. For now, we assume they are paid with this debt.
                // We could update 'cargo_adicional' status here.
                if (!empty($details['cargos'])) {
                    // Logic to mark cargos as assigned to this debt/payment or simply 'APLICADO'
                    // For MVP, we leave them or update status if we had IDs.
                    // Let's simple update all PENDING cargos for this service to APLICADO
                    $sqlUpdCargos = "UPDATE cargo_adicional SET estado = 'APLICADO', descripcion = CONCAT(descripcion, ' (Pagado en deuda ', :id_deuda, ')') 
                                    WHERE id_servicio = :id_servicio AND estado = 'PENDIENTE'";
                    $stmtUpdC = $this->conn->prepare($sqlUpdCargos);
                    $stmtUpdC->execute([':id_deuda' => $datos['id_deuda'], ':id_servicio' => $datos['id_servicio']]);
                }
            }

            // 2. Register Payment
            $sql = "INSERT INTO pagos (id_deuda, id_metodo_pago, id_usuario, monto, numero_operacion, banco, estado, fecha_pago, referencia) 
                    VALUES (:id_deuda, :id_metodo_pago, :id_usuario, :monto, :numero_operacion, :banco, 'VALIDADO', NOW(), :referencia)";

            $referencia = "QCK-" . time();
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_deuda' => $datos['id_deuda'],
                ':id_metodo_pago' => $datos['id_metodo_pago'],
                ':id_usuario' => $datos['id_usuario'],
                ':monto' => $datos['monto'],
                ':numero_operacion' => $datos['numero_operacion'] ?? NULL,
                ':banco' => $datos['banco'] ?? NULL,
                ':referencia' => $referencia
            ]);

            $id_pago = $this->conn->lastInsertId();

            // 3. Update Debt Status
            // Check if fully paid
            $sqlCheck = "SELECT total, (SELECT COALESCE(SUM(monto),0) FROM pagos WHERE id_deuda = :id_deuda) as pagado FROM deuda WHERE id_deuda = :id_deuda";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->execute([':id_deuda' => $datos['id_deuda']]);
            $debtState = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // Logic: if pagado >= total -> PAGADO, else PARCIAL
            // But we simplify to PAGADO as requested "cancel instante"

            $sqlDeuda = "UPDATE deuda SET estado = 'PAGADO' WHERE id_deuda = :id_deuda";
            if ($debtState['pagado'] < $debtState['total']) {
                $sqlDeuda = "UPDATE deuda SET estado = 'PARCIAL' WHERE id_deuda = :id_deuda";
                // If the user wants "cancela instante", we assume full payment is enforced by frontend
            }

            // Override: If amount matches expected, set PAGADO hard.
            $stmtDeuda = $this->conn->prepare($sqlDeuda);
            $stmtDeuda->execute([':id_deuda' => $datos['id_deuda']]);

            $this->conn->commit();
            return ['success' => true, 'id_pago' => $id_pago];

        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getNextPaymentDetails($id_servicio)
    {
        // 1. Get Plan Price
        $sqlPlan = "SELECT p.precio, p.nombre FROM servicio s JOIN plan p ON s.id_plan = p.id_plan WHERE s.id_servicio = :id";
        $stmt = $this->conn->prepare($sqlPlan);
        $stmt->execute([':id' => $id_servicio]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$plan)
            return null;

        // 2. Get Pending Charges
        $sqlCargos = "SELECT id_cargo, concepto, monto FROM cargo_adicional WHERE id_servicio = :id AND estado = 'PENDIENTE'";
        $stmtC = $this->conn->prepare($sqlCargos);
        $stmtC->execute([':id' => $id_servicio]);
        $cargos = $stmtC->fetchAll(PDO::FETCH_ASSOC);

        $totalCargos = 0;
        foreach ($cargos as $c)
            $totalCargos += $c['monto'];

        return [
            'plan_precio' => $plan['precio'],
            'plan_nombre' => $plan['nombre'],
            'total_cargos' => $totalCargos,
            'cargos' => $cargos,
            'total' => $plan['precio'] + $totalCargos
        ];
    }


    public function getMaterialesPendientes()
    {
        return []; // Placeholder
    }

    public function registrarContactoCliente($datos)
    {
        return true; // Placeholder
    }

    public function getHistorialContactos($clienteId)
    {
        return []; // Placeholder
    }

    public function getPlans()
    {
        $sql = "SELECT * FROM plan ORDER BY precio ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInternetOptions()
    {
        $sql = "SELECT id_internet, velocidad, precio FROM internet ORDER BY precio ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTvOptions()
    {
        $sql = "SELECT id_tv, nombre, precio FROM tv ORDER BY precio ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPlan($data)
    {
        $sql = "INSERT INTO plan (id_internet, id_tv, nombre, velocidad_bajada, velocidad_subida, precio, estado) 
                VALUES (:id_internet, :id_tv, :nombre, :velocidad_bajada, :velocidad_subida, :precio, 'ACTIVO')";

        $stmt = $this->conn->prepare($sql);
        $res = $stmt->execute([
            ':id_internet' => !empty($data['id_internet']) ? $data['id_internet'] : null,
            ':id_tv' => !empty($data['id_tv']) ? $data['id_tv'] : null,
            ':nombre' => $data['nombre'],
            ':velocidad_bajada' => $data['velocidad_bajada'],
            ':velocidad_subida' => $data['velocidad_subida'] ?? $data['velocidad_bajada'],
            ':precio' => $data['precio']
        ]);

        return $res ? $this->conn->lastInsertId() : false;
    }

    public function getServiceById($id)
    {
        $sql = "SELECT 
                    s.*,
                    c.nombres, c.apellidos, c.razon_social, c.dni,
                    p.nombre as nombre_plan, p.precio as precio_plan, p.velocidad_bajada,
                    (SELECT numero FROM cliente_telefono WHERE id_cliente = c.id_cliente AND principal = 1 LIMIT 1) as telefono
                FROM servicio s
                JOIN cliente c ON s.id_cliente = c.id_cliente
                LEFT JOIN plan p ON s.id_plan = p.id_plan
                WHERE s.id_servicio = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateService($id, $data)
    {
        // Campos permitidos para actualizar
        // id_plan, direccion, ip_asignada, precio_personalizado (si aplica)
        // Por ahora actualizaremos plan e IP que son los que se muestran en el modal

        $sql = "UPDATE servicio SET 
                    id_plan = :id_plan, 
                    ip_asignada = :ip,
                    direccion = :direccion
                WHERE id_servicio = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_plan' => $data['id_plan'],
            ':ip' => $data['ip'],
            ':direccion' => $data['direccion'], // Asegurarse de pasar la dirección, o modificar query
            ':id' => $id
        ]);

        // NOTA: Si se quisiera soportar precios personalizados, habría que alterar la tabla servicio
        // para tener 'precio_custom' o similar. Por ahora nos ceñimos a lo básico.
    }
    public function getClientFullDetails($id)
    {
        $sql = "SELECT c.*, 
                (SELECT numero FROM cliente_telefono WHERE id_cliente = c.id_cliente AND principal = 1 LIMIT 1) as telefono
                FROM cliente c WHERE c.id_cliente = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createClient($data)
    {
        // Basic validation: DNI/RUC is required
        if (empty($data['dni']))
            return false;

        $sql = "INSERT INTO cliente (dni, nombres, apellidos, razon_social, email, ubigeo, activo, creado_en) 
                VALUES (:dni, :nombres, :apellidos, :razon_social, :email, :ubigeo, 1, NOW())";

        $stmt = $this->conn->prepare($sql);
        $res = $stmt->execute([
            ':dni' => $data['dni'],
            ':nombres' => $data['nombres'] ?? null,
            ':apellidos' => $data['apellidos'] ?? null,
            ':razon_social' => $data['razon_social'] ?? null,
            ':email' => $data['email'] ?? null,
            ':ubigeo' => $data['ubigeo'] ?? '150101' // Default Lima
        ]);

        if ($res) {
            $id = $this->conn->lastInsertId();
            // Insert phone if provided
            if (!empty($data['telefono'])) {
                $sqlTel = "INSERT INTO cliente_telefono (id_cliente, numero, tipo, principal, activo) VALUES (:id, :num, 'MOVIL', 1, 1)";
                $stmtTel = $this->conn->prepare($sqlTel);
                $stmtTel->execute([':id' => $id, ':num' => $data['telefono']]);
            }
            return $id;
        }
        return false;
    }

    public function updateClient($id, $data)
    {
        $sql = "UPDATE cliente SET 
                    dni = :dni, 
                    nombres = :nombres, 
                    apellidos = :apellidos, 
                    razon_social = :razon_social, 
                    email = :email
                WHERE id_cliente = :id";

        $stmt = $this->conn->prepare($sql);
        $res = $stmt->execute([
            ':dni' => $data['dni'],
            ':nombres' => $data['nombres'] ?? null,
            ':apellidos' => $data['apellidos'] ?? null,
            ':razon_social' => $data['razon_social'] ?? null,
            ':email' => $data['email'] ?? null,
            ':id' => $id
        ]);

        // Update phone (simplification: update the principal phone or insert if not exists)
        if ($res && !empty($data['telefono'])) {
            // Check if exists
            $check = $this->conn->prepare("SELECT id_telefono FROM cliente_telefono WHERE id_cliente = :id AND principal = 1");
            $check->execute([':id' => $id]);
            if ($check->rowCount() > 0) {
                $upd = $this->conn->prepare("UPDATE cliente_telefono SET numero = :num WHERE id_cliente = :id AND principal = 1");
                $upd->execute([':id' => $id, ':num' => $data['telefono']]);
            } else {
                $ins = $this->conn->prepare("INSERT INTO cliente_telefono (id_cliente, numero, tipo, principal, activo) VALUES (:id, :num, 'MOVIL', 1, 1)");
                $ins->execute([':id' => $id, ':num' => $data['telefono']]);
            }
        }

        return $res;
    }
    public function searchClientForPayment($term)
    {
        $sql = "SELECT 
                    c.id_cliente,
                    c.dni, 
                    COALESCE(c.razon_social, CONCAT(c.nombres, ' ', c.apellidos)) as nombre_completo,
                    s.id_servicio,
                    s.estado as estado_servicio,
                    s.direccion,
                    p.nombre as nombre_plan,
                    p.precio as precio_plan,
                    (SELECT COALESCE(SUM(total), 0) FROM deuda d WHERE d.id_servicio = s.id_servicio AND d.estado != 'PAGADO') as deuda_total,
                    (SELECT COUNT(*) FROM deuda d WHERE d.id_servicio = s.id_servicio AND d.estado != 'PAGADO') as meses_deuda,
                    (SELECT id_deuda FROM deuda d WHERE d.id_servicio = s.id_servicio AND d.estado != 'PAGADO' ORDER BY id_periodo ASC LIMIT 1) as id_deuda_antigua
                FROM cliente c
                JOIN servicio s ON c.id_cliente = s.id_cliente
                JOIN plan p ON s.id_plan = p.id_plan
                WHERE c.dni LIKE :term OR c.nombres LIKE :term OR c.razon_social LIKE :term
                LIMIT 5";

        $stmt = $this->conn->prepare($sql);
        $term = "%$term%";
        $stmt->execute([':term' => $term]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaymentMethods()
    {
        // Hardcoded for now, or fetch from table if exists
        return [
            ['id' => 1, 'nombre' => 'Efectivo'],
            ['id' => 2, 'nombre' => 'Transferencia'],
            ['id' => 3, 'nombre' => 'Yape / Plin'],
            ['id' => 4, 'nombre' => 'Tarjeta']
        ];
    }

    public function getReceiptData($id_pago)
    {
        $sql = "SELECT 
                    pg.id_pago,
                    pg.monto,
                    pg.fecha_pago,
                    pg.numero_operacion,
                    pg.referencia,
                    c.dni,
                    COALESCE(c.razon_social, CONCAT(c.nombres, ' ', c.apellidos)) as nombre_cliente,
                    s.direccion,
                    p.nombre as nombre_plan
                FROM pagos pg
                LEFT JOIN deuda d ON pg.id_deuda = d.id_deuda
                LEFT JOIN servicio s ON d.id_servicio = s.id_servicio -- Link via deuda
                LEFT JOIN cliente c ON s.id_cliente = c.id_cliente
                LEFT JOIN plan p ON s.id_plan = p.id_plan
                WHERE pg.id_pago = :id";

        // Note: The original registrarPagoManual inserts id_deuda. 
        // If we want to support advance payments without specific debt ID, logic needs adjustment.
        // For now assuming payment is linked to a debt or we modify registrarPagoManual.

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_pago]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getServiceDebtHistory($id_servicio)
    {
        $sql = "SELECT 
                    d.id_deuda,
                    CONCAT(per.mes, '/', per.anio) as periodo,
                    d.total,
                    d.estado,
                    per.fecha_fin as fecha_vencimiento
                FROM deuda d
                JOIN periodos per ON d.id_periodo = per.id_periodo
                WHERE d.id_servicio = :id
                ORDER BY d.id_periodo DESC
                LIMIT 12";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_servicio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
