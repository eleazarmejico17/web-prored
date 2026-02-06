-- =====================================================
-- BASE DE DATOS PRORED
-- =====================================================

DROP DATABASE IF EXISTS prored;
CREATE DATABASE prored CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE prored;

-- =====================================================
-- TABLAS BASE
-- =====================================================

CREATE TABLE distrito (
    id_distrito INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE winbox (
    id_winbox INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE internet (
    id_internet INT AUTO_INCREMENT PRIMARY KEY,
    velocidad VARCHAR(50),
    precio DECIMAL(10,2)
) ENGINE=InnoDB;

CREATE TABLE tv (
    id_tv INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    cantidad_canales INT,
    precio DECIMAL(10,2)
) ENGINE=InnoDB;

CREATE TABLE plan (
    id_plan INT AUTO_INCREMENT PRIMARY KEY,
    id_internet INT,
    id_tv INT,
    nombre VARCHAR(100),
    velocidad_subida VARCHAR(50),
    velocidad_bajada VARCHAR(50),
    dispositivos_incluidos INT,
    precio DECIMAL(10,2),
    precio_dispositivo_extra DECIMAL(10,2),
    descripcion TEXT,
    estado ENUM('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',

    FOREIGN KEY (id_internet) REFERENCES internet(id_internet),
    FOREIGN KEY (id_tv) REFERENCES tv(id_tv)
) ENGINE=InnoDB;

-- =====================================================
-- CLIENTES Y SERVICIOS
-- =====================================================

CREATE TABLE cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15) UNIQUE,
    nombres VARCHAR(100),
    apellidos VARCHAR(100),
    razon_social VARCHAR(150),
    ubigeo VARCHAR(50),
    email VARCHAR(150),
    activo BOOLEAN DEFAULT TRUE,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE cliente_telefono (
    id_telefono INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    numero VARCHAR(15) NOT NULL,
    tipo ENUM('MOVIL','FIJO','WHATSAPP'),
    principal BOOLEAN DEFAULT FALSE,
    activo BOOLEAN DEFAULT TRUE,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE servicio (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_plan INT,
    id_winbox INT,
    id_distrito INT,
    direccion VARCHAR(255),
    ip_asignada VARCHAR(50),
    estado ENUM('ACTIVO','SUSPENDIDO','EN_MORA','CORTADO') DEFAULT 'ACTIVO',
    fecha_instalacion DATE,
    fecha_corte DATE,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_plan) REFERENCES plan(id_plan),
    FOREIGN KEY (id_winbox) REFERENCES winbox(id_winbox),
    FOREIGN KEY (id_distrito) REFERENCES distrito(id_distrito)
) ENGINE=InnoDB;

-- =====================================================
-- PERIODOS Y DEUDA
-- =====================================================

CREATE TABLE periodos (
    id_periodo INT AUTO_INCREMENT PRIMARY KEY,
    mes INT,
    anio INT,
    fecha_inicio DATE,
    fecha_fin DATE
) ENGINE=InnoDB;

CREATE TABLE deuda (
    id_deuda INT AUTO_INCREMENT PRIMARY KEY,
    id_servicio INT,
    id_periodo INT,
    monto_base DECIMAL(10,2),
    mora DECIMAL(10,2) DEFAULT 0.00,
    total DECIMAL(10,2),
    estado ENUM('PENDIENTE','PAGADO','PARCIAL') DEFAULT 'PENDIENTE',
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio),
    FOREIGN KEY (id_periodo) REFERENCES periodos(id_periodo)
) ENGINE=InnoDB;

CREATE TABLE cargo_adicional (
    id_cargo INT AUTO_INCREMENT PRIMARY KEY,
    id_servicio INT,
    id_periodo INT,
    concepto VARCHAR(100),
    descripcion TEXT,
    monto DECIMAL(10,2),
    origen ENUM('VISITA_TECNICA','MANUAL'),
    estado ENUM('PENDIENTE','APLICADO') DEFAULT 'PENDIENTE',
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio),
    FOREIGN KEY (id_periodo) REFERENCES periodos(id_periodo)
) ENGINE=InnoDB;

-- =====================================================
-- PAGOS
-- =====================================================

