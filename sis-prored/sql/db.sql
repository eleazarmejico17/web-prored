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
    nombre VARCHAR(100) NOT NULL,
    id_provincia INT,
    FOREIGN KEY (id_provincia) REFERENCES provincia(id_provincia)
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
-- ESCALAR DISTRITOS A JERARQUÍA PROVINCIA -> DISTRITO
-- =====================================================

CREATE TABLE provincia (
    id_provincia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

ALTER TABLE distrito
ADD COLUMN id_provincia INT,
ADD FOREIGN KEY (id_provincia) REFERENCES provincia(id_provincia);

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

-- =====================================================
-- GESTIÓN DE HERRAMIENTAS Y EPPs
-- =====================================================

CREATE TABLE herramienta (
    id_herramienta INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    cantidad_total INT NOT NULL,
    cantidad_disponible INT NOT NULL,
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE prestamo_herramienta (
    id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
    id_herramienta INT NOT NULL,
    id_tecnico INT NOT NULL,
    cantidad INT NOT NULL,
    fecha_prestamo DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_devolucion DATETIME,
    estado ENUM('PRESTADO','DEVUELTO') DEFAULT 'PRESTADO',

    FOREIGN KEY (id_herramienta) REFERENCES herramienta(id_herramienta),
    FOREIGN KEY (id_tecnico) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

-- =====================================================
-- MODELO DE CUADRILLAS Y CONDUCTORES DESIGNADOS
-- =====================================================

CREATE TABLE cuadrilla (
    id_cuadrilla INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE cuadrilla_tecnico (
    id_cuadrilla_tecnico INT AUTO_INCREMENT PRIMARY KEY,
    id_cuadrilla INT NOT NULL,
    id_tecnico INT NOT NULL,
    es_conductor BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (id_cuadrilla) REFERENCES cuadrilla(id_cuadrilla),
    FOREIGN KEY (id_tecnico) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

-- =====================================================
-- MODELO DE VEHÍCULOS Y REVISIONES
-- =====================================================

CREATE TABLE vehiculo (
    id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(20) NOT NULL UNIQUE,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    anio INT,
    id_conductor INT NOT NULL,

    FOREIGN KEY (id_conductor) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE revision_vehiculo (
    id_revision INT AUTO_INCREMENT PRIMARY KEY,
    id_vehiculo INT NOT NULL,
    fecha_revision DATETIME NOT NULL,
    descripcion TEXT,
    imagenes JSON,

    FOREIGN KEY (id_vehiculo) REFERENCES vehiculo(id_vehiculo)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- GESTIÓN DE DOCUMENTOS DE VEHÍCULOS
-- =====================================================

CREATE TABLE documento_vehiculo (
    id_documento INT AUTO_INCREMENT PRIMARY KEY,
    id_vehiculo INT NOT NULL,
    tipo_documento ENUM('TARJETA_CIRCULACION', 'SOAT') NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    ruta_documento VARCHAR(255),

    FOREIGN KEY (id_vehiculo) REFERENCES vehiculo(id_vehiculo)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- ALERTAS PARA VENCIMIENTO DE SOAT
-- =====================================================

CREATE TABLE alerta_soat (
    id_alerta INT AUTO_INCREMENT PRIMARY KEY,
    id_documento INT NOT NULL,
    id_supervisor INT NOT NULL,
    estado ENUM('PENDIENTE','ENVIADA') DEFAULT 'PENDIENTE',
    fecha_alerta DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_documento) REFERENCES documento_vehiculo(id_documento)
        ON DELETE CASCADE,
    FOREIGN KEY (id_supervisor) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

-- =====================================================
-- MODELO DE EQUIPOS PARA INSTALACIONES
-- =====================================================

CREATE TABLE equipo (
    id_equipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    tipo ENUM('TRIPLEXOR', 'PATCHCORD', 'ROSETA', 'ROUTER') NOT NULL,
    numero_serie VARCHAR(50) UNIQUE,
    mac VARCHAR(50) UNIQUE NOT NULL,
    sn VARCHAR(50) UNIQUE NOT NULL,
    estado ENUM('ALMACEN', 'VEHICULO', 'INSTALADO') DEFAULT 'ALMACEN',
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- =====================================================
-- SEGUIMIENTO DE EQUIPOS EN ALMACÉN, VEHÍCULOS Y HOGARES
-- =====================================================

CREATE TABLE equipo_movimiento (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_equipo INT NOT NULL,
    origen ENUM('ALMACEN', 'VEHICULO', 'INSTALADO') NOT NULL,
    destino ENUM('ALMACEN', 'VEHICULO', 'INSTALADO') NOT NULL,
    id_vehiculo INT NULL,
    id_servicio INT NULL,
    fecha_movimiento DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_equipo) REFERENCES equipo(id_equipo),
    FOREIGN KEY (id_vehiculo) REFERENCES vehiculo(id_vehiculo),
    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio)
) ENGINE=InnoDB;

-- =====================================================
-- GESTIÓN DE RETIRO DE EQUIPOS AL CANCELAR SERVICIO
-- =====================================================

CREATE TABLE retiro_equipo (
    id_retiro INT AUTO_INCREMENT PRIMARY KEY,
    id_equipo INT NOT NULL,
    id_servicio INT NOT NULL,
    fecha_retiro DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('PENDIENTE', 'RETIRADO') DEFAULT 'PENDIENTE',
    observaciones TEXT,

    FOREIGN KEY (id_equipo) REFERENCES equipo(id_equipo),
    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio)
) ENGINE=InnoDB;

-- =====================================================
-- GESTIÓN DE CAMBIOS DE EQUIPOS DEFECTUOSOS U OBSOLETOS
-- =====================================================

CREATE TABLE cambio_equipo (
    id_cambio INT AUTO_INCREMENT PRIMARY KEY,
    id_equipo_viejo INT NOT NULL,
    id_equipo_nuevo INT NOT NULL,
    id_servicio INT NOT NULL,
    motivo ENUM('DEFECTUOSO', 'OBSOLETO') NOT NULL,
    fecha_cambio DATETIME DEFAULT CURRENT_TIMESTAMP,
    observaciones TEXT,

    FOREIGN KEY (id_equipo_viejo) REFERENCES equipo(id_equipo),
    FOREIGN KEY (id_equipo_nuevo) REFERENCES equipo(id_equipo),
    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio)
) ENGINE=InnoDB;

-- =====================================================
-- REGISTRAR USUARIOS Y ASIGNAR SERVICIOS SEGÚN PLAN
-- =====================================================

-- La tabla 'cliente' ya existe para registrar usuarios.
-- La tabla 'servicio' ya existe para asignar servicios según el plan.

-- No se requieren cambios adicionales en esta sección, ya que las tablas existentes cumplen con los requisitos.

-- =====================================================
-- CREAR TICKETS DE INSTALACIÓN PARA TÉCNICOS
-- =====================================================

-- La tabla 'ticket' ya existe para gestionar tickets.

ALTER TABLE ticket
ADD COLUMN tipo_ticket ENUM('INSTALACION', 'REPARACION') DEFAULT 'INSTALACION';

-- =====================================================
-- GESTIONAR RUTAS DE TÉCNICOS CON TICKETS
-- =====================================================

CREATE TABLE ruta_tecnico (
    id_ruta INT AUTO_INCREMENT PRIMARY KEY,
    id_tecnico INT NOT NULL,
    fecha DATE NOT NULL,
    estado ENUM('PENDIENTE', 'EN_PROGRESO', 'COMPLETADA') DEFAULT 'PENDIENTE',

    FOREIGN KEY (id_tecnico) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE ruta_ticket (
    id_ruta_ticket INT AUTO_INCREMENT PRIMARY KEY,
    id_ruta INT NOT NULL,
    id_ticket INT NOT NULL,

    FOREIGN KEY (id_ruta) REFERENCES ruta_tecnico(id_ruta),
    FOREIGN KEY (id_ticket) REFERENCES ticket(id_ticket)
) ENGINE=InnoDB;

-- =====================================================
-- SOLICITAR MATERIALES AL ENCARGADO DE ALMACÉN
-- =====================================================

CREATE TABLE solicitud_material (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    id_tecnico INT NOT NULL,
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('PENDIENTE', 'APROBADA', 'RECHAZADA') DEFAULT 'PENDIENTE',

    FOREIGN KEY (id_ticket) REFERENCES ticket(id_ticket),
    FOREIGN KEY (id_tecnico) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE detalle_solicitud_material (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_solicitud INT NOT NULL,
    id_material INT NOT NULL,
    cantidad INT NOT NULL,

    FOREIGN KEY (id_solicitud) REFERENCES solicitud_material(id_solicitud),
    FOREIGN KEY (id_material) REFERENCES material(id_material)
) ENGINE=InnoDB;

-- =====================================================
-- ADJUNTAR IMÁGENES Y DETALLES AL CERRAR TICKETS
-- =====================================================

ALTER TABLE ticket
ADD COLUMN imagen_instalacion VARCHAR(255),
ADD COLUMN imagen_fachada VARCHAR(255),
ADD COLUMN descripcion_problema TEXT,
ADD COLUMN material_usado JSON;
