USE prored;

-- =====================================================
-- VIEW 1: SERVICIOS COMPLETOS DEL CLIENTE
-- RF-U01 Visualizar Servicios Contratados
-- =====================================================
CREATE OR REPLACE VIEW vw_cliente_servicios AS
SELECT 
    s.id_servicio,
    c.id_cliente,
    CONCAT(c.nombres,' ',c.apellidos) AS cliente,
    p.nombre AS plan,
    p.velocidad_bajada,
    p.velocidad_subida,
    p.dispositivos_incluidos,
    s.direccion,
    s.ip_asignada,
    s.estado,
    p.precio AS costo_mensual,
    s.fecha_instalacion,
    s.fecha_corte
FROM servicio s
JOIN cliente c ON s.id_cliente = c.id_cliente
JOIN plan p ON s.id_plan = p.id_plan;



-- =====================================================
-- VIEW 2: ESTADO DE CUENTA DETALLADO
-- RF-U02 Consultar Estado de Cuenta
-- =====================================================
CREATE OR REPLACE VIEW vw_estado_cuenta AS
SELECT 
    d.id_deuda,
    s.id_servicio,
    c.id_cliente,
    CONCAT(c.nombres,' ',c.apellidos) AS cliente,
    per.mes,
    per.anio,
    d.monto_base,
    IFNULL(SUM(ca.monto),0) AS cargos_adicionales,
    d.mora,
    (d.monto_base + IFNULL(SUM(ca.monto),0) + d.mora) AS total_calculado,
    d.estado
FROM deuda d
JOIN servicio s ON d.id_servicio = s.id_servicio
JOIN cliente c ON s.id_cliente = c.id_cliente
JOIN periodos per ON d.id_periodo = per.id_periodo
LEFT JOIN cargo_adicional ca 
    ON ca.id_servicio = s.id_servicio 
    AND ca.id_periodo = per.id_periodo
GROUP BY d.id_deuda;



-- =====================================================
-- VIEW 3: PAGOS REPORTADOS (PARA VALIDACION)
-- RF-V01 Validar Pagos
-- =====================================================
CREATE OR REPLACE VIEW vw_pagos_pendientes AS
SELECT 
    p.id_pago,
    p.referencia,
    CONCAT(c.nombres,' ',c.apellidos) AS cliente,
    s.direccion,
    mp.nombre AS metodo_pago,
    p.monto,
    p.numero_operacion,
    p.banco,
    p.fecha_pago,
    p.estado
FROM pagos p
JOIN deuda d ON p.id_deuda = d.id_deuda
JOIN servicio s ON d.id_servicio = s.id_servicio
JOIN cliente c ON s.id_cliente = c.id_cliente
JOIN metodo_pago mp ON p.id_metodo_pago = mp.id_metodo_pago
WHERE p.estado = 'PENDIENTE';



-- =====================================================
-- VIEW 4: SERVICIOS EN MORA (DASHBOARD VENTAS)
-- RF-V06 Alertas de Morosidad
-- =====================================================
CREATE OR REPLACE VIEW vw_servicios_mora AS
SELECT 
    s.id_servicio,
    CONCAT(c.nombres,' ',c.apellidos) AS cliente,
    s.direccion,
    SUM(d.total) AS total_deuda,
    MAX(per.fecha_fin) AS ultimo_vencimiento,
    DATEDIFF(CURDATE(), MAX(per.fecha_fin)) AS dias_mora
FROM deuda d
JOIN servicio s ON d.id_servicio = s.id_servicio
JOIN cliente c ON s.id_cliente = c.id_cliente
JOIN periodos per ON d.id_periodo = per.id_periodo
WHERE d.estado = 'PENDIENTE'
GROUP BY s.id_servicio
HAVING dias_mora > 0;



-- =====================================================
-- VIEW 5: LISTA DE TICKETS CON INFO COMPLETA
-- RF-S01 Gestionar Tickets
-- =====================================================
CREATE OR REPLACE VIEW vw_tickets_completo AS
SELECT 
    t.id_ticket,
    CONCAT(c.nombres,' ',c.apellidos) AS cliente,
    s.direccion,
    t.tipo_problema,
    t.urgencia,
    t.estado,
    t.creado_en,
    TIMESTAMPDIFF(HOUR, t.creado_en, NOW()) AS horas_transcurridas
