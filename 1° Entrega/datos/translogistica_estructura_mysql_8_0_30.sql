SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `translogistica`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `translogistica`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `seguimientos`;
DROP TABLE IF EXISTS `notificaciones`;
DROP TABLE IF EXISTS `fichas`;
DROP TABLE IF EXISTS `depositos`;
DROP TABLE IF EXISTS `despachantes_locales`;
DROP TABLE IF EXISTS `usuarios_internos`;
DROP TABLE IF EXISTS `transportistas`;
DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `origen` enum('local', 'brasil', 'argentina') NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `usuario` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Solo hash',
  `estado` enum('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  KEY `idx_clientes_nombre` (`nombre`),
  KEY `idx_clientes_origen` (`origen`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `transportistas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contacto` varchar(255) DEFAULT NULL,
  `estado` enum('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_transportistas_nombre` (`nombre`),
  KEY `idx_transportistas_email` (`email`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `usuarios_internos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Solo hash',
  `rol` enum('administrativo', 'operativo', 'vendedor') NOT NULL,
  `estado` enum('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `depositos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ciudad` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `contacto` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_depositos_ciudad` (`ciudad`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `despachantes_locales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `gestor` varchar(255) DEFAULT NULL,
  `gestor_telefono` varchar(50) DEFAULT NULL,
  `contacto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_despachantes_locales_nombre` (`nombre`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `fichas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `num_ficha` varchar(50) DEFAULT NULL COMMENT 'Interno; no se muestra al cliente',
  `tipo_operacion` enum('importacion', 'exportacion') NOT NULL,
  `cliente_id` bigint unsigned NOT NULL,
  `transportista_id` bigint unsigned DEFAULT NULL,
  `vendedor_id` bigint unsigned DEFAULT NULL,
  `deposito_id` bigint unsigned DEFAULT NULL,
  `despachante_local_id` bigint unsigned DEFAULT NULL,
  `estado` enum(
    'iniciada',
    'en_curso',
    'en_frontera',
    'entregada',
    'anulada'
  ) NOT NULL DEFAULT 'iniciada',
  `num_crt` varchar(255) DEFAULT NULL,
  `cliente_nombre` varchar(255) DEFAULT NULL,
  `cliente_contacto` varchar(255) DEFAULT NULL,
  `cliente_telefono` varchar(50) DEFAULT NULL,
  `cliente_ruc_rut` varchar(50) DEFAULT NULL,
  `nombre_exportador` varchar(255) DEFAULT NULL,
  `contacto_exportador_nombre` varchar(255) DEFAULT NULL,
  `contacto_exportador_email` varchar(255) DEFAULT NULL,
  `telefono_exportador` varchar(50) DEFAULT NULL,
  `despachante_exportador` varchar(255) DEFAULT NULL,
  `mercaderia` varchar(255) DEFAULT NULL,
  `tipo_carga` enum('consolidada', 'completa') DEFAULT NULL,
  `peso_bruto` decimal(10, 2) DEFAULT NULL COMMENT 'kg',
  `volumen` decimal(10, 2) DEFAULT NULL COMMENT 'm3',
  `tipo_bulto` varchar(255) DEFAULT NULL,
  `cantidad_bultos` int unsigned DEFAULT NULL,
  `num_factura` varchar(255) DEFAULT NULL,
  `ciudad_carga` varchar(255) DEFAULT NULL,
  `frontera` varchar(255) DEFAULT NULL,
  `coleta` varchar(255) DEFAULT NULL,
  `dua` varchar(6) DEFAULT NULL COMMENT 'Máx. 6 dígitos',
  `hecha_por` varchar(255) DEFAULT NULL,
  `fecha_comercial` date DEFAULT NULL,
  `puesta_fob` decimal(12, 2) DEFAULT NULL,
  `flete_internacional` decimal(12, 2) DEFAULT NULL,
  `flete_nacional` decimal(12, 2) DEFAULT NULL,
  `ad_valorem_porcentaje` decimal(5, 2) DEFAULT NULL,
  `ad_valorem` decimal(12, 2) DEFAULT NULL,
  `despacho_brasil` decimal(12, 2) DEFAULT NULL,
  `total_comercial` decimal(12, 2) DEFAULT NULL,
  `fob_usd` decimal(12, 2) DEFAULT NULL,
  `proforma` varchar(255) DEFAULT NULL,
  `factura_definitiva` varchar(255) DEFAULT NULL,
  `cantidad_camiones` int unsigned DEFAULT NULL,
  `flete_por_camion` varchar(255) DEFAULT NULL,
  `observaciones_comerciales` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `num_ficha` (`num_ficha`),
  UNIQUE KEY `num_crt` (`num_crt`),
  KEY `idx_fichas_cliente` (`cliente_id`),
  KEY `idx_fichas_transportista` (`transportista_id`),
  KEY `idx_fichas_vendedor` (`vendedor_id`),
  KEY `idx_fichas_deposito` (`deposito_id`),
  KEY `idx_fichas_despachante_local` (`despachante_local_id`),
  KEY `idx_fichas_dua` (`dua`),
  KEY `idx_fichas_estado` (`estado`),
  KEY `idx_fichas_created` (`created_at`),
  CONSTRAINT `fk_fichas_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_fichas_transportista` FOREIGN KEY (`transportista_id`) REFERENCES `transportistas` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_fichas_vendedor` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios_internos` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_fichas_deposito` FOREIGN KEY (`deposito_id`) REFERENCES `depositos` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_fichas_despachante_local` FOREIGN KEY (`despachante_local_id`) REFERENCES `despachantes_locales` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `chk_fichas_dua` CHECK (
    (
      (`dua` is null)
      or regexp_like(`dua`, '^[0-9]{1,6}$')
    )
  )
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `notificaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ficha_id` bigint unsigned NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_notificaciones_ficha` (`ficha_id`),
  CONSTRAINT `fk_notificaciones_ficha` FOREIGN KEY (`ficha_id`) REFERENCES `fichas` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `seguimientos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ficha_id` bigint unsigned NOT NULL,
  `etapa` enum(
    'carga',
    'deposito',
    'frontera',
    'aduana_internacional',
    'aduana_nacional',
    'liberacion',
    'entrega'
  ) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `canal_aduanero` enum('verde', 'naranja', 'rojo') DEFAULT NULL,
  `observacion` text,
  `notificar_cliente` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_seguimientos_ficha_fecha` (`ficha_id`, `fecha`),
  CONSTRAINT `fk_seguimientos_ficha` FOREIGN KEY (`ficha_id`) REFERENCES `fichas` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `chk_seguimiento_con_datos` CHECK (
    (
      (`etapa` is not null)
      or (`fecha` is not null)
      or (`observacion` is not null)
    )
  ),
  CONSTRAINT `chk_seguimiento_etapa_fecha` CHECK (
    (
      (
        (`etapa` is null)
        and (`fecha` is null)
      )
      or (
        (`etapa` is not null)
        and (`fecha` is not null)
      )
    )
  ),
  CONSTRAINT `chk_seguimientos_canal` CHECK (
    (
      (`canal_aduanero` is null)
      or (
        `etapa` in (
          'aduana_internacional',
          'aduana_nacional'
        )
      )
    )
  )
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

DELIMITER $$

DROP TRIGGER IF EXISTS `trg_clientes_usuario_bi`$$

CREATE TRIGGER `trg_clientes_usuario_bi` BEFORE INSERT ON `clientes` FOR EACH ROW BEGIN
 IF EXISTS (SELECT 1 FROM usuarios_internos WHERE usuario = NEW.usuario) THEN
 SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario ya existe como usuario interno';
 END IF;
 END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS `trg_clientes_usuario_bu`$$

CREATE TRIGGER `trg_clientes_usuario_bu` BEFORE UPDATE ON `clientes` FOR EACH ROW BEGIN
 IF NEW.usuario <> OLD.usuario
 AND EXISTS (SELECT 1 FROM usuarios_internos WHERE usuario = NEW.usuario) THEN
 SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario ya existe como usuario interno';
 END IF;
 END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS `trg_internos_usuario_bi`$$

CREATE TRIGGER `trg_internos_usuario_bi` BEFORE INSERT ON `usuarios_internos` FOR EACH ROW BEGIN
 IF EXISTS (SELECT 1 FROM clientes WHERE usuario = NEW.usuario) THEN
 SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario ya existe como cliente';
 END IF;
 END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS `trg_internos_usuario_bu`$$

CREATE TRIGGER `trg_internos_usuario_bu` BEFORE UPDATE ON `usuarios_internos` FOR EACH ROW BEGIN
 IF NEW.usuario <> OLD.usuario
 AND EXISTS (SELECT 1 FROM clientes WHERE usuario = NEW.usuario) THEN
 SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario ya existe como cliente';
 END IF;
 END$$

DELIMITER ;
