-- Libro de Reclamaciones — esquema MySQL (utf8mb4)
-- Ejecutar en phpMyAdmin o: mysql -u usuario -p BD < sql/schema.sql

SET NAMES utf8mb4;
SET time_zone = '-05:00';

-- --------------------------------------------------------
-- Reclamaciones
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS reclamaciones (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  numero INT UNSIGNED NOT NULL,
  codigo VARCHAR(32) NOT NULL,
  constancia_token CHAR(64) NOT NULL,
  fecha_registro DATETIME NOT NULL,

  tipo_persona ENUM('natural','juridica') NOT NULL DEFAULT 'natural',
  nombre_razon_social VARCHAR(200) NOT NULL,
  tipo_documento ENUM('dni','ce','ruc','otro') NOT NULL,
  numero_documento VARCHAR(20) NOT NULL,
  domicilio VARCHAR(255) NOT NULL,
  telefono VARCHAR(30) NOT NULL,
  email VARCHAR(150) NOT NULL,

  es_menor TINYINT(1) NOT NULL DEFAULT 0,
  representante_nombre VARCHAR(200) NULL,
  representante_documento VARCHAR(30) NULL,

  tipo_bien ENUM('producto','servicio') NOT NULL DEFAULT 'servicio',
  servicio VARCHAR(100) NOT NULL,
  descripcion_bien VARCHAR(500) NULL,
  monto_reclamado DECIMAL(10,2) NULL,

  tipo ENUM('reclamo','queja') NOT NULL,
  detalle TEXT NOT NULL,
  pedido TEXT NOT NULL,

  estado ENUM(
    'RECIBIDO','EN_REVISION','EN_ATENCION',
    'PENDIENTE_INFORMACION','RESPONDIDO','CERRADO'
  ) NOT NULL DEFAULT 'RECIBIDO',

  fecha_limite_respuesta DATE NOT NULL,
  fecha_respuesta DATETIME NULL,
  respuesta TEXT NULL,
  acciones_adoptadas TEXT NULL,
  medio_envio_respuesta VARCHAR(50) NULL,
  usuario_responsable VARCHAR(100) NULL,

  canal_presentacion VARCHAR(50) NOT NULL DEFAULT 'WEB',
  ip_registro VARCHAR(45) NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  UNIQUE KEY uq_numero (numero),
  UNIQUE KEY uq_codigo (codigo),
  UNIQUE KEY uq_token (constancia_token),
  KEY idx_fecha (fecha_registro),
  KEY idx_estado (estado),
  KEY idx_doc (numero_documento),
  KEY idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Historial / auditoría
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS reclamacion_historial (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  reclamacion_id INT UNSIGNED NOT NULL,
  usuario_id INT UNSIGNED NULL,
  accion VARCHAR(50) NOT NULL,
  descripcion VARCHAR(500) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_recl (reclamacion_id),
  CONSTRAINT fk_hist_recl FOREIGN KEY (reclamacion_id)
    REFERENCES reclamaciones(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Contador correlativo (transaccional)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS reclamaciones_contador (
  anio SMALLINT NOT NULL,
  ultimo INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (anio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO reclamaciones_contador (anio, ultimo) VALUES (YEAR(NOW()), 0)
ON DUPLICATE KEY UPDATE anio = anio;

-- --------------------------------------------------------
-- Usuarios administrativos
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin_users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  nombre VARCHAR(100) NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  ultimo_acceso DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Rate limiting por IP (registro de envíos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS rate_limit (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ip VARCHAR(45) NOT NULL,
  accion VARCHAR(50) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_ip_accion (ip, accion, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Acceso al panel (login/logout) — sin FK a reclamaciones
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin_access_log (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INT UNSIGNED NULL,
  username VARCHAR(50) NULL,
  accion VARCHAR(50) NOT NULL,
  ip VARCHAR(45) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_usuario (usuario_id),
  KEY idx_created (created_at),
  CONSTRAINT fk_access_user FOREIGN KEY (usuario_id)
    REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
