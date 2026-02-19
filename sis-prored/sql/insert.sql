-- =====================================================
-- INSERCIONES COMPLETAS - PRORED
-- CORREGIDO Y ORDENADO
-- =====================================================
USE prored;

-- =====================================================
-- 1. DISTRITOS (LIMA METROPOLITANA)
-- =====================================================
INSERT INTO distrito (nombre) VALUES
('Comas'), ('Independencia'), ('San Martín de Porres'), ('Rímac'), 
('Cercado de Lima'), ('Breña'), ('Pueblo Libre'), ('Magdalena'),
('Jesús María'), ('Lince'), ('San Miguel'), ('Bellavista'),
('Callao'), ('La Perla'), ('Ventanilla'), ('Carabayllo'),
('Puente Piedra'), ('Ancón'), ('Santa Rosa'), ('Chorrillos'),
('Barranco'), ('Surquillo'), ('Santiago de Surco'), ('Villa El Salvador'),
('Villa María del Triunfo'), ('San Juan de Miraflores'), ('Ate'),
('Santa Anita'), ('El Agustino'), ('San Luis');

-- =====================================================
-- 2. WINBOX (EQUIPOS DE RED)
-- =====================================================
INSERT INTO winbox (nombre) VALUES
('W-EDGE03'), ('W-EDGE04'), ('W-AP03'), ('W-AP04'), ('W-AP05'),
('W-CORE02'), ('W-CORE03'), ('W-DIST01'), ('W-DIST02'), ('W-BH01'),
('W-BH02'), ('W-CPE01'), ('W-CPE02'), ('W-CPE03'), ('W-CPE04');

-- =====================================================
-- 3. INTERNET (PLANES BASE)
-- =====================================================
INSERT INTO internet (velocidad, precio) VALUES
('20 Mbps', 39.90), ('30 Mbps', 49.90), ('80 Mbps', 69.90),
('150 Mbps', 89.90), ('250 Mbps', 119.90), ('300 Mbps', 129.90),
('400 Mbps', 139.90), ('800 Mbps', 199.90), ('1 Gbps', 249.90);

-- =====================================================
-- 4. TV (PAQUETES)
-- =====================================================
INSERT INTO tv (nombre, cantidad_canales, precio) VALUES
('TV Full HD', 100, 49.90), ('TV 4K', 150, 79.90),
('TV Latino', 85, 44.90), ('TV Noticias', 60, 34.90),
('TV Entretenimiento', 110, 54.90), ('TV Movies', 90, 49.90),
('TV Mega Pack', 200, 99.90), ('TV Deportes Plus', 95, 59.90),
('TV Infantil Plus', 55, 29.90);

-- =====================================================
-- 5. ROLES DEL SISTEMA
-- =====================================================
INSERT INTO rol (nombre, descripcion) VALUES
('admin', 'Administrador del sistema – acceso total'),
('soporte_tecnico', 'Soporte técnico – gestión de tickets y monitoreo'),
('tecnico_campo', 'Técnico de campo – visitas e instalaciones'),
('user', 'Cliente – acceso a portal de pagos y soporte'),
('ventas', 'Ventas – gestión de clientes y planes');

-- =====================================================
-- 6. MÉTODOS DE PAGO
-- =====================================================
INSERT INTO metodo_pago (nombre, requiere_comprobante, requiere_operacion, requiere_banco, activo) VALUES
('EFECTIVO', FALSE, FALSE, FALSE, TRUE),
('TARJETA', TRUE, TRUE, TRUE, TRUE),
('TRANSFERENCIA', TRUE, TRUE, TRUE, TRUE),
('YAPE', TRUE, TRUE, FALSE, TRUE),
('PLIN', TRUE, TRUE, FALSE, TRUE);

-- =====================================================
-- 7. PLANES (COMBOS Y SOLO INTERNET)
-- =====================================================
INSERT INTO plan (id_internet, id_tv, nombre, velocidad_subida, velocidad_bajada, 
                  dispositivos_incluidos, precio, precio_dispositivo_extra, descripcion, estado) VALUES
-- Solo internet
(1, NULL, 'Internet 50', '5 Mbps', '50 Mbps', 3, 39.90, 8.00, 'Plan básico económico 50 Mbps', 'ACTIVO'),
(2, NULL, 'Internet 100', '10 Mbps', '100 Mbps', 4, 49.90, 8.00, 'Plan ideal para hogares pequeños', 'ACTIVO'),
(3, NULL, 'Internet 200', '20 Mbps', '200 Mbps', 6, 69.90, 12.00, 'Internet de velocidad media', 'ACTIVO'),
(4, NULL, 'Internet 500', '50 Mbps', '500 Mbps', 8, 89.90, 15.00, 'Fibra óptica 500 Mbps', 'ACTIVO'),
(5, NULL, 'Internet 600', '60 Mbps', '600 Mbps', 10, 119.90, 18.00, 'Internet de alta velocidad', 'ACTIVO'),
-- Combos internet + TV
(2, 1, 'Combo 100 + TV Básico', '10 Mbps', '100 Mbps', 10, 99.90, 15.00, 'Internet 100 + TV Básico 50 canales', 'ACTIVO'),
(3, 2, 'Combo 200 + TV Premium', '20 Mbps', '200 Mbps', 12, 149.90, 20.00, 'Internet 200 + TV Premium 120 canales', 'ACTIVO'),
(4, 3, 'Combo 500 + TV Deportes', '50 Mbps', '500 Mbps', 15, 189.90, 25.00, 'Internet 500 + TV Deportes 80 canales', 'ACTIVO'),
(5, 4, 'Combo 600 + TV Infantil', '60 Mbps', '600 Mbps', 18, 199.90, 30.00, 'Internet 600 + TV Infantil 40 canales', 'ACTIVO'),
(1, 1, 'Combo 50 + TV Básico', '5 Mbps', '50 Mbps', 10, 79.90, 12.00, 'Internet 50 + TV Básico', 'ACTIVO'),
(2, 2, 'Combo 100 + TV Premium', '10 Mbps', '100 Mbps', 12, 129.90, 15.00, 'Internet 100 + TV Premium', 'ACTIVO'),
(3, 3, 'Combo 200 + TV Deportes', '20 Mbps', '200 Mbps', 15, 139.90, 20.00, 'Internet 200 + TV Deportes', 'ACTIVO'),
(4, 4, 'Combo 500 + TV Infantil', '50 Mbps', '500 Mbps', 15, 169.90, 25.00, 'Internet 500 + TV Infantil', 'ACTIVO'),
(5, 2, 'Combo 600 + TV Premium', '60 Mbps', '600 Mbps', 15, 239.90, 30.00, 'Internet 600 + TV Premium', 'ACTIVO'),
-- Planes inactivos (históricos)
(1, 1, 'Internet 50 (Antiguo)', '5 Mbps', '50 Mbps', 5, 59.90, 10.00, 'Plan descontinuado', 'INACTIVO'),
(2, NULL, 'Internet 100 (Antiguo)', '10 Mbps', '100 Mbps', 8, 79.90, 15.00, 'Plan descontinuado', 'INACTIVO');

