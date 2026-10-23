<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsuarioInterno extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios_internos';

    protected $fillable = [
        'nombre',
        'usuario',
        'password',
        'rol',
        'estado',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function fichas(): HasMany
    {
        return $this->hasMany(
            Ficha::class,
            'vendedor_id'
        );
    }
}
