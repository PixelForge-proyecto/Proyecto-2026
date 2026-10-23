<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Ficha extends Model
{
    use HasFactory;

    protected $table = 'fichas';

    protected $fillable = [
        'num_ficha',
        'tipo_operacion',
        'cliente_id',
        'cliente_internacional_id',
        'transportista_id',
        'vendedor_id',
        'estado',
        'num_crt',

        'cliente_nombre',
        'cliente_contacto',
        'cliente_telefono',
        'cliente_ruc_rut',

        'nombre_exportador',
        'contacto_exportador_nombre',
        'contacto_exportador_email',
        'telefono_exportador',
        'despachante_exportador',

        'mercaderia',
        'tipo_carga',
        'peso_bruto',
        'volumen',
        'tipo_bulto',
        'cantidad_bultos',
        'num_factura',

        'ciudad_carga',
        'frontera',
        'coleta',

        'ciudad_descarga',
        'direccion_descarga',
        'telefono_descarga',
        'contacto_descarga',
        'email_descarga',

        'despachante_local_nombre',
        'despachante_local_email',
        'despachante_local_telefono',
        'despachante_local_direccion',
        'despachante_local_gestor',
        'despachante_local_gestor_telefono',
        'despachante_local_contacto',

        'dua',

        'hecha_por',
        'fecha_comercial',
        'puesta_fob',
        'flete_internacional',
        'flete_nacional',
        'ad_valorem_porcentaje',
        'ad_valorem',
        'despacho_brasil',
        'total_comercial',
        'fob_usd',
        'proforma',
        'factura_definitiva',
        'cantidad_camiones',
        'flete_por_camion',
        'observaciones_comerciales',
    ];

    protected function casts(): array
    {
        return [
            'peso_bruto' => 'decimal:2',
            'volumen' => 'decimal:2',
            'cantidad_bultos' => 'integer',

            'fecha_comercial' => 'date',
            'puesta_fob' => 'decimal:2',
            'flete_internacional' => 'decimal:2',
            'flete_nacional' => 'decimal:2',
            'ad_valorem_porcentaje' => 'decimal:2',
            'ad_valorem' => 'decimal:2',
            'despacho_brasil' => 'decimal:2',
            'total_comercial' => 'decimal:2',
            'fob_usd' => 'decimal:2',
            'cantidad_camiones' => 'integer',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /*
     * Cliente internacional asociado a la ficha.
     * Puede corresponder a Brasil o Argentina.
     */
    public function clienteInternacional(): BelongsTo
    {
        return $this->belongsTo(
            Cliente::class,
            'cliente_internacional_id'
        );
    }

    public function transportista(): BelongsTo
    {
        return $this->belongsTo(Transportista::class);
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(
            UsuarioInterno::class,
            'vendedor_id'
        );
    }

    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class);
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class);
    }

    /*
     * Devuelve el paso fronterizo completo, independientemente
     * de si en la ficha se registró la ciudad uruguaya
     * o la ciudad brasileña.
     */
    public function getFronteraCompletaAttribute(): string
    {
        if (blank($this->frontera)) {
            return 'Sin especificar';
        }

        $fronteraNormalizada = Str::of($this->frontera)
            ->ascii()
            ->lower()
            ->squish()
            ->value();

        if (
            str_contains($fronteraNormalizada, 'rivera')
            || str_contains(
                $fronteraNormalizada,
                'santana do livramento'
            )
        ) {
            return 'Rivera / Santana do Livramento';
        }

        if (
            str_contains($fronteraNormalizada, 'rio branco')
            || str_contains(
                $fronteraNormalizada,
                'jaguarao'
            )
        ) {
            return 'Río Branco / Jaguarão';
        }

        if (
            str_contains($fronteraNormalizada, 'chuy')
            || str_contains(
                $fronteraNormalizada,
                'chui'
            )
        ) {
            return 'Chuy / Chuí';
        }

        /*
         * Si se registra otra frontera que todavía no está
         * contemplada, se muestra exactamente como fue guardada.
         */
        return trim($this->frontera);
    }
}