-- =====================================================
-- 8. CLIENTES (PERSONAS Y EMPRESAS)
-- =====================================================
INSERT INTO cliente (dni, nombres, apellidos, razon_social, ubigeo, email, activo) VALUES
-- Personas naturales
('44444444', 'María Elena', 'Quispe Mamani', NULL, '150101', 'maria.quispe@email.com', TRUE),
('55555555', 'Juan Carlos', 'Pérez López', NULL, '150102', 'juan.perez@email.com', TRUE),
('66666666', 'Carmen Rosa', 'Flores Vega', NULL, '150103', 'carmen.flores@email.com', TRUE),
('77777777', 'Roberto', 'Sánchez Castillo', NULL, '150104', 'roberto.sanchez@email.com', TRUE),
('88888888', 'Patricia', 'Castro Mendoza', NULL, '150105', 'patricia.castro@email.com', TRUE),
('99999999', 'Fernando', 'Díaz García', NULL, '150106', 'fernando.diaz@email.com', TRUE),
('10101010', 'Luis Miguel', 'Torres Rojas', NULL, '150201', 'luis.torres@email.com', TRUE),
('11111112', 'Ana Lucía', 'Mendoza Vargas', NULL, '150202', 'ana.mendoza@email.com', TRUE),
('12121212', 'Diego Alonso', 'Ramos Chávez', NULL, '150203', 'diego.ramos@email.com', TRUE),
('13131313', 'Silvia', 'Cárdenas Paredes', NULL, '150301', 'silvia.cardenas@email.com', TRUE),
('14141414', 'Jorge Luis', 'Morales Zegarra', NULL, '150302', 'jorge.morales@email.com', TRUE),
('15151515', 'Verónica', 'Salazar Huamán', NULL, '150303', 'veronica.salazar@email.com', TRUE),
('16161616', 'Oscar', 'Fernández Alva', NULL, '150401', 'oscar.fernandez@email.com', TRUE),
('17171717', 'Mónica', 'Gonzáles Ruiz', NULL, '150402', 'monica.gonzales@email.com', TRUE),
('18181818', 'Rafael', 'Ortega Navarro', NULL, '150403', 'rafael.ortega@email.com', TRUE),
('19191919', 'Elena', 'Vásquez Puma', NULL, '150501', 'elena.vasquez@email.com', TRUE),
('20202020', 'Gustavo', 'Romero Acosta', NULL, '150502', 'gustavo.romero@email.com', TRUE),
('21212121', 'Claudia', 'Herrera Campos', NULL, '150601', 'claudia.herrera@email.com', TRUE),
('22222223', 'Pedro', 'Jiménez Cruz', NULL, '150602', 'pedro.jimenez@email.com', TRUE),
('23232323', 'Rosa María', 'Silva Delgado', NULL, '150701', 'rosa.silva@email.com', TRUE),
-- Empresas
(NULL, NULL, NULL, 'Restaurante El Marino SAC', '150801', 'admin@elmarino.pe', TRUE),
(NULL, NULL, NULL, 'Gimnasio Fitness Center EIRL', '150802', 'contacto@gimnasiope.com', TRUE),
(NULL, NULL, NULL, 'Clínica Dental Sonrisas', '150803', 'cita@dentalsonrisas.pe', TRUE),
(NULL, NULL, NULL, 'Estudio Jurídico Pérez & Asociados', '150804', 'abogados@perezasoc.com', TRUE),
(NULL, NULL, NULL, 'Bodega Don José', '150805', 'donjose@yahoo.com', TRUE),
(NULL, NULL, NULL, 'Ferretería El Constructor', '150806', 'ventas@ferreconstructor.pe', TRUE),
(NULL, NULL, NULL, 'Hotel Los Andes SAC', '150807', 'reservas@hotellosandes.com', TRUE),
(NULL, NULL, NULL, 'Colegio Privado Santa María', '150808', 'informes@santamaria.edu.pe', TRUE);

-- =====================================================
-- 9. USUARIOS DEL SISTEMA (EMPLEADOS)
-- =====================================================
INSERT INTO usuario (id_rol, id_cliente, nombre, email, password, activo) VALUES
-- Admin (rol 1)
(1, NULL, 'Administrador', 'admin@prored.pe', 'hash_de_contraseña', TRUE),
-- Soporte técnico (rol 2)
(2, NULL, 'Soporte Juan', 'juan.soporte@prored.pe', 'hash', TRUE),
(2, NULL, 'Soporte Ana', 'ana.soporte@prored.pe', 'hash', TRUE),
(2, NULL, 'Soporte Carlos', 'carlos.soporte@prored.pe', 'hash', TRUE),
-- Técnicos de campo (rol 3)
(3, NULL, 'Técnico Pedro', 'pedro.tecnico@prored.pe', 'hash', TRUE),
(3, NULL, 'Técnico Luis', 'luis.tecnico@prored.pe', 'hash', TRUE),
(3, NULL, 'Técnico Mario', 'mario.tecnico@prored.pe', 'hash', TRUE),
-- Ventas (rol 5)
(5, NULL, 'Ventas Sofia', 'sofia.ventas@prored.pe', 'hash', TRUE),
(5, NULL, 'Ventas Diego', 'diego.ventas@prored.pe', 'hash', TRUE);

