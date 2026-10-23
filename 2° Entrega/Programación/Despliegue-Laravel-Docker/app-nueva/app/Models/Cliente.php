<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cliente extends Authenticatable
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'ruc_rut',
        'origen',
        'direccion',
        'telefono',
        'contacto_nombre',
        'contacto_email',
        'despachante',
        'usuario',
        'password',
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
            'cliente_id'
        );
    }

    public function fichasComoInternacional(): HasMany
    {
        return $this->hasMany(
            Ficha::class,
            'cliente_internacional_id'
        );
    }

    public function esNacional(): bool
    {
        return $this->origen === 'local';
    }

    public function esInternacional(): bool
    {
        return in_array(
            $this->origen,
            [
                'brasil',
                'argentina',
            ],
            true
        );
    }
}
