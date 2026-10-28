-- Translogistica v12: instalacion desde cero con baja logica de fichas.
-- Este script BORRA y RECREA las tablas de negocio.

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `translogistica`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `translogistica`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `documentos`;
DROP TABLE IF EXISTS `seguimientos`;
DROP TABLE IF EXISTS `notificaciones`;
DROP TABLE IF EXISTS `fichas`;
DROP TABLE IF EXISTS `depositos`;
DROP TABLE IF EXISTS `despachantes`;
DROP TABLE IF EXISTS `despachantes_locales`;
DROP TABLE IF EXISTS `usuarios_internos`;
DROP TABLE IF EXISTS `transportistas`;
DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `ruc_rut` varchar(50) DEFAULT NULL COMMENT 'Solo clientes locales',
  `origen` enum('local', 'brasil', 'argentina') NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `contacto_nombre` varchar(255) DEFAULT NULL,
  `contacto_email` varchar(255) DEFAULT NULL,
  `despachante` varchar(255) DEFAULT NULL COMMENT 'Solo clientes internacionales',
  `usuario` varchar(255) NOT NULL COMMENT 'Un unico usuario por empresa',
  `password` varchar(255) NOT NULL COMMENT 'Solo hash',
  `estado` enum('activo', 'inactivo') NOT NULL DEFAULT 'activo' COMMENT 'inactivo = cliente dado de baja; no se elimina para conservar las fichas',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  KEY `idx_clientes_nombre` (`nombre`),
  KEY `idx_clientes_origen` (`origen`),
  KEY `idx_clientes_ruc_rut` (`ruc_rut`)
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