-- =====================================================
-- 10. TELÉFONOS DE CLIENTES
-- =====================================================
INSERT INTO cliente_telefono (id_cliente, numero, tipo, principal, activo) VALUES
(1, '987654321', 'MOVIL', TRUE, TRUE),   -- María
(1, '987654322', 'WHATSAPP', FALSE, TRUE),
(2, '976543210', 'MOVIL', TRUE, TRUE),   -- Juan
(2, '976543211', 'FIJO', FALSE, TRUE),
(3, '965432109', 'MOVIL', TRUE, TRUE),   -- Carmen
(3, '965432108', 'WHATSAPP', FALSE, TRUE),
(4, '954321098', 'MOVIL', TRUE, TRUE),   -- Roberto
(5, '943210987', 'MOVIL', TRUE, TRUE),   -- Patricia
(5, '943210986', 'WHATSAPP', FALSE, TRUE),
(6, '932109876', 'MOVIL', TRUE, TRUE),   -- Fernando
(7, '921098765', 'MOVIL', TRUE, TRUE),   -- Luis
(7, '921098764', 'FIJO', FALSE, TRUE),
(8, '910987654', 'MOVIL', TRUE, TRUE),   -- Ana
(8, '910987653', 'WHATSAPP', FALSE, TRUE),
(9, '999876543', 'MOVIL', TRUE, TRUE),   -- Diego
(10, '988765432', 'MOVIL', TRUE, TRUE),  -- Silvia
(11, '977654321', 'MOVIL', TRUE, TRUE),  -- Jorge
(11, '977654320', 'WHATSAPP', FALSE, TRUE),
(12, '966543210', 'MOVIL', TRUE, TRUE),  -- Verónica
(13, '955432109', 'MOVIL', TRUE, TRUE),  -- Oscar
(14, '944321098', 'MOVIL', TRUE, TRUE),  -- Mónica
(14, '944321097', 'FIJO', FALSE, TRUE),
(15, '933210987', 'MOVIL', TRUE, TRUE),  -- Rafael
(16, '922109876', 'MOVIL', TRUE, TRUE),  -- Elena
(17, '911098765', 'MOVIL', TRUE, TRUE),  -- Gustavo
(17, '911098764', 'WHATSAPP', FALSE, TRUE),
(18, '900987654', 'MOVIL', TRUE, TRUE),  -- Claudia
(19, '999876543', 'MOVIL', TRUE, TRUE),  -- Pedro
(20, '988765432', 'MOVIL', TRUE, TRUE),  -- Rosa
(20, '988765431', 'WHATSAPP', FALSE, TRUE),
(21, '977654322', 'FIJO', TRUE, TRUE),   -- Restaurante
(21, '977654323', 'WHATSAPP', FALSE, TRUE),
(22, '966543211', 'FIJO', TRUE, TRUE),   -- Gimnasio
(22, '966543212', 'WHATSAPP', FALSE, TRUE),
(23, '955432110', 'FIJO', TRUE, TRUE),   -- Clínica Dental
(24, '944321099', 'FIJO', TRUE, TRUE),   -- Estudio Jurídico
(24, '944321100', 'WHATSAPP', FALSE, TRUE),
(25, '933210988', 'MOVIL', TRUE, TRUE),  -- Bodega
(25, '933210989', 'WHATSAPP', FALSE, TRUE),
(26, '922109877', 'FIJO', TRUE, TRUE),   -- Ferretería
(27, '911098766', 'FIJO', TRUE, TRUE),   -- Hotel
(27, '911098767', 'WHATSAPP', FALSE, TRUE),
(28, '900987655', 'FIJO', TRUE, TRUE);   -- Colegio

-- =====================================================
-- 11. SERVICIOS CONTRATADOS (IDs DE PLAN CORREGIDOS)
-- =====================================================
INSERT INTO servicio (id_cliente, id_plan, id_winbox, id_distrito, direccion, ip_asignada, estado, fecha_instalacion, fecha_corte) VALUES
(1, 5, 5, 3, 'Av. Primavera 234, Surco', '10.0.11.20', 'ACTIVO', '2025-01-05', NULL),
(1, 9, 6, 3, 'Av. Primavera 234, Dpto 302', '10.0.11.21', 'ACTIVO', '2025-01-05', NULL),
(2, 8, 7, 2, 'Calle Las Orquídeas 567, Miraflores', '10.0.12.30', 'ACTIVO', '2025-01-12', NULL),
(3, 3, 8, 1, 'Av. Salaverry 890, San Isidro', '10.0.13.40', 'SUSPENDIDO', '2024-11-20', '2025-03-15'),
(4, 11, 9, 4, 'Calle Los Fresnos 123, La Molina', '10.0.14.50', 'ACTIVO', '2025-02-01', NULL),
(5, 4, 10, 5, 'Av. Angamos 456, San Borja', '10.0.15.60', 'ACTIVO', '2025-01-28', NULL),
(6, 2, 11, 6, 'Av. Carlos Izaguirre 789, Los Olivos', '10.0.16.70', 'EN_MORA', '2024-12-10', '2025-03-10'),
(7, 8, 12, 7, 'Jr. Junín 321, Comas', '10.0.17.80', 'ACTIVO', '2025-02-15', NULL),   -- antes 17 -> 8
(8, 12, 13, 8, 'Av. Túpac Amaru 654, Independencia', '10.0.18.90', 'ACTIVO', '2025-01-20', NULL),
(9, 13, 14, 9, 'Calle 2, Mz B Lt 15, SMP', '10.0.19.100', 'ACTIVO', '2025-02-10', NULL),
(10, 1, 15, 10, 'Jr. Trujillo 987, Rímac', '10.0.20.110', 'ACTIVO', '2025-01-18', NULL),
(11, 6, 1, 11, 'Av. Bolivia 147, Cercado', '10.0.21.120', 'CORTADO', '2024-10-05', '2025-02-28'),
(12, 14, 2, 12, 'Av. Brasil 258, Breña', '10.0.22.130', 'ACTIVO', '2025-02-20', NULL),
(13, 7, 3, 13, 'Av. Universitaria 369, Pueblo Libre', '10.0.23.140', 'ACTIVO', '2025-01-25', NULL),
(14, 1, 4, 14, 'Jr. Caracas 741, Magdalena', '10.0.24.150', 'ACTIVO', '2025-02-05', NULL),   -- antes 15 -> 1
(15, 2, 5, 15, 'Av. Arequipa 852, Jesús María', '10.0.25.160', 'EN_MORA', '2024-12-15', '2025-03-05'), -- antes 16 -> 2
(16, 10, 6, 16, 'Calle Bartolomé Herrera 963, Lince', '10.0.26.170', 'ACTIVO', '2025-02-12', NULL), -- antes 18 -> 10
(17, 10, 7, 17, 'Av. La Marina 159, San Miguel', '10.0.27.180', 'ACTIVO', '2025-01-08', NULL),
(18, 11, 8, 18, 'Av. Sáenz Peña 357, Bellavista', '10.0.28.190', 'ACTIVO', '2025-02-18', NULL), -- antes 19 -> 11
(19, 12, 9, 19, 'Av. Buenos Aires 468, Callao', '10.0.29.200', 'ACTIVO', '2025-01-30', NULL), -- antes 20 -> 12
(20, 5, 10, 20, 'Jr. Moquegua 579, La Perla', '10.0.30.210', 'ACTIVO', '2025-02-22', NULL),
(21, 6, 11, 21, 'Av. Bolognesi 681, Ventanilla', '10.0.31.220', 'ACTIVO', '2024-11-15', NULL), -- antes 21 -> 6
(22, 7, 12, 22, 'Av. San Juan 792, Carabayllo', '10.0.32.230', 'ACTIVO', '2024-12-01', NULL), -- antes 22 -> 7
(23, 8, 13, 23, 'Av. Canta Callao 903, Puente Piedra', '10.0.33.240', 'ACTIVO', '2025-01-10', NULL), -- antes 23 -> 8
(24, 9, 14, 24, 'Av. Panamericana Norte 124, Ancón', '10.0.34.250', 'ACTIVO', '2025-02-03', NULL), -- antes 24 -> 9
(25, 2, 15, 25, 'Mz C Lt 8, Santa Rosa', '10.0.35.260', 'ACTIVO', '2025-01-15', NULL),
(26, 3, 1, 26, 'Av. Prolongación Iquitos 235, Chorrillos', '10.0.36.270', 'ACTIVO', '2024-10-20', NULL),
(27, 11, 2, 27, 'Av. Pedro de Osma 346, Barranco', '10.0.37.280', 'ACTIVO', '2024-11-25', NULL),
(28, 13, 3, 28, 'Av. San Luis 457, Surquillo', '10.0.38.290', 'ACTIVO', '2025-02-25', NULL);

