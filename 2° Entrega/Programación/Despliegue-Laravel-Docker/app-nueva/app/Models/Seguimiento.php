<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seguimiento extends Model
{
    use HasFactory;

    protected $table = 'seguimientos';

    protected $fillable = [
        'ficha_id',
        'etapa',
        'fecha',
        'canal_aduanero',
        'observacion',
        'notificar_cliente',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'notificar_cliente' => 'boolean',
        ];
    }

    public function ficha(): BelongsTo
    {
        return $this->belongsTo(Ficha::class);
    }
}
