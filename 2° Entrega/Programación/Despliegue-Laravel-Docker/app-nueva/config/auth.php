<?php

use App\Models\Cliente;
use App\Models\UsuarioInterno;

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración predeterminada
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => 'interno',
        'passwords' => 'usuarios_internos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Guards de autenticación
    |--------------------------------------------------------------------------
    |
    | Cada guard mantiene una sesión independiente.
    |
    */

    'guards' => [
        'interno' => [
            'driver' => 'session',
            'provider' => 'usuarios_internos',
        ],

        'cliente' => [
            'driver' => 'session',
            'provider' => 'clientes',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Proveedores de usuarios
    |--------------------------------------------------------------------------
    |
    | Indican qué modelo debe consultar cada guard.
    |
    */

    'providers' => [
        'usuarios_internos' => [
            'driver' => 'eloquent',
            'model' => UsuarioInterno::class,
        ],

        'clientes' => [
            'driver' => 'eloquent',
            'model' => Cliente::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Recuperación de contraseñas
    |--------------------------------------------------------------------------
    |
    | La recuperación todavía no está implementada en la interfaz, pero se
    | dejan definidos los proveedores para una futura implementación.
    |
    */

    'passwords' => [
        'usuarios_internos' => [
            'provider' => 'usuarios_internos',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'clientes' => [
            'provider' => 'clientes',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tiempo de confirmación de contraseña
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];