-- =====================================================
-- 12. PERIODOS (2024-2025)
-- =====================================================
INSERT INTO periodos (mes, anio, fecha_inicio, fecha_fin) VALUES
(9, 2024, '2024-09-01', '2024-09-30'),
(10, 2024, '2024-10-01', '2024-10-31'),
(11, 2024, '2024-11-01', '2024-11-30'),
(12, 2024, '2024-12-01', '2024-12-31'),
(1, 2025, '2025-01-01', '2025-01-31'),
(2, 2025, '2025-02-01', '2025-02-28'),
(3, 2025, '2025-03-01', '2025-03-31'),
(4, 2025, '2025-04-01', '2025-04-30'),
(5, 2025, '2025-05-01', '2025-05-31');

-- =====================================================
-- 13. DEUDA MASIVA (CORREGIDA)
-- =====================================================
INSERT INTO deuda (id_servicio, id_periodo, monto_base, mora, total, estado) VALUES
-- Servicio 1 (María, servicio 5? cuidado: los id_servicio se generan en orden desde 1)
-- Para evitar confusiones, asumimos que los servicios se insertaron en el orden anterior y obtuvieron IDs correlativos.
-- Usamos los números de servicio según el orden del INSERT (primer servicio = id 1, etc.)
-- Servicio 1 (Cliente 1 - María, plan 5) - PAGADO
(1, 6, 89.90, 0.00, 89.90, 'PAGADO'),   -- periodo feb 2025 (id=6)
-- Servicio 2 (Cliente 1 - María, plan 9) - PAGADO
(2, 6, 119.90, 0.00, 119.90, 'PAGADO'),
-- Servicio 3 (Cliente 2 - Juan) - PENDIENTE
(3, 7, 69.90, 0.00, 69.90, 'PENDIENTE'), -- periodo mar 2025 (id=7)
-- Servicio 4 (Cliente 3 - Carmen) - VENCIDA (MORA)
(4, 6, 149.90, 29.90, 179.80, 'PENDIENTE'),
(4, 7, 149.90, 0.00, 149.90, 'PENDIENTE'),
-- Servicio 5 (Cliente 4 - Roberto) - PAGADO
(5, 7, 129.90, 0.00, 129.90, 'PAGADO'),
-- Servicio 6 (Cliente 5 - Patricia) - PARCIAL
(6, 7, 149.90, 0.00, 149.90, 'PARCIAL'),
-- Servicio 7 (Cliente 6 - Fernando) - EN MORA
(7, 6, 99.90, 19.90, 119.80, 'PENDIENTE'),
(7, 7, 99.90, 0.00, 99.90, 'PENDIENTE'),
-- Servicio 8 (Cliente 7 - Luis) - PAGADO
(8, 7, 189.90, 0.00, 189.90, 'PAGADO'),
-- Servicio 9 (Cliente 8 - Ana) - PENDIENTE
(9, 7, 129.90, 0.00, 129.90, 'PENDIENTE'),
-- Servicio 10 (Cliente 9 - Diego) - PAGADO
(10, 7, 139.90, 0.00, 139.90, 'PAGADO'),
-- Servicio 11 (Cliente 10 - Silvia) - PENDIENTE
(11, 7, 59.90, 0.00, 59.90, 'PENDIENTE'),
-- Servicio 12 (Cliente 11 - Jorge) - CORTADO (DEUDA GRANDE)
(12, 3, 149.90, 45.90, 195.80, 'PENDIENTE'), -- nov 2024
(12, 4, 149.90, 0.00, 149.90, 'PENDIENTE'), -- dic 2024
(12, 5, 149.90, 30.90, 180.80, 'PENDIENTE'), -- ene 2025
(12, 6, 149.90, 15.90, 165.80, 'PENDIENTE'), -- feb 2025
-- Servicio 13 (Cliente 12 - Verónica) - PAGADO
(13, 7, 199.90, 0.00, 199.90, 'PAGADO'),
-- Servicio 14 (Cliente 13 - Oscar) - PAGADO
(14, 7, 119.90, 0.00, 119.90, 'PAGADO'),
-- Servicio 15 (Cliente 14 - Mónica) - PENDIENTE
(15, 7, 39.90, 0.00, 39.90, 'PENDIENTE'), -- plan 1 (Internet 50)
-- Servicio 16 (Cliente 15 - Rafael) - EN MORA
(16, 6, 49.90, 25.90, 75.80, 'PENDIENTE'), -- plan 2 (Internet 100)
(16, 7, 49.90, 0.00, 49.90, 'PENDIENTE'),
-- Servicio 17 (Cliente 16 - Elena) - PAGADO
(17, 7, 79.90, 0.00, 79.90, 'PAGADO'), -- plan 10
-- Servicio 18 (Cliente 17 - Gustavo) - PENDIENTE
(18, 7, 79.90, 0.00, 79.90, 'PENDIENTE'), -- plan 10
-- Servicio 19 (Cliente 18 - Claudia) - PAGADO
(19, 7, 129.90, 0.00, 129.90, 'PAGADO'), -- plan 11
-- Servicio 20 (Cliente 19 - Pedro) - PENDIENTE
(20, 7, 139.90, 0.00, 139.90, 'PENDIENTE'), -- plan 12
-- Servicio 21 (Cliente 20 - Rosa) - PAGADO
(21, 7, 119.90, 0.00, 119.90, 'PAGADO'), -- plan 5
-- Empresas
(22, 6, 99.90, 0.00, 99.90, 'PAGADO'),   -- Restaurante, plan 6
(22, 7, 99.90, 0.00, 99.90, 'PENDIENTE'),
(23, 7, 149.90, 0.00, 149.90, 'PENDIENTE'), -- Gimnasio, plan 7
(24, 7, 189.90, 0.00, 189.90, 'PAGADO'), -- Clínica, plan 8
(25, 7, 199.90, 0.00, 199.90, 'PENDIENTE'), -- Estudio, plan 9
(26, 7, 49.90, 0.00, 49.90, 'PENDIENTE'), -- Bodega, plan 2
(27, 7, 69.90, 0.00, 69.90, 'PENDIENTE'), -- Ferretería, plan 3
(28, 6, 129.90, 0.00, 129.90, 'PAGADO'), -- Hotel, plan 11
(28, 7, 129.90, 0.00, 129.90, 'PENDIENTE'),
(29, 7, 139.90, 0.00, 139.90, 'PENDIENTE'); -- Colegio, plan 13

