<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'ficha_id',
        'tipo_documento',
        'numero',
        'archivo_path',
    ];

    public function ficha(): BelongsTo
    {
        return $this->belongsTo(Ficha::class);
    }
}
