SET NAMES utf8mb4;

USE `translogistica`;

SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `clientes` (
    `id`,
    `nombre`,
    `origen`,
    `direccion`,
    `usuario`,
    `password`,
    `estado`,
    `created_at`,
    `updated_at`
)
VALUES
  (
    1,
    'Cliente de prueba',
    'local',
    'Montevideo',
    'cliente.prueba',
    '$2y$12$YdkuONbBQ5Fuw.rkB8NSaO7L4k7Mc4syHC5Nb/D1qXAlViTvFdK9S',
    'activo',
    '2026-10-16 00:34:33',
    '2026-10-18 21:01:21'
  ),
  (
    2,
    'Cliente de prueba',
    'brasil',
    'Floripa, Brasil',
    'cliente',
    '$2y$12$OT628fsaIHbxEdOAyxUoTeC5tX6Zicwxh3Ky9HYuV94ABP/VCqtMO',
    'inactivo',
    '2026-10-17 00:12:44',
    '2026-10-18 20:49:39'
  );

INSERT INTO `transportistas` (
    `id`,
    `nombre`,
    `email`,
    `contacto`,
    `estado`,
    `created_at`,
    `updated_at`
)
VALUES
  (
    1,
    'Transportes de prueba',
    'transporte@prueba.com',
    '099 123 454',
    'inactivo',
    '2026-10-18 20:30:16',
    '2026-10-18 20:36:49'
  ),
  (
    2,
    'Transportes1',
    'transportes1@prueba.com',
    '098 674 299',
    'inactivo',
    '2026-10-18 20:34:00',
    '2026-10-18 20:36:53'
  ),
  (
    3,
    'Transportista 1',
    'Transportista@gmail.com',
    '098 765 433',
    'activo',
    '2026-10-18 21:01:56',
    '2026-10-19 20:05:57'
  );

INSERT INTO `usuarios_internos` (
    `id`,
    `nombre`,
    `usuario`,
    `password`,
    `rol`,
    `estado`,
    `created_at`,
    `updated_at`
)
VALUES
  (
    1,
    'Administrador interno',
    'interno',
    '$2y$12$X.krhQLE2JjOFnZ5IoMTN.QCtr3f5XgZ4bv.hjOpViifGWj8/jIYy',
    'administrativo',
    'activo',
    '2026-10-16 23:40:45',
    '2026-10-16 23:40:45'
  );

INSERT INTO `depositos` (
    `id`, `ciudad`, `direccion`, `telefono`, `contacto`, `email`, `created_at`, `updated_at`
)
VALUES
  (
    1, 'Montevideo', 'Ruta 5 km 15', '29001234', 'Juan Pérez', 'deposito@cliente.com',
    '2026-10-18 21:04:58', '2026-10-18 21:04:58'
  );

INSERT INTO `despachantes_locales` (
    `id`, `nombre`, `email`, `telefono`, `direccion`, `gestor`, `gestor_telefono`, `contacto`,
    `created_at`, `updated_at`
)
VALUES
  (
    1, 'Despachante Local S.A.', 'contacto@despachantelocal.com', '29005678',
    'Av. Uruguay 1234', 'Carlos Rodríguez', '098765432', 'Oficina de operaciones',
    '2026-10-18 21:04:58', '2026-10-18 21:04:58'
  );