-- =====================================================
-- 14. CARGOS ADICIONALES (PERIODOS CORREGIDOS)
-- =====================================================
INSERT INTO cargo_adicional (id_servicio, id_periodo, concepto, descripcion, monto, origen, estado) VALUES
(4, 7, 'Visita técnica', 'Cambio de router averiado', 35.00, 'VISITA_TECNICA', 'PENDIENTE'),
(7, 7, 'Reconexión', 'Reactivación de servicio suspendido', 25.00, 'MANUAL', 'APLICADO'),
(12, 7, 'Mora acumulada', 'Intereses por mora 4 meses', 45.90, 'MANUAL', 'APLICADO'),
(16, 7, 'Visita técnica', 'Reparación de cableado', 40.00, 'VISITA_TECNICA', 'PENDIENTE'),
(22, 7, 'Instalación adicional', 'Punto extra de WiFi', 30.00, 'VISITA_TECNICA', 'PENDIENTE'),
(23, 7, 'Mantenimiento preventivo', 'Limpieza de equipos', 25.00, 'VISITA_TECNICA', 'APLICADO'),
(27, 7, 'Cambio de ONT', 'Equipo dañado por rayo', 150.00, 'VISITA_TECNICA', 'PENDIENTE'),
(29, 7, 'Instalación', 'Segundo punto de red', 45.00, 'VISITA_TECNICA', 'APLICADO');

-- =====================================================
-- 15. PAGOS REGISTRADOS (IDs DE USUARIO CORREGIDOS)
-- =====================================================
INSERT INTO pagos (id_deuda, id_metodo_pago, id_usuario, monto, numero_operacion, banco, estado, fecha_pago, referencia) VALUES
-- Pagos de servicio 1 y 2 (María) - id_deuda 1 y 2
(1, 2, 3, 89.90, 'TARJETA-445566', 'BBVA', 'VALIDADO', '2025-02-05 10:30:00', 'PAG-2025-020'),
(2, 2, 3, 119.90, 'TARJETA-445567', 'BBVA', 'VALIDADO', '2025-02-05 10:31:00', 'PAG-2025-021'),
-- Pago servicio 5 (Roberto) - id_deuda 5
(5, 4, 2, 129.90, 'YAPE-112233', NULL, 'VALIDADO', '2025-03-01 14:20:00', 'PAG-2025-030'),
-- Pago parcial servicio 6 (Patricia) - id_deuda 6
(6, 1, 3, 100.00, NULL, NULL, 'VALIDADO', '2025-03-05 16:15:00', 'PAG-2025-035'),
-- Pago servicio 8 (Luis) - id_deuda 8
(8, 3, 2, 189.90, 'TRF-998822', 'BCP', 'VALIDADO', '2025-03-02 09:45:00', 'PAG-2025-031'),
-- Pago servicio 10 (Diego) - id_deuda 10
(10, 4, 3, 139.90, 'YAPE-556677', NULL, 'VALIDADO', '2025-03-03 11:10:00', 'PAG-2025-032'),
-- Pago servicio 13 (Verónica) - id_deuda 13
(13, 5, 2, 199.90, 'PLIN-334455', NULL, 'VALIDADO', '2025-03-04 12:30:00', 'PAG-2025-033'),
-- Pago servicio 14 (Oscar) - id_deuda 14
(14, 3, 3, 119.90, 'TRF-223344', 'INTERBANK', 'VALIDADO', '2025-03-06 08:50:00', 'PAG-2025-036'),
-- Pago servicio 17 (Elena) - id_deuda 17
(17, 2, 2, 79.90, 'TARJETA-667788', 'SCOTIABANK', 'VALIDADO', '2025-03-07 15:40:00', 'PAG-2025-037'),
-- Pago servicio 19 (Claudia) - id_deuda 19
(19, 4, 3, 129.90, 'YAPE-990011', NULL, 'VALIDADO', '2025-03-08 13:25:00', 'PAG-2025-038'),
-- Pago servicio 21 (Rosa) - id_deuda 21
(21, 5, 2, 119.90, 'PLIN-112233', NULL, 'VALIDADO', '2025-03-09 10:15:00', 'PAG-2025-039'),
-- Pago empresa Restaurante (servicio 22, deuda 22) - Febrero
(22, 3, 2, 99.90, 'TRF-771122', 'BCP', 'VALIDADO', '2025-02-25 17:30:00', 'PAG-2025-040'),
-- Pago empresa Clínica (servicio 24, deuda 24) - Marzo
(24, 1, 3, 189.90, NULL, NULL, 'VALIDADO', '2025-03-10 09:00:00', 'PAG-2025-041'),
-- Pago empresa Hotel (servicio 28, deuda 28) - Febrero
(28, 3, 2, 129.90, 'TRF-556677', 'BBVA', 'VALIDADO', '2025-02-20 11:45:00', 'PAG-2025-042'),
-- Pago adicional mora (servicio 7, deuda 7) - id_deuda 7
(7, 4, 9, 119.80, 'YAPE-778899', NULL, 'VALIDADO', '2025-03-11 19:20:00', 'PAG-2025-043');

