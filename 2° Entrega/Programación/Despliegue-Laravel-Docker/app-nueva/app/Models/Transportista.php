<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transportista extends Model
{
    use HasFactory;

    protected $table = 'transportistas';

    protected $fillable = [
        'nombre',
        'email',
        'contacto',
        'estado',
    ];

    public function fichas(): HasMany
    {
        return $this->hasMany(Ficha::class);
    }
}