INSERT INTO `fichas` (
    `id`, `num_ficha`, `tipo_operacion`, `cliente_id`, `transportista_id`, `vendedor_id`,
    `deposito_id`, `despachante_local_id`, `estado`, `num_crt`,
    `cliente_nombre`, `cliente_contacto`, `cliente_telefono`, `cliente_ruc_rut`,
    `nombre_exportador`, `contacto_exportador_nombre`, `contacto_exportador_email`,
    `telefono_exportador`, `despachante_exportador`,
    `mercaderia`, `tipo_carga`, `peso_bruto`, `volumen`, `tipo_bulto`, `cantidad_bultos`,
    `num_factura`, `ciudad_carga`, `frontera`, `coleta`,
    `dua`, `hecha_por`, `fecha_comercial`, `puesta_fob`, `flete_internacional`,
    `flete_nacional`, `ad_valorem_porcentaje`, `ad_valorem`, `despacho_brasil`,
    `total_comercial`, `fob_usd`, `proforma`, `factura_definitiva`, `cantidad_camiones`,
    `flete_por_camion`, `observaciones_comerciales`, `created_at`, `updated_at`
)
VALUES
  (
    1, 'F-0001', 'importacion', 1, 3, 1,
    1, 1, 'entregada', 'CRT-0001',
    'Cliente de prueba', 'Federico', '099123456', '123456789012',
    'Exportadora Brasil Ltda.', 'María Silva', 'maria@exportadora.com',
    '551199999999', 'Despachante Brasil',
    'Productos veterinarios y farmacéuticos', 'completa', 1300.00, 8.50, 'Cajas', 25,
    'FAC-0001', 'São Paulo', 'Rivera', 'Depósito central',
    '123456', NULL, NULL, NULL, NULL,
    NULL, NULL, NULL, NULL,
    NULL, NULL, NULL, NULL, NULL,
    NULL, NULL, '2026-10-18 21:04:58', '2026-10-22 01:54:04'
  );

INSERT INTO `seguimientos` (
    `id`,
    `ficha_id`,
    `etapa`,
    `fecha`,
    `canal_aduanero`,
    `observacion`,
    `notificar_cliente`,
    `created_at`,
    `updated_at`
)
VALUES
  (
    2,
    1,
    'frontera',
    '2026-10-22',
    NULL,
    'El vehículo llegó a frontera',
    1,
    '2026-10-20 13:10:18',
    '2026-10-22 01:27:58'
  ),
  (
    3,
    1,
    'carga',
    '2026-10-20',
    NULL,
    'La mercadería fue cargada correctamente',
    1,
    '2026-10-21 23:04:23',
    '2026-10-22 01:27:32'
  ),
  (
    4,
    1,
    'aduana_internacional',
    '2026-10-20',
    'verde',
    NULL,
    0,
    '2026-10-21 23:08:17',
    '2026-10-21 23:08:17'
  ),
  (
    5,
    1,
    'aduana_nacional',
    '2026-10-20',
    'verde',
    NULL,
    0,
    '2026-10-21 23:08:31',
    '2026-10-21 23:08:31'
  ),
  (
    6,
    1,
    'liberacion',
    '2026-10-22',
    NULL,
    NULL,
    0,
    '2026-10-21 23:12:22',
    '2026-10-21 23:12:22'
  ),
  (
    7,
    1,
    'aduana_internacional',
    '2026-10-22',
    NULL,
    NULL,
    0,
    '2026-10-21 23:12:39',
    '2026-10-21 23:12:39'
  ),
  (
    8,
    1,
    'aduana_nacional',
    '2026-10-22',
    NULL,
    NULL,
    0,
    '2026-10-21 23:12:54',
    '2026-10-21 23:12:54'
  ),
  (
    9,
    1,
    'liberacion',
    '2026-10-22',
    NULL,
    NULL,
    0,
    '2026-10-21 23:13:15',
    '2026-10-21 23:13:46'
  ),
  (
    10,
    1,
    'entrega',
    '2026-10-22',
    NULL,
    NULL,
    0,
    '2026-10-21 23:14:04',
    '2026-10-21 23:14:04'
  ),
  (
    11,
    1,
    'aduana_internacional',
    '2026-10-22',
    'verde',
    NULL,
    0,
    '2026-10-21 23:20:17',
    '2026-10-21 23:20:17'
  ),
  (
    12,
    1,
    'aduana_nacional',
    '2026-10-22',
    'verde',
    NULL,
    0,
    '2026-10-21 23:20:35',
    '2026-10-21 23:20:35'
  );

SET FOREIGN_KEY_CHECKS = 1;
