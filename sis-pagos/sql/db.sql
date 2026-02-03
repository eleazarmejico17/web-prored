-- =========================================
-- 1. CREAR BASE DE DATOS
-- =========================================
CREATE DATABASE IF NOT EXISTS isp_control
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE isp_control;

-- =========================================
-- 2. TABLA CLIENTE
-- =========================================
CREATE TABLE cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15) UNIQUE NOT NULL,
    nombres VARCHAR(100),
    apellidos VARCHAR(100),
    razon_social VARCHAR(150),
    email VARCHAR(100) UNIQUE,
    celular_titular VARCHAR(15),
    celular_usuario VARCHAR(15),
    whatsapp VARCHAR(15),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- 3. USUARIOS DEL PORTAL
-- =========================================
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    estado ENUM('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
    ultimo_acceso DATETIME,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
);

-- =========================================
-- 4. UBICACIONES
-- =========================================
CREATE TABLE ubicacion (
    id_ubicacion INT AUTO_INCREMENT PRIMARY KEY,
    distrito VARCHAR(100),
    direccion VARCHAR(200),
    referencia TEXT
);

-- =========================================
-- 5. TIPOS DE SERVICIO
-- =========================================
CREATE TABLE tipo_servicio (
    id_tipo_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- =========================================
-- 6. PLANES
-- =========================================
CREATE TABLE plan (
    id_plan INT AUTO_INCREMENT PRIMARY KEY,
    id_tipo_servicio INT NOT NULL,
    nombre_plan VARCHAR(100) NOT NULL,
    velocidad_mbps INT NULL,
    cantidad_canales INT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_tipo_servicio) REFERENCES tipo_servicio(id_tipo_servicio)
);

-- =========================================
-- 7. CONTRATOS / SERVICIOS DEL CLIENTE
-- =========================================
CREATE TABLE contrato (
    id_contrato INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_plan INT NOT NULL,
    id_ubicacion INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    estado ENUM('ACTIVO','SUSPENDIDO','CORTADO') DEFAULT 'ACTIVO',
    fecha_suspension DATE,
    fecha_corte_fisico DATE,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_plan) REFERENCES plan(id_plan),
    FOREIGN KEY (id_ubicacion) REFERENCES ubicacion(id_ubicacion)
);

-- =========================================
-- 8. MÉTODOS DE PAGO
-- =========================================
CREATE TABLE metodo_pago (
    id_metodo_pago INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- =========================================
-- 9. PAGOS
-- =========================================
CREATE TABLE pago (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    id_metodo_pago INT NOT NULL,
    mes INT NOT NULL,
    anio INT NOT NULL,
    monto_facturado DECIMAL(10,2) NOT NULL,
    monto_pagado DECIMAL(10,2) DEFAULT 0,
    saldo DECIMAL(10,2) NOT NULL,
    fecha_pago DATE,
    banco VARCHAR(50),
    numero_operacion VARCHAR(50),
    observaciones TEXT,
    FOREIGN KEY (id_contrato) REFERENCES contrato(id_contrato),
    FOREIGN KEY (id_metodo_pago) REFERENCES metodo_pago(id_metodo_pago)
);

-- =========================================
-- 10. CONSUMO DE INTERNET
-- =========================================
CREATE TABLE consumo (
    id_consumo INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    mes INT NOT NULL,
    anio INT NOT NULL,
    consumo_gb DECIMAL(10,2),
    FOREIGN KEY (id_contrato) REFERENCES contrato(id_contrato)
);

-- =========================================
-- 11. INCIDENCIAS / REPORTES
-- =========================================
CREATE TABLE incidencia (
    id_incidencia INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    tipo VARCHAR(100),
    descripcion TEXT,
    estado ENUM('ABIERTO','EN PROCESO','RESUELTO') DEFAULT 'ABIERTO',
    fecha_reporte DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATETIME,
    FOREIGN KEY (id_contrato) REFERENCES contrato(id_contrato)
);

-- =========================================
-- 12. SOLICITUDES DE CAMBIO DE PLAN
-- =========================================
CREATE TABLE solicitud_cambio_plan (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    id_plan_nuevo INT NOT NULL,
    motivo TEXT,
    estado ENUM('PENDIENTE','APROBADO','RECHAZADO') DEFAULT 'PENDIENTE',
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_contrato) REFERENCES contrato(id_contrato),
    FOREIGN KEY (id_plan_nuevo) REFERENCES plan(id_plan)
);

-- =========================================
-- 13. ÍNDICES PARA MEJOR RENDIMIENTO
-- =========================================
CREATE INDEX idx_cliente_dni ON cliente(dni);
CREATE INDEX idx_pago_mes_anio ON pago(mes, anio);
CREATE INDEX idx_contrato_estado ON contrato(estado);
CREATE INDEX idx_incidencia_estado ON incidencia(estado);