CREATE TABLE metodo_pago (
    id_metodo_pago INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    requiere_comprobante BOOLEAN,
    requiere_operacion BOOLEAN,
    requiere_banco BOOLEAN,
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    descripcion VARCHAR(150)
) ENGINE=InnoDB;

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_rol INT,
    id_cliente INT NULL,
    nombre VARCHAR(100),
    email VARCHAR(150),
    password VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_rol) REFERENCES rol(id_rol),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
) ENGINE=InnoDB;

CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_deuda INT,
    id_metodo_pago INT,
    id_usuario INT,
    monto DECIMAL(10,2),
    numero_operacion VARCHAR(100),
    banco VARCHAR(100),
    estado ENUM('PENDIENTE','VALIDADO','RECHAZADO') DEFAULT 'PENDIENTE',
    fecha_pago DATETIME,
    referencia VARCHAR(100) UNIQUE,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_deuda) REFERENCES deuda(id_deuda),
    FOREIGN KEY (id_metodo_pago) REFERENCES metodo_pago(id_metodo_pago),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE pago_comprobante (
    id_comprobante INT AUTO_INCREMENT PRIMARY KEY,
    id_pago INT,
    numero VARCHAR(50),
    ruta_pdf VARCHAR(255),
    fecha_emision DATETIME,

    FOREIGN KEY (id_pago) REFERENCES pagos(id_pago)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE envio_whatsapp (
    id_envio INT AUTO_INCREMENT PRIMARY KEY,
    id_comprobante INT,
    id_usuario INT,
    telefono VARCHAR(15),
    estado ENUM('PENDIENTE','ENVIADO') DEFAULT 'PENDIENTE',
    fecha_envio DATETIME,

    FOREIGN KEY (id_comprobante) REFERENCES pago_comprobante(id_comprobante),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

-- =====================================================
-- SOPORTE Y TECNICO
-- =====================================================

CREATE TABLE ticket (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    id_servicio INT,
    id_telefono INT,
    tipo_problema VARCHAR(100),
    urgencia ENUM('BAJO','MEDIO','ALTO'),
    estado ENUM('ABIERTO','ASIGNADO','EN_PROCESO','DERIVADO','RESUELTO','CERRADO') DEFAULT 'ABIERTO',
    descripcion TEXT,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio),
    FOREIGN KEY (id_telefono) REFERENCES cliente_telefono(id_telefono)
) ENGINE=InnoDB;

CREATE TABLE ticket_mensaje (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT,
    id_usuario INT,
    tipo ENUM('ACTUALIZACION','SOLICITUD','RESOLUCION'),
    mensaje TEXT,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_ticket) REFERENCES ticket(id_ticket)
        ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE visita_tecnica (
    id_visita INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT,
    id_tecnico INT,
    estado ENUM('PROGRAMADA','EN_CAMINO','ATENDIENDO','CONCLUIDA') DEFAULT 'PROGRAMADA',
    fecha_programada DATETIME,
    inicio DATETIME,
    fin DATETIME,
    diagnostico TEXT,
    solucion TEXT,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_ticket) REFERENCES ticket(id_ticket),
    FOREIGN KEY (id_tecnico) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE material (
    id_material INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    unidad VARCHAR(50),
    precio_unitario DECIMAL(10,2),
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE visita_material (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_visita INT,
    id_material INT,
    cantidad DECIMAL(10,2),
    precio_unitario DECIMAL(10,2),
    total DECIMAL(10,2),

    FOREIGN KEY (id_visita) REFERENCES visita_tecnica(id_visita)
        ON DELETE CASCADE,
    FOREIGN KEY (id_material) REFERENCES material(id_material)
) ENGINE=InnoDB;

-- =====================================================
-- NOTIFICACIONES Y AUDITORIA
-- =====================================================

CREATE TABLE notificacion (
    id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    tipo VARCHAR(50),
    canal ENUM('WHATSAPP','EMAIL','SISTEMA'),
    mensaje TEXT,
    enviado_en DATETIME,

    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
) ENGINE=InnoDB;

CREATE TABLE auditoria_log (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    accion VARCHAR(100),
    modulo VARCHAR(50),
    datos_antes JSON,
    datos_despues JSON,
    ip VARCHAR(45),
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;