-- =====================================================
-- 16. COMPROBANTES DE PAGO
-- =====================================================
INSERT INTO pago_comprobante (id_pago, numero, ruta_pdf, fecha_emision) VALUES
(1, 'B001-000124', '/comprobantes/2025/02/B001-000124.pdf', '2025-02-05 10:35:00'),
(2, 'B001-000125', '/comprobantes/2025/02/B001-000125.pdf', '2025-02-05 10:36:00'),
(3, 'B001-000126', '/comprobantes/2025/03/B001-000126.pdf', '2025-03-01 14:25:00'),
(4, 'B001-000127', '/comprobantes/2025/03/B001-000127.pdf', '2025-03-05 16:20:00'),
(5, 'B001-000128', '/comprobantes/2025/03/B001-000128.pdf', '2025-03-02 09:50:00'),
(6, 'B001-000129', '/comprobantes/2025/03/B001-000129.pdf', '2025-03-03 11:15:00'),
(7, 'B001-000130', '/comprobantes/2025/03/B001-000130.pdf', '2025-03-04 12:35:00'),
(8, 'B001-000131', '/comprobantes/2025/03/B001-000131.pdf', '2025-03-06 08:55:00'),
(9, 'B001-000132', '/comprobantes/2025/03/B001-000132.pdf', '2025-03-07 15:45:00'),
(10, 'B001-000133', '/comprobantes/2025/03/B001-000133.pdf', '2025-03-08 13:30:00'),
(11, 'B001-000134', '/comprobantes/2025/03/B001-000134.pdf', '2025-03-09 10:20:00'),
(12, 'B001-000135', '/comprobantes/2025/02/B001-000135.pdf', '2025-02-25 17:35:00'),
(13, 'B001-000136', '/comprobantes/2025/03/B001-000136.pdf', '2025-03-10 09:05:00'),
(14, 'B001-000137', '/comprobantes/2025/02/B001-000137.pdf', '2025-02-20 11:50:00'),
(15, 'B001-000138', '/comprobantes/2025/03/B001-000138.pdf', '2025-03-11 19:25:00');

-- =====================================================
-- 17. ENVÍOS WHATSAPP (SOLO EMPLEADOS, IDs CORREGIDOS)
-- =====================================================
INSERT INTO envio_whatsapp (id_comprobante, id_usuario, telefono, estado, fecha_envio) VALUES
(1, 8, '987654321', 'ENVIADO', '2025-02-05 10:40:00'),  -- Ventas Sofia
(2, 8, '987654321', 'ENVIADO', '2025-02-05 10:41:00'),
(3, 9, '954321098', 'ENVIADO', '2025-03-01 14:30:00'),  -- Ventas Diego
(4, 8, '943210987', 'ENVIADO', '2025-03-05 16:25:00'),
(5, 9, '921098765', 'ENVIADO', '2025-03-02 09:55:00'),
(6, 8, '910987654', 'ENVIADO', '2025-03-03 11:20:00'),
(7, 9, '999876543', 'ENVIADO', '2025-03-04 12:40:00'),
(8, 8, '966543210', 'ENVIADO', '2025-03-06 09:00:00'),
(9, 9, '911098765', 'ENVIADO', '2025-03-07 15:50:00'),
(10, 8, '988765432', 'ENVIADO', '2025-03-08 13:35:00'),
(11, 9, '955432109', 'ENVIADO', '2025-03-09 10:25:00'),
(12, 8, '977654322', 'ENVIADO', '2025-02-25 17:40:00'),
(13, 9, '933210988', 'ENVIADO', '2025-03-10 09:10:00'),
(14, 8, '900987655', 'ENVIADO', '2025-02-20 11:55:00'),
(15, 9, '932109876', 'ENVIADO', '2025-03-11 19:30:00');

