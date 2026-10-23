<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despachante extends Model
{
    use HasFactory;

    protected $table = 'despachantes';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion',
        'gestor',
        'gestor_telefono',
        'contacto',
        'estado',
    ];
}