CREATE TABLE `despachantes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `gestor` varchar(255) DEFAULT NULL,
  `gestor_telefono` varchar(50) DEFAULT NULL,
  `contacto` varchar(255) DEFAULT NULL,
  `estado` enum('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_despachantes_nombre` (`nombre`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `fichas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `num_ficha` varchar(50) DEFAULT NULL COMMENT 'Interno; no se muestra al cliente',
  `tipo_operacion` enum('importacion', 'exportacion') NOT NULL,
  `cliente_id` bigint unsigned NOT NULL,
  `cliente_internacional_id` bigint unsigned DEFAULT NULL COMMENT 'Cliente de Brasil o Argentina',
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
  `ciudad_descarga` varchar(255) DEFAULT NULL,
  `direccion_descarga` varchar(255) DEFAULT NULL,
  `telefono_descarga` varchar(50) DEFAULT NULL,
  `contacto_descarga` varchar(255) DEFAULT NULL,
  `email_descarga` varchar(255) DEFAULT NULL,
  `despachante_local_nombre` varchar(255) DEFAULT NULL,
  `despachante_local_email` varchar(255) DEFAULT NULL,
  `despachante_local_telefono` varchar(50) DEFAULT NULL,
  `despachante_local_direccion` varchar(255) DEFAULT NULL,
  `despachante_local_gestor` varchar(255) DEFAULT NULL,
  `despachante_local_gestor_telefono` varchar(50) DEFAULT NULL,
  `despachante_local_contacto` varchar(255) DEFAULT NULL,
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
  `cliente_internacional_nombre` varchar(255) DEFAULT NULL COMMENT 'Copia congelada al pasar a entregada',
  `cliente_internacional_origen` enum('brasil', 'argentina') DEFAULT NULL COMMENT 'Copia congelada al pasar a entregada',
  `transportista_nombre` varchar(255) DEFAULT NULL COMMENT 'Copia congelada al pasar a entregada',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'Baja logica; conserva ficha, CRT e historial',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `num_ficha` (`num_ficha`),
  UNIQUE KEY `num_crt` (`num_crt`),
  KEY `idx_fichas_cliente` (`cliente_id`),
  KEY `idx_fichas_cliente_internacional` (`cliente_internacional_id`),
  KEY `idx_fichas_transportista` (`transportista_id`),
  KEY `idx_fichas_vendedor` (`vendedor_id`),
  KEY `idx_fichas_deposito` (`deposito_id`),
  KEY `idx_fichas_despachante_local` (`despachante_local_id`),
  KEY `idx_fichas_dua` (`dua`),
  KEY `idx_fichas_estado` (`estado`),
  KEY `idx_fichas_created` (`created_at`),
  KEY `idx_fichas_deleted_at` (`deleted_at`),
  CONSTRAINT `fk_fichas_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_fichas_cliente_internacional` FOREIGN KEY (`cliente_internacional_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_fichas_transportista` FOREIGN KEY (`transportista_id`) REFERENCES `transportistas` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_fichas_vendedor` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios_internos` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_fichas_deposito` FOREIGN KEY (`deposito_id`) REFERENCES `depositos` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_fichas_despachante_local` FOREIGN KEY (`despachante_local_id`) REFERENCES `despachantes` (`id`) ON DELETE RESTRICT,
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
        `etapa` is not null
        and `etapa` in (
          'aduana_internacional',
          'aduana_nacional'
        )
      )
    )
  )
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `documentos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ficha_id` bigint unsigned NOT NULL,
  `tipo_documento` varchar(255) NOT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `archivo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_documentos_ficha` (`ficha_id`),
  CONSTRAINT `fk_documentos_ficha` FOREIGN KEY (`ficha_id`) REFERENCES `fichas` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Registro de migraciones de Laravel: estas 4 ya estan reflejadas en este esquema,
-- asi `php artisan migrate` no intenta volver a aplicarlas.
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT m.`migration`, 1 FROM (
  SELECT '2026_10_22_224223_adapt_clientes_for_national_and_international_clients' AS `migration`
  UNION ALL SELECT '2026_10_22_224634_add_cliente_internacional_id_to_fichas_table'
  UNION ALL SELECT '2026_10_23_011603_add_despachante_to_clientes_table'
  UNION ALL SELECT '2026_10_23_124036_create_despachantes_table'
) m
WHERE NOT EXISTS (SELECT 1 FROM `migrations` x WHERE x.`migration` = m.`migration`);

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

-- =====================================================================
-- CIERRE DE FICHAS ENTREGADAS (protección en la base de datos)
-- Regla de negocio:
--   * Una ficha abierta (iniciada / en_curso / en_frontera) se puede modificar.
--   * Cuando una ficha pasa a 'entregada' queda cerrada definitivamente:
--     no se puede modificar, anular ni reabrir; se permite su baja logica.
--     Tampoco se pueden
--     crear, editar ni borrar sus seguimientos.
--   * La transición a 'entregada' sí está permitida (es la que cierra la
--     ficha) y en ese momento se congelan los datos que hoy dependen de
--     registros vivos (cliente, cliente internacional, transportista,
--     despachante local).
-- Estos triggers cubren también las escrituras directas que hace Laravel
-- (Eloquent), que no pasan por los procedimientos almacenados.
-- Las notificaciones y los documentos se conservan.
-- Laravel debe filtrar las fichas con deleted_at IS NOT NULL.
-- No se permite borrar fisicamente fichas ni reutilizar sus numeros.
-- =====================================================================
DELIMITER $$

DROP TRIGGER IF EXISTS `trg_fichas_cierre_bu`$$

CREATE TRIGGER `trg_fichas_cierre_bu` BEFORE UPDATE ON `fichas` FOR EACH ROW BEGIN
  -- Una baja conserva el registro y no permite cambios posteriores.
  IF OLD.deleted_at IS NOT NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'La ficha esta dada de baja y no admite modificaciones';
  END IF;

  -- En una ficha entregada solo se permite la primera baja logica.
  -- Una baja, incluso de una ficha abierta, no puede alterar otros datos.
  -- updated_at puede cambiar junto con deleted_at para admitir SoftDeletes.
  IF OLD.estado = 'entregada' OR NEW.deleted_at IS NOT NULL THEN
    IF NEW.deleted_at IS NULL OR (
      NOT (CAST(NEW.`id` AS BINARY) <=> CAST(OLD.`id` AS BINARY))
      OR
      NOT (CAST(NEW.`num_ficha` AS BINARY) <=> CAST(OLD.`num_ficha` AS BINARY))
      OR
      NOT (CAST(NEW.`tipo_operacion` AS BINARY) <=> CAST(OLD.`tipo_operacion` AS BINARY))
      OR
      NOT (CAST(NEW.`cliente_id` AS BINARY) <=> CAST(OLD.`cliente_id` AS BINARY))
      OR
      NOT (CAST(NEW.`cliente_internacional_id` AS BINARY) <=> CAST(OLD.`cliente_internacional_id` AS BINARY))
      OR
      NOT (CAST(NEW.`transportista_id` AS BINARY) <=> CAST(OLD.`transportista_id` AS BINARY))
      OR
      NOT (CAST(NEW.`vendedor_id` AS BINARY) <=> CAST(OLD.`vendedor_id` AS BINARY))
      OR
      NOT (CAST(NEW.`deposito_id` AS BINARY) <=> CAST(OLD.`deposito_id` AS BINARY))
      OR
      NOT (CAST(NEW.`despachante_local_id` AS BINARY) <=> CAST(OLD.`despachante_local_id` AS BINARY))
      OR
      NOT (CAST(NEW.`estado` AS BINARY) <=> CAST(OLD.`estado` AS BINARY))
      OR
      NOT (CAST(NEW.`num_crt` AS BINARY) <=> CAST(OLD.`num_crt` AS BINARY))
      OR
      NOT (CAST(NEW.`cliente_nombre` AS BINARY) <=> CAST(OLD.`cliente_nombre` AS BINARY))
      OR
      NOT (CAST(NEW.`cliente_contacto` AS BINARY) <=> CAST(OLD.`cliente_contacto` AS BINARY))
      OR
      NOT (CAST(NEW.`cliente_telefono` AS BINARY) <=> CAST(OLD.`cliente_telefono` AS BINARY))
      OR
      NOT (CAST(NEW.`cliente_ruc_rut` AS BINARY) <=> CAST(OLD.`cliente_ruc_rut` AS BINARY))
      OR
      NOT (CAST(NEW.`nombre_exportador` AS BINARY) <=> CAST(OLD.`nombre_exportador` AS BINARY))
      OR
      NOT (CAST(NEW.`contacto_exportador_nombre` AS BINARY) <=> CAST(OLD.`contacto_exportador_nombre` AS BINARY))
      OR
      NOT (CAST(NEW.`contacto_exportador_email` AS BINARY) <=> CAST(OLD.`contacto_exportador_email` AS BINARY))
      OR
      NOT (CAST(NEW.`telefono_exportador` AS BINARY) <=> CAST(OLD.`telefono_exportador` AS BINARY))
      OR
      NOT (CAST(NEW.`despachante_exportador` AS BINARY) <=> CAST(OLD.`despachante_exportador` AS BINARY))
      OR
      NOT (CAST(NEW.`mercaderia` AS BINARY) <=> CAST(OLD.`mercaderia` AS BINARY))
      OR
      NOT (CAST(NEW.`tipo_carga` AS BINARY) <=> CAST(OLD.`tipo_carga` AS BINARY))
      OR
      NOT (CAST(NEW.`peso_bruto` AS BINARY) <=> CAST(OLD.`peso_bruto` AS BINARY))
      OR
      NOT (CAST(NEW.`volumen` AS BINARY) <=> CAST(OLD.`volumen` AS BINARY))
      OR
      NOT (CAST(NEW.`tipo_bulto` AS BINARY) <=> CAST(OLD.`tipo_bulto` AS BINARY))
      OR
      NOT (CAST(NEW.`cantidad_bultos` AS BINARY) <=> CAST(OLD.`cantidad_bultos` AS BINARY))
      OR
      NOT (CAST(NEW.`num_factura` AS BINARY) <=> CAST(OLD.`num_factura` AS BINARY))
      OR
      NOT (CAST(NEW.`ciudad_carga` AS BINARY) <=> CAST(OLD.`ciudad_carga` AS BINARY))
      OR
      NOT (CAST(NEW.`frontera` AS BINARY) <=> CAST(OLD.`frontera` AS BINARY))
      OR
      NOT (CAST(NEW.`coleta` AS BINARY) <=> CAST(OLD.`coleta` AS BINARY))
      OR
      NOT (CAST(NEW.`ciudad_descarga` AS BINARY) <=> CAST(OLD.`ciudad_descarga` AS BINARY))
      OR
      NOT (CAST(NEW.`direccion_descarga` AS BINARY) <=> CAST(OLD.`direccion_descarga` AS BINARY))
      OR
      NOT (CAST(NEW.`telefono_descarga` AS BINARY) <=> CAST(OLD.`telefono_descarga` AS BINARY))
      OR
      NOT (CAST(NEW.`contacto_descarga` AS BINARY) <=> CAST(OLD.`contacto_descarga` AS BINARY))
      OR
      NOT (CAST(NEW.`email_descarga` AS BINARY) <=> CAST(OLD.`email_descarga` AS BINARY))
      OR
      NOT (CAST(NEW.`despachante_local_nombre` AS BINARY) <=> CAST(OLD.`despachante_local_nombre` AS BINARY))
      OR
      NOT (CAST(NEW.`despachante_local_email` AS BINARY) <=> CAST(OLD.`despachante_local_email` AS BINARY))
      OR
      NOT (CAST(NEW.`despachante_local_telefono` AS BINARY) <=> CAST(OLD.`despachante_local_telefono` AS BINARY))
      OR
      NOT (CAST(NEW.`despachante_local_direccion` AS BINARY) <=> CAST(OLD.`despachante_local_direccion` AS BINARY))
      OR
      NOT (CAST(NEW.`despachante_local_gestor` AS BINARY) <=> CAST(OLD.`despachante_local_gestor` AS BINARY))
      OR
      NOT (CAST(NEW.`despachante_local_gestor_telefono` AS BINARY) <=> CAST(OLD.`despachante_local_gestor_telefono` AS BINARY))
      OR
      NOT (CAST(NEW.`despachante_local_contacto` AS BINARY) <=> CAST(OLD.`despachante_local_contacto` AS BINARY))
      OR
      NOT (CAST(NEW.`dua` AS BINARY) <=> CAST(OLD.`dua` AS BINARY))
      OR
      NOT (CAST(NEW.`hecha_por` AS BINARY) <=> CAST(OLD.`hecha_por` AS BINARY))
      OR
      NOT (CAST(NEW.`fecha_comercial` AS BINARY) <=> CAST(OLD.`fecha_comercial` AS BINARY))
      OR
      NOT (CAST(NEW.`puesta_fob` AS BINARY) <=> CAST(OLD.`puesta_fob` AS BINARY))
      OR
      NOT (CAST(NEW.`flete_internacional` AS BINARY) <=> CAST(OLD.`flete_internacional` AS BINARY))
      OR
      NOT (CAST(NEW.`flete_nacional` AS BINARY) <=> CAST(OLD.`flete_nacional` AS BINARY))
      OR
      NOT (CAST(NEW.`ad_valorem_porcentaje` AS BINARY) <=> CAST(OLD.`ad_valorem_porcentaje` AS BINARY))
      OR
      NOT (CAST(NEW.`ad_valorem` AS BINARY) <=> CAST(OLD.`ad_valorem` AS BINARY))
      OR
      NOT (CAST(NEW.`despacho_brasil` AS BINARY) <=> CAST(OLD.`despacho_brasil` AS BINARY))
      OR
      NOT (CAST(NEW.`total_comercial` AS BINARY) <=> CAST(OLD.`total_comercial` AS BINARY))
      OR
      NOT (CAST(NEW.`fob_usd` AS BINARY) <=> CAST(OLD.`fob_usd` AS BINARY))
      OR
      NOT (CAST(NEW.`proforma` AS BINARY) <=> CAST(OLD.`proforma` AS BINARY))
      OR
      NOT (CAST(NEW.`factura_definitiva` AS BINARY) <=> CAST(OLD.`factura_definitiva` AS BINARY))
      OR
      NOT (CAST(NEW.`cantidad_camiones` AS BINARY) <=> CAST(OLD.`cantidad_camiones` AS BINARY))
      OR
      NOT (CAST(NEW.`flete_por_camion` AS BINARY) <=> CAST(OLD.`flete_por_camion` AS BINARY))
      OR
      NOT (CAST(NEW.`observaciones_comerciales` AS BINARY) <=> CAST(OLD.`observaciones_comerciales` AS BINARY))
      OR
      NOT (CAST(NEW.`cliente_internacional_nombre` AS BINARY) <=> CAST(OLD.`cliente_internacional_nombre` AS BINARY))
      OR
      NOT (CAST(NEW.`cliente_internacional_origen` AS BINARY) <=> CAST(OLD.`cliente_internacional_origen` AS BINARY))
      OR
      NOT (CAST(NEW.`transportista_nombre` AS BINARY) <=> CAST(OLD.`transportista_nombre` AS BINARY))
      OR
      NOT (CAST(NEW.`created_at` AS BINARY) <=> CAST(OLD.`created_at` AS BINARY))
    ) THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Solo se permite la baja logica sin cambiar los datos de la ficha';
    END IF;
  END IF;

  -- Transicion a 'entregada': se congela la informacion historica.
  IF OLD.estado <> 'entregada' AND NEW.estado = 'entregada' AND NEW.deleted_at IS NULL THEN
    -- Cliente local: se respeta lo que ya este cargado en la ficha y solo
    -- se completan los datos vacios con los del cliente actual.
    IF NEW.cliente_id IS NOT NULL THEN
      SET NEW.cliente_nombre = COALESCE(NEW.cliente_nombre,
            (SELECT c.nombre FROM clientes c WHERE c.id = NEW.cliente_id));
      SET NEW.cliente_contacto = COALESCE(NEW.cliente_contacto,
            (SELECT c.contacto_nombre FROM clientes c WHERE c.id = NEW.cliente_id));
      SET NEW.cliente_telefono = COALESCE(NEW.cliente_telefono,
            (SELECT c.telefono FROM clientes c WHERE c.id = NEW.cliente_id));
      SET NEW.cliente_ruc_rut = COALESCE(NEW.cliente_ruc_rut,
            (SELECT c.ruc_rut FROM clientes c WHERE c.id = NEW.cliente_id));
    END IF;

    -- Cliente internacional: copia tomada en el momento del cierre.
    IF NEW.cliente_internacional_id IS NOT NULL THEN
      SET NEW.cliente_internacional_nombre =
            (SELECT c.nombre FROM clientes c WHERE c.id = NEW.cliente_internacional_id);
      SET NEW.cliente_internacional_origen =
            (SELECT c.origen FROM clientes c WHERE c.id = NEW.cliente_internacional_id);
    END IF;

    -- Transportista: copia tomada en el momento del cierre.
    IF NEW.transportista_id IS NOT NULL THEN
      SET NEW.transportista_nombre =
            (SELECT t.nombre FROM transportistas t WHERE t.id = NEW.transportista_id);
    END IF;

    -- Despachante local (solo si la ficha lo referencia por id): se completan
    -- los datos de texto vacios con los del despachante actual.
    IF NEW.despachante_local_id IS NOT NULL THEN
      SET NEW.despachante_local_nombre = COALESCE(NEW.despachante_local_nombre,
            (SELECT d.nombre FROM despachantes d WHERE d.id = NEW.despachante_local_id));
      SET NEW.despachante_local_email = COALESCE(NEW.despachante_local_email,
            (SELECT d.email FROM despachantes d WHERE d.id = NEW.despachante_local_id));
      SET NEW.despachante_local_telefono = COALESCE(NEW.despachante_local_telefono,
            (SELECT d.telefono FROM despachantes d WHERE d.id = NEW.despachante_local_id));
      SET NEW.despachante_local_direccion = COALESCE(NEW.despachante_local_direccion,
            (SELECT d.direccion FROM despachantes d WHERE d.id = NEW.despachante_local_id));
      SET NEW.despachante_local_gestor = COALESCE(NEW.despachante_local_gestor,
            (SELECT d.gestor FROM despachantes d WHERE d.id = NEW.despachante_local_id));
      SET NEW.despachante_local_gestor_telefono = COALESCE(NEW.despachante_local_gestor_telefono,
            (SELECT d.gestor_telefono FROM despachantes d WHERE d.id = NEW.despachante_local_id));
      SET NEW.despachante_local_contacto = COALESCE(NEW.despachante_local_contacto,
            (SELECT d.contacto FROM despachantes d WHERE d.id = NEW.despachante_local_id));
    END IF;
  END IF;
END$$

DROP TRIGGER IF EXISTS `trg_fichas_cierre_bd`$$

CREATE TRIGGER `trg_fichas_cierre_bd` BEFORE DELETE ON `fichas` FOR EACH ROW BEGIN
  -- No se borra fisicamente ninguna ficha: sus numeros siguen reservados.
  SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Las fichas solo admiten baja logica; no se pueden borrar fisicamente';
END$$

-- Seguimientos: la lectura del estado de la ficha usa FOR SHARE para que, si
-- otra transaccion esta cerrando la ficha en este momento, esta espere y vea
-- el estado definitivo (evita la carrera entre "cerrar" y "agregar").
DROP TRIGGER IF EXISTS `trg_seguimientos_cierre_bi`$$

CREATE TRIGGER `trg_seguimientos_cierre_bi` BEFORE INSERT ON `seguimientos` FOR EACH ROW BEGIN
  DECLARE v_estado VARCHAR(20) DEFAULT NULL;
  DECLARE v_deleted_at TIMESTAMP DEFAULT NULL;

  SELECT f.estado, f.deleted_at INTO v_estado, v_deleted_at FROM fichas f WHERE f.id = NEW.ficha_id FOR SHARE;

  IF v_estado = 'entregada' OR v_deleted_at IS NOT NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'La ficha esta entregada o dada de baja: no admite nuevos seguimientos';
  END IF;
END$$

DROP TRIGGER IF EXISTS `trg_seguimientos_cierre_bu`$$

CREATE TRIGGER `trg_seguimientos_cierre_bu` BEFORE UPDATE ON `seguimientos` FOR EACH ROW BEGIN
  DECLARE v_estado VARCHAR(20) DEFAULT NULL;
  DECLARE v_deleted_at TIMESTAMP DEFAULT NULL;

  SELECT f.estado, f.deleted_at INTO v_estado, v_deleted_at FROM fichas f WHERE f.id = OLD.ficha_id FOR SHARE;

  IF v_estado = 'entregada' OR v_deleted_at IS NOT NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'La ficha esta entregada o dada de baja: sus seguimientos no se pueden modificar';
  END IF;

  -- Si el seguimiento se quisiera mover a otra ficha, esa tampoco puede estar cerrada.
  IF NEW.ficha_id <> OLD.ficha_id THEN
    SET v_estado = NULL;
    SET v_deleted_at = NULL;
    SELECT f.estado, f.deleted_at INTO v_estado, v_deleted_at FROM fichas f WHERE f.id = NEW.ficha_id FOR SHARE;

    IF v_estado = 'entregada' OR v_deleted_at IS NOT NULL THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La ficha de destino esta entregada o dada de baja: no admite nuevos seguimientos';
    END IF;
  END IF;
END$$

DROP TRIGGER IF EXISTS `trg_seguimientos_cierre_bd`$$

CREATE TRIGGER `trg_seguimientos_cierre_bd` BEFORE DELETE ON `seguimientos` FOR EACH ROW BEGIN
  DECLARE v_estado VARCHAR(20) DEFAULT NULL;
  DECLARE v_deleted_at TIMESTAMP DEFAULT NULL;

  SELECT f.estado, f.deleted_at INTO v_estado, v_deleted_at FROM fichas f WHERE f.id = OLD.ficha_id FOR SHARE;

  IF v_estado = 'entregada' OR v_deleted_at IS NOT NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'La ficha esta entregada o dada de baja: sus seguimientos no se pueden eliminar';
  END IF;
END$$

DELIMITER ;


-- =====================================================================
-- PROCEDIMIENTOS ALMACENADOS
-- Convención de nombres (las Query_rules del docker-compose enrutan por
-- este prefijo):
--   esc_  -> escritura: modifican datos (INSERT / UPDATE)
--   lec_  -> lectura: solo consultan (SELECT)
-- =====================================================================
DELIMITER $$

-- ---------------------------------------------------------------------
-- 1) esc_recalcular_estado_ficha
--    Regla de negocio central: el estado de la ficha sale de la etapa
--    del último seguimiento completo (etapa + fecha; ante empate de
--    fecha, el de id más alto).
--      carga / deposito / liberacion                  -> en_curso
--      frontera / aduana_internacional / aduana_nacional -> en_frontera
--      entrega                                         -> entregada
--      sin seguimientos completos                      -> iniciada
--    Las fichas ENTREGADAS (cerradas) y ANULADAS conservan su estado:
--    el procedimiento no las modifica.
--    Bloquea la fila de la ficha (FOR UPDATE) mientras recalcula.
--    Es la misma lógica que SeguimientoController::recalcularEstadoFicha,
--    Tambien conserva sin cambios las fichas dadas de baja.
-- ---------------------------------------------------------------------
DROP PROCEDURE IF EXISTS `esc_recalcular_estado_ficha`$$

CREATE PROCEDURE `esc_recalcular_estado_ficha`(
  IN p_ficha_id BIGINT UNSIGNED
)
BEGIN
  DECLARE v_estado_actual VARCHAR(20) DEFAULT NULL;
  DECLARE v_deleted_at TIMESTAMP DEFAULT NULL;
  DECLARE v_etapa VARCHAR(30) DEFAULT NULL;
  DECLARE v_estado_nuevo VARCHAR(20);

  SELECT f.estado, f.deleted_at INTO v_estado_actual, v_deleted_at
    FROM fichas f
   WHERE f.id = p_ficha_id
     FOR UPDATE;

  IF v_estado_actual IS NULL THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La ficha no existe';
  END IF;

  IF v_deleted_at IS NULL AND v_estado_actual NOT IN ('entregada', 'anulada') THEN
    SELECT s.etapa INTO v_etapa
      FROM seguimientos s
     WHERE s.ficha_id = p_ficha_id
       AND s.etapa IS NOT NULL
       AND s.fecha IS NOT NULL
     ORDER BY s.fecha DESC, s.id DESC
     LIMIT 1;

    SET v_estado_nuevo = CASE
      WHEN v_etapa IN ('carga', 'deposito', 'liberacion') THEN 'en_curso'
      WHEN v_etapa IN ('frontera', 'aduana_internacional', 'aduana_nacional') THEN 'en_frontera'
      WHEN v_etapa = 'entrega' THEN 'entregada'
      ELSE 'iniciada'
    END;

    IF v_estado_nuevo <> v_estado_actual THEN
      UPDATE fichas
         SET estado = v_estado_nuevo,
             updated_at = UTC_TIMESTAMP()
       WHERE id = p_ficha_id;
    END IF;
  END IF;
END$$

-- ---------------------------------------------------------------------
-- 2) esc_registrar_seguimiento
--    Alta de un avance de la ficha en una sola operación atómica:
--      - abre la transacción y bloquea la ficha (FOR UPDATE), de modo que
--        el estado se valida sobre el dato vigente y nadie lo cambia
--        mientras se registra el seguimiento,
--      - rechaza fichas inexistentes, dadas de baja, anuladas o entregadas,
--      - valida los datos,
--      - inserta el seguimiento (el canal aduanero solo se guarda en
--        aduana internacional / nacional),
--      - recalcula el estado de la ficha (si el seguimiento es una
--        'entrega', la ficha pasa a 'entregada' y queda cerrada),
--      - si se pidió notificar al cliente, deja una notificación.
--    Devuelve el id del seguimiento en p_seguimiento_id.
--    Nota: START TRANSACTION confirma implícitamente cualquier transacción
--    abierta por el llamador; llamarlo fuera de DB::transaction.
-- ---------------------------------------------------------------------
DROP PROCEDURE IF EXISTS `esc_registrar_seguimiento`$$

CREATE PROCEDURE `esc_registrar_seguimiento`(
  IN  p_ficha_id          BIGINT UNSIGNED,
  IN  p_etapa             VARCHAR(30),
  IN  p_fecha             DATE,
  IN  p_canal_aduanero    VARCHAR(10),
  IN  p_observacion       TEXT,
  IN  p_notificar_cliente TINYINT(1),
  OUT p_seguimiento_id    BIGINT UNSIGNED
)
BEGIN
  DECLARE v_estado_ficha VARCHAR(20) DEFAULT NULL;
  DECLARE v_deleted_at TIMESTAMP DEFAULT NULL;
  DECLARE v_num_ficha    VARCHAR(50) DEFAULT NULL;
  DECLARE v_etapa        VARCHAR(30);
  DECLARE v_canal        VARCHAR(10);
  DECLARE v_observacion  TEXT;
  DECLARE v_notificar    TINYINT(1);

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    RESIGNAL;
  END;

  SET p_seguimiento_id = NULL;
  SET v_etapa        = NULLIF(TRIM(p_etapa), '');
  SET v_observacion  = NULLIF(TRIM(p_observacion), '');
  SET v_notificar    = IF(COALESCE(p_notificar_cliente, 0) <> 0, 1, 0);

  START TRANSACTION;

  -- Se bloquea la ficha y se lee su estado dentro de la transaccion.
  SELECT f.estado, f.num_ficha, f.deleted_at INTO v_estado_ficha, v_num_ficha, v_deleted_at
    FROM fichas f
   WHERE f.id = p_ficha_id
     FOR UPDATE;

  IF v_estado_ficha IS NULL THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La ficha no existe';
  END IF;

  IF v_deleted_at IS NOT NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'No se pueden registrar seguimientos en una ficha dada de baja';
  END IF;
  IF v_estado_ficha = 'anulada' THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'No se pueden registrar seguimientos en una ficha anulada';
  END IF;

  IF v_estado_ficha = 'entregada' THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'No se pueden registrar seguimientos en una ficha entregada (cerrada)';
  END IF;

  IF (v_etapa IS NULL) <> (p_fecha IS NULL) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'La etapa y la fecha deben indicarse juntas';
  END IF;

  IF v_etapa IS NULL AND v_observacion IS NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Indique una etapa con fecha o una observacion';
  END IF;

  IF v_etapa IN ('aduana_internacional', 'aduana_nacional') THEN
    SET v_canal = NULLIF(TRIM(p_canal_aduanero), '');
  ELSE
    SET v_canal = NULL;
  END IF;

  INSERT INTO seguimientos
    (ficha_id, etapa, fecha, canal_aduanero, observacion,
     notificar_cliente, created_at, updated_at)
  VALUES
    (p_ficha_id, v_etapa, p_fecha, v_canal, v_observacion,
     v_notificar, UTC_TIMESTAMP(), UTC_TIMESTAMP());

  SET p_seguimiento_id = LAST_INSERT_ID();

  CALL esc_recalcular_estado_ficha(p_ficha_id);

  IF v_notificar = 1 AND v_etapa IS NOT NULL THEN
    INSERT INTO notificaciones
      (ficha_id, tipo, mensaje, leida, created_at, updated_at)
    VALUES
      (p_ficha_id,
       'seguimiento',
       CONCAT('Ficha ',
              COALESCE(v_num_ficha, CAST(p_ficha_id AS CHAR)),
              ': ', REPLACE(v_etapa, '_', ' '),
              ' (', DATE_FORMAT(p_fecha, '%d/%m/%Y'), ')'),
       0, UTC_TIMESTAMP(), UTC_TIMESTAMP());
  END IF;

  COMMIT;
END$$

-- ---------------------------------------------------------------------
-- 3) lec_resumen_dashboard
--    Los cuatro indicadores del panel principal en una sola consulta
--    (misma lógica que DashboardController): clientes activos, total de
--    fichas, transportistas activos y entregas del mes en curso.
-- ---------------------------------------------------------------------
DROP PROCEDURE IF EXISTS `lec_resumen_dashboard`$$

CREATE PROCEDURE `lec_resumen_dashboard`()
BEGIN
  SELECT
    (SELECT COUNT(*) FROM clientes WHERE estado = 'activo') AS clientes_activos,
    (SELECT COUNT(*) FROM fichas WHERE deleted_at IS NULL) AS total_fichas,
    (SELECT COUNT(*) FROM transportistas WHERE estado = 'activo') AS transportistas_activos,
    (SELECT COUNT(*)
       FROM fichas f
      WHERE f.deleted_at IS NULL
        AND f.estado = 'entregada'
        AND EXISTS (
              SELECT 1
                FROM seguimientos s
               WHERE s.ficha_id = f.id
                 AND s.etapa = 'entrega'
                 AND YEAR(s.fecha) = YEAR(CURDATE())
                 AND MONTH(s.fecha) = MONTH(CURDATE())
            )
    ) AS entregas_mes;
END$$

DELIMITER ;

-- Ejemplos de uso:
--   CALL esc_registrar_seguimiento(1, 'frontera', CURDATE(), NULL, 'Llegó a frontera', 1, @nuevo_id);
--   SELECT @nuevo_id;
--   CALL esc_recalcular_estado_ficha(1);
--   CALL lec_resumen_dashboard();