-- =====================================================
-- 18. TICKETS DE SOPORTE
-- =====================================================
INSERT INTO ticket (id_cliente, id_servicio, id_telefono, tipo_problema, urgencia, estado, descripcion, creado_en) VALUES
(1, 1, 1, 'Velocidad lenta', 'MEDIO', 'RESUELTO', 'Cliente reporta que la velocidad es menor a la contratada', '2025-02-10 08:30:00'),
(3, 4, 3, 'Servicio suspendido', 'ALTO', 'RESUELTO', 'Cliente solicita reactivación, ya realizó pago', '2025-02-15 09:45:00'),
(4, 5, 4, 'Router no enciende', 'ALTO', 'RESUELTO', 'Equipo no tiene luz, probable fuente dañada', '2025-02-18 10:20:00'),
(6, 7, 6, 'Cortes frecuentes', 'ALTO', 'EN_PROCESO', 'Se cae el internet cada 2 horas', '2025-02-20 14:00:00'),
(8, 9, 8, 'Configuración de WiFi', 'BAJO', 'ABIERTO', 'Cliente desea cambiar contraseña', '2025-03-01 11:15:00'),
(11, 12, 11, 'Deuda acumulada', 'ALTO', 'ABIERTO', 'Cliente reclama montos de mora', '2025-03-02 16:30:00'),
(13, 14, 13, 'Mudanza', 'MEDIO', 'ASIGNADO', 'Cliente solicita cambio de dirección', '2025-03-03 09:00:00'),
(15, 16, 15, 'Fibra rota', 'ALTO', 'DERIVADO', 'Poste con cable caído en la calle', '2025-03-04 07:50:00'),
(17, 18, 17, 'Facturación', 'BAJO', 'ABIERTO', 'Cliente no recibe factura por email', '2025-03-05 13:40:00'),
(21, 22, 21, 'WiFi intermitente', 'MEDIO', 'EN_PROCESO', 'Señal inestable en zona de mesas', '2025-03-06 12:10:00'),
(23, 24, 23, 'Instalación', 'MEDIO', 'PROGRAMADO', 'Requiere segundo punto para consultorio', '2025-03-07 10:30:00'),
(26, 27, 26, 'Equipo dañado', 'ALTO', 'ASIGNADO', 'ONT quemada por tormenta eléctrica', '2025-03-08 15:20:00'),
(28, 29, 28, 'Ampliación', 'MEDIO', 'ABIERTO', 'Solicitan 5 puntos de red adicionales', '2025-03-09 08:15:00');

-- =====================================================
-- 19. MENSAJES DE TICKETS
-- =====================================================
INSERT INTO ticket_mensaje (id_ticket, id_usuario, tipo, mensaje, creado_en) VALUES
(1, 2, 'SOLICITUD', 'Ventas: Contacté al cliente, intentamos reinicio remoto sin éxito', '2025-02-10 08:45:00'),
(1, 4, 'ACTUALIZACION', 'Soporte: Revisé configuración, canal saturado, optimicé', '2025-02-10 09:30:00'),
(1, 4, 'RESOLUCION', 'Soporte: Velocidad normalizada, cierro ticket', '2025-02-10 10:00:00'),
(2, 3, 'SOLICITUD', 'Ventas: Cliente pagó deuda, procedo a reactivar', '2025-02-15 10:00:00'),
(2, 1, 'RESOLUCION', 'Admin: Servicio reactivado manualmente', '2025-02-15 10:15:00'),
(3, 5, 'ACTUALIZACION', 'Soporte: Diagnóstico, fuente de poder quemada', '2025-02-18 10:45:00'),
(3, 7, 'RESOLUCION', 'Técnico: Cambié fuente de poder, router operativo', '2025-02-18 11:30:00'),
(4, 4, 'ACTUALIZACION', 'Soporte: Revisando logs, hay pérdida de paquetes', '2025-02-20 14:30:00'),
(4, 4, 'SOLICITUD', 'Soporte: Programé visita técnica para mañana', '2025-02-20 15:00:00'),
(5, 2, 'SOLICITUD', 'Ventas: Cliente indica que no recuerda contraseña', '2025-03-01 11:30:00'),
(6, 2, 'SOLICITUD', 'Ventas: Cliente no reconoce deuda de S/195.80', '2025-03-02 16:45:00'),
(7, 3, 'SOLICITUD', 'Ventas: Solicito cambio de dirección a Surco', '2025-03-03 09:15:00'),
(8, 5, 'ACTUALIZACION', 'Soporte: Confirmé fibra rota, se requiere cuadrilla', '2025-03-04 08:30:00'),
(8, 1, 'ACTUALIZACION', 'Admin: Derivado a técnicos de campo', '2025-03-04 09:00:00'),
(9, 2, 'SOLICITUD', 'Ventas: Corregí email en sistema, reenvié factura', '2025-03-05 14:00:00'),
(10, 5, 'ACTUALIZACION', 'Soporte: Revisé potencia, ajusté canal WiFi', '2025-03-06 12:45:00'),
(11, 6, 'ACTUALIZACION', 'Técnico: Programé instalación para 10/03', '2025-03-07 11:00:00'),
(12, 7, 'ACTUALIZACION', 'Técnico: Confirmé ONT dañada, requiero repuesto', '2025-03-08 15:45:00'),
(13, 2, 'SOLICITUD', 'Ventas: Cotización enviada, cliente aceptó', '2025-03-09 08:45:00');

-- =====================================================
-- 20. VISITAS TÉCNICAS
-- =====================================================
INSERT INTO visita_tecnica (id_ticket, id_tecnico, estado, fecha_programada, inicio, fin, diagnostico, solucion) VALUES
(3, 7, 'CONCLUIDA', '2025-02-18 08:00:00', '2025-02-18 08:10:00', '2025-02-18 08:45:00',
 'Fuente de poder 12V no entrega voltaje', 'Reemplazo de fuente de poder. Router OK'),
(4, 6, 'CONCLUIDA', '2025-02-21 09:00:00', '2025-02-21 09:05:00', '2025-02-21 10:30:00',
 'Fibra con microcurvatura en acometida', 'Se reemplazó tramo de fibra óptica'),
(8, 6, 'PROGRAMADA', '2025-03-05 08:00:00', NULL, NULL,
 'Fibra rota en poste', 'Pendiente de ejecución'),
(11, 7, 'PROGRAMADA', '2025-03-10 14:00:00', NULL, NULL,
 'Instalación de punto adicional', 'Pendiente'),
(12, 6, 'ASIGNADA', '2025-03-09 10:00:00', NULL, NULL,
 'Cambio de ONT', 'Pendiente de repuesto');