FROM ticket t
JOIN cliente c ON t.id_cliente = c.id_cliente
JOIN servicio s ON t.id_servicio = s.id_servicio;



-- =====================================================
-- VIEW 6: VISITAS TECNICAS CON DETALLE
-- RF-T01 Agenda Técnico
-- =====================================================
CREATE OR REPLACE VIEW vw_visitas_tecnicas AS
SELECT 
    v.id_visita,
    v.estado,
    v.fecha_programada,
    CONCAT(c.nombres,' ',c.apellidos) AS cliente,
    s.direccion,
    u.nombre AS tecnico
FROM visita_tecnica v
JOIN ticket t ON v.id_ticket = t.id_ticket
JOIN cliente c ON t.id_cliente = c.id_cliente
JOIN servicio s ON t.id_servicio = s.id_servicio
JOIN usuario u ON v.id_tecnico = u.id_usuario;



-- =====================================================
-- VIEW 7: MATERIALES REPORTADOS PENDIENTES
-- Flujo materiales técnicos
-- =====================================================
CREATE OR REPLACE VIEW vw_materiales_pendientes AS
SELECT 
    v.id_visita,
    CONCAT(c.nombres,' ',c.apellidos) AS cliente,
    s.direccion,
    SUM(vm.total) AS total_materiales,
    v.estado
FROM visita_tecnica v
JOIN ticket t ON v.id_ticket = t.id_ticket
JOIN cliente c ON t.id_cliente = c.id_cliente
JOIN servicio s ON t.id_servicio = s.id_servicio
JOIN visita_material vm ON vm.id_visita = v.id_visita
WHERE v.estado = 'CONCLUIDA'
GROUP BY v.id_visita;



-- =====================================================
-- VIEW 8: REPORTE FINANCIERO MENSUAL
-- RF-A06 Reporte Financiero
-- =====================================================
CREATE OR REPLACE VIEW vw_reporte_financiero_mensual AS
SELECT 
    per.mes,
    per.anio,
    SUM(p.monto) AS ingresos_recibidos,
    COUNT(p.id_pago) AS cantidad_pagos
FROM pagos p
JOIN deuda d ON p.id_deuda = d.id_deuda
JOIN periodos per ON d.id_periodo = per.id_periodo
WHERE p.estado = 'VALIDADO'
GROUP BY per.anio, per.mes;



-- =====================================================
-- VIEW 9: PRODUCTIVIDAD DE EMPLEADOS
-- RF-A06 Reporte de Empleados
-- =====================================================
CREATE OR REPLACE VIEW vw_productividad_empleados AS
SELECT 
    u.id_usuario,
    u.nombre,
    COUNT(DISTINCT p.id_pago) AS pagos_validados,
    COUNT(DISTINCT t.id_ticket) AS tickets_atendidos,
    COUNT(DISTINCT v.id_visita) AS visitas_realizadas
FROM usuario u
LEFT JOIN pagos p ON p.id_usuario = u.id_usuario AND p.estado = 'VALIDADO'
LEFT JOIN ticket_mensaje tm ON tm.id_usuario = u.id_usuario
LEFT JOIN ticket t ON tm.id_ticket = t.id_ticket
LEFT JOIN visita_tecnica v ON v.id_tecnico = u.id_usuario
GROUP BY u.id_usuario;



-- =====================================================
-- VIEW 10: HISTORIAL DE PAGOS DEL CLIENTE
-- RF-U04 Historial de Pagos
-- =====================================================
CREATE OR REPLACE VIEW vw_historial_pagos_cliente AS
SELECT 
    p.id_pago,
    CONCAT(c.nombres,' ',c.apellidos) AS cliente,
    s.direccion,
    p.monto,
    mp.nombre AS metodo_pago,
    p.numero_operacion,
    p.estado,
    p.fecha_pago
FROM pagos p
JOIN deuda d ON p.id_deuda = d.id_deuda
JOIN servicio s ON d.id_servicio = s.id_servicio
JOIN cliente c ON s.id_cliente = c.id_cliente
JOIN metodo_pago mp ON p.id_metodo_pago = mp.id_metodo_pago;
