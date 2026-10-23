-- Base de datos: translogistica
-- Motor: MySQL 8.0
-- Orden respetando dependencias de FK

USE translogistica;

CREATE TABLE clientes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  tipo_operador ENUM('importador','exportador') NOT NULL,
  origen ENUM('local','brasil','argentina') NOT NULL,
  usuario VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE usuarios_internos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  usuario VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol ENUM('administrativo','operativo','vendedor') NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE transportistas (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  tipo_camion VARCHAR(255) NOT NULL,
  contacto VARCHAR(255) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE despachantes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  pais VARCHAR(255) NOT NULL,
  tipo ENUM('uruguayo','extranjero') NOT NULL,
  licencia VARCHAR(255) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE fichas (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NOT NULL,
  vendedor_id BIGINT UNSIGNED NOT NULL,
  transportista_id BIGINT UNSIGNED NULL,
  num_factura VARCHAR(255) NOT NULL,
  num_crt VARCHAR(255) NOT NULL UNIQUE,
  tipo_carga ENUM('consolidada','completa') NOT NULL,
  peso_bruto DECIMAL(10,2) NOT NULL,
  volumen DECIMAL(10,2) NULL,
  tipo_bulto VARCHAR(255) NULL,
  estado ENUM('iniciada','en_curso','cerrada') NOT NULL DEFAULT 'iniciada',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  FOREIGN KEY (vendedor_id) REFERENCES usuarios_internos(id),
  FOREIGN KEY (transportista_id) REFERENCES transportistas(id)
);

CREATE TABLE ficha_despachante (
  ficha_id BIGINT UNSIGNED NOT NULL,
  despachante_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (ficha_id, despachante_id),
  FOREIGN KEY (ficha_id) REFERENCES fichas(id) ON DELETE CASCADE,
  FOREIGN KEY (despachante_id) REFERENCES despachantes(id) ON DELETE CASCADE
);

CREATE TABLE seguimientos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ficha_id BIGINT UNSIGNED NOT NULL,
  etapa ENUM('carga','frontera','liberacion','entrega') NOT NULL,
  fecha DATE NOT NULL,
  canal_aduanero ENUM('verde','naranja','rojo') NULL,
  observacion TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (ficha_id) REFERENCES fichas(id) ON DELETE CASCADE
);

CREATE TABLE documentos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ficha_id BIGINT UNSIGNED NOT NULL,
  tipo_documento ENUM('DUA','DUE','licencia','certificado','CRT','factura') NOT NULL,
  numero VARCHAR(255) NULL,
  archivo_path VARCHAR(255) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (ficha_id) REFERENCES fichas(id) ON DELETE CASCADE
);

CREATE TABLE notificaciones (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ficha_id BIGINT UNSIGNED NOT NULL,
  tipo VARCHAR(255) NOT NULL,
  mensaje TEXT NOT NULL,
  leida BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (ficha_id) REFERENCES fichas(id) ON DELETE CASCADE
);