-- =====================================================
-- 21. MATERIALES (CORREGIDOS Y COMPLETOS)
-- =====================================================
INSERT INTO material (nombre, unidad, precio_unitario, activo) VALUES
('Cable UTP Cat6', 'metro', 1.50, TRUE),
('Conector RJ45', 'unidad', 0.80, TRUE),
('Patch cord', 'unidad', 5.00, TRUE),
('Fibra óptica drop', 'metro', 2.20, TRUE),
('Caja terminal', 'unidad', 45.00, TRUE),
('Acoplador SC-SC', 'unidad', 3.50, TRUE),
('Roseta óptica', 'unidad', 12.00, TRUE),
('Pigtail SC', 'unidad', 4.50, TRUE),
('Cinta aislante', 'rollo', 2.00, TRUE),
('Tacos y tornillos', 'juego', 1.50, TRUE),
('Canaleta 20x10', 'metro', 3.00, TRUE),
('Bridas', 'bolsa', 5.00, TRUE);

-- =====================================================
-- 22. MATERIALES USADOS EN VISITAS (IDs CORREGIDOS)
-- =====================================================
INSERT INTO visita_material (id_visita, id_material, cantidad, precio_unitario, total) VALUES
(1, 1, 25, 1.50, 37.50),   -- Cable UTP Cat6
(1, 2, 4, 0.80, 3.20),     -- Conectores RJ45
(1, 3, 2, 5.00, 10.00),    -- Patch cord
(2, 4, 50, 2.20, 110.00),  -- Fibra óptica drop
(2, 5, 1, 45.00, 45.00),   -- Caja terminal
(2, 6, 2, 3.50, 7.00),     -- Acoplador SC-SC
(2, 7, 1, 12.00, 12.00),   -- Roseta óptica
(2, 8, 2, 4.50, 9.00);     -- Pigtail SC

-- =====================================================
-- 23. NOTIFICACIONES MASIVAS
-- =====================================================
INSERT INTO notificacion (id_cliente, tipo, canal, mensaje, enviado_en) VALUES
(1, 'SOPORTE', 'SISTEMA', 'Su ticket #1 ha sido resuelto', '2025-02-10 10:05:00'),
(2, 'DEUDA_PENDIENTE', 'WHATSAPP', 'Tiene una deuda pendiente de S/69.90', '2025-03-11 09:00:00'),
(3, 'SUSPENSION', 'WHATSAPP', 'Su servicio será suspendido por deuda', '2025-03-01 08:00:00'),
(4, 'VISITA_PROGRAMADA', 'WHATSAPP', 'Visita técnica programada para 18/02', '2025-02-17 17:00:00'),
(5, 'PAGO_PARCIAL', 'EMAIL', 'Hemos recibido un pago parcial de S/100.00', '2025-03-05 16:30:00'),
(6, 'VISITA_PROGRAMADA', 'WHATSAPP', 'Visita programada para 05/03', '2025-03-04 18:00:00'),
(8, 'SOPORTE', 'SISTEMA', 'Su ticket #5 ha sido recibido', '2025-03-01 11:20:00'),
(11, 'DEUDA', 'EMAIL', 'Detalle de su deuda acumulada', '2025-03-03 09:00:00'),
(12, 'BIENVENIDA', 'EMAIL', 'Bienvenido a ProRed, su servicio ya está activo', '2025-02-20 14:00:00'),
(13, 'FACTURA', 'EMAIL', 'Su factura del mes de marzo está disponible', '2025-03-05 08:00:00'),
(15, 'AVERIA', 'WHATSAPP', 'Reportamos una avería en su zona, técnicos trabajando', '2025-03-04 08:00:00'),
(17, 'FACTURA', 'EMAIL', 'Su factura del mes de marzo está disponible', '2025-03-05 08:00:00'),
(21, 'SOPORTE', 'SISTEMA', 'Su ticket #10 está en proceso', '2025-03-06 13:00:00'),
(23, 'VISITA_PROGRAMADA', 'WHATSAPP', 'Instalación programada para 10/03', '2025-03-07 12:00:00'),
(26, 'VISITA_PROGRAMADA', 'WHATSAPP', 'Cambio de equipo programado', '2025-03-08 16:00:00'),
(28, 'COTIZACION', 'EMAIL', 'Cotización de ampliación de red enviada', '2025-03-09 09:00:00');

-- =====================================================
-- 24. AUDITORÍA (ACTIVIDADES DEL SISTEMA)
-- =====================================================
INSERT INTO auditoria_log (id_usuario, accion, modulo, datos_antes, datos_despues, ip, creado_en) VALUES
(2, 'REACTIVAR_SERVICIO', 'COBRANZA', '{"estado": "SUSPENDIDO"}', '{"estado": "ACTIVO"}', '10.0.0.25', '2025-02-15 10:10:00'),
(7, 'COMPLETAR_VISITA', 'CAMPO', '{"estado": "EN_CAMINO"}', '{"estado": "CONCLUIDA"}', '10.0.0.45', '2025-02-18 08:50:00'),
(1, 'CREAR_USUARIO', 'SEGURIDAD', NULL, '{"usuario_id": 32, "rol": "user"}', '10.0.0.10', '2025-02-20 11:00:00'),
(3, 'REGISTRAR_PAGO', 'COBRANZA', NULL, '{"pago_id": 15, "monto": 119.80}', '10.0.0.26', '2025-03-11 19:20:00'),
(5, 'ACTUALIZAR_TICKET', 'SOPORTE', '{"estado": "ABIERTO"}', '{"estado": "EN_PROCESO"}', '10.0.0.35', '2025-03-04 08:25:00'),
(6, 'ASIGNAR_VISITA', 'CAMPO', NULL, '{"tecnico_id": 6, "visita_id": 3}', '10.0.0.40', '2025-03-04 09:05:00'),
(2, 'MODIFICAR_PLAN', 'VENTAS', '{"plan_id": 5, "precio": 79.90}', '{"plan_id": 5, "precio": 69.90}', '10.0.0.25', '2025-03-01 10:30:00'),
(4, 'CERRAR_TICKET', 'SOPORTE', '{"estado": "DERIVADO"}', '{"estado": "RESUELTO"}', '10.0.0.30', '2025-02-21 10:35:00'),
(1, 'BACKUP', 'SISTEMA', NULL, '{"backup": "20250301_full.sql"}', '10.0.0.10', '2025-03-01 23:00:00'),
(3, 'ENVIAR_FACTURA', 'COBRANZA', NULL, '{"cliente_id": 17, "periodo": "2025-03"}', '10.0.0.26', '2025-03-05 08:05:00');

