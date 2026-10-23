<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Ficha;
use App\Models\Seguimiento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InicioController extends Controller
{
    public function index(): View
    {
        $cantidadCargas = $this->fichasDelCliente()
            ->count();

        return view(
            'cliente.inicio',
            compact('cantidadCargas')
        );
    }

    public function cargas(): View
    {
        $cargas = $this->fichasDelCliente()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view(
            'cliente.cargas.index',
            compact('cargas')
        );
    }

    public function buscar(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'dua' => [
                    'required',
                    'string',
                    'regex:/^\d{1,6}$/',
                ],
            ],
            [
                'dua.required' => 'Ingresá un número de DUA.',

                'dua.string' => 'El número de DUA debe ser válido.',

                'dua.regex' => 'El DUA debe contener solamente números y un máximo de 6 dígitos.',
            ]
        );

        $dua = trim($validated['dua']);

        /*
         * La consulta restringe las fichas al cliente autenticado
         * para impedir el acceso a cargas ajenas.
         */
        $carga = $this->fichasDelCliente()
            ->where('dua', $dua)
            ->first();

        if ($carga === null) {
            return back()
                ->withInput()
                ->withErrors([
                    'dua' => 'No se encontró una carga asociada a ese número de DUA.',
                ]);
        }

        return redirect()->route(
            'cliente.cargas.show',
            $carga->id
        );
    }

    public function show(
        string $carga
    ): View {
        /*
         * No utilizamos una búsqueda únicamente por ID.
         * También se exige que la ficha pertenezca al cliente
         * autenticado para evitar acceso a cargas ajenas.
         */
        $ficha = $this->fichasDelCliente()
            ->where('id', $carga)
            ->firstOrFail();

        $seguimientos = $ficha
            ->seguimientos()
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        $cargaSeguimiento = $this->prepararCarga(
            $ficha,
            $seguimientos
        );

        return view(
            'cliente.carga',
            [
                'carga' => $cargaSeguimiento,
            ]
        );
    }

    private function fichasDelCliente(): Builder
    {
        $cliente = Auth::guard('cliente')->user();

        abort_unless($cliente !== null && $cliente->estado === 'activo', 403);

        $columna = match ($cliente->origen) {
            'local' => 'cliente_id',
            'brasil', 'argentina' => 'cliente_internacional_id',
            default => null,
        };

        abort_if($columna === null, 403);

        return Ficha::query()
            ->where($columna, $cliente->id)
            ->where('estado', '!=', 'anulada');
    }

    private function prepararCarga(
        Ficha $ficha,
        Collection $seguimientos
    ): array {
        $seguimientoCarga = $this->seguimientoDeEtapa(
            $seguimientos,
            'carga'
        );

        $seguimientoDeposito = $this->seguimientoDeEtapa(
            $seguimientos,
            'deposito'
        );

        $seguimientoFrontera = $this->seguimientoDeEtapa(
            $seguimientos,
            'frontera'
        );

        $seguimientoAduanaInternacional =
            $this->seguimientoDeEtapa(
                $seguimientos,
                'aduana_internacional'
            );

        $seguimientoAduanaNacional =
            $this->seguimientoDeEtapa(
                $seguimientos,
                'aduana_nacional'
            );

        $seguimientoLiberacion = $this->seguimientoDeEtapa(
            $seguimientos,
            'liberacion'
        );

        $seguimientoEntrega = $this->seguimientoDeEtapa(
            $seguimientos,
            'entrega'
        );

        /*
         * Se preparan todas las observaciones autorizadas para
         * el cliente. Se ordenan desde la más antigua hasta la
         * más reciente para formar un historial cronológico.
         */
        $observaciones = $seguimientos
            ->filter(
                fn (Seguimiento $seguimiento) => $seguimiento->notificar_cliente
                    && filled($seguimiento->observacion)
            )
            ->sortBy(
                fn (Seguimiento $seguimiento) => (
                    $seguimiento->fecha?->format(
                        'Y-m-d H:i:s'
                    )
                    ?? $seguimiento->created_at?->format(
                        'Y-m-d H:i:s'
                    )
                    ?? '0000-00-00 00:00:00'
                )
                    .'-'
                    .str_pad(
                        (string) $seguimiento->id,
                        20,
                        '0',
                        STR_PAD_LEFT
                    )
            )
            ->map(
                fn (Seguimiento $seguimiento) => [
                    'fecha' => $seguimiento->fecha?->format(
                        'd/m/Y'
                    )
                        ?? $seguimiento->created_at?->format(
                            'd/m/Y'
                        )
                        ?? 'Sin fecha',

                    'etapa' => $this->nombreEtapa(
                        $seguimiento->etapa
                    ),

                    'texto' => $seguimiento->observacion,
                ]
            )
            ->values()
            ->all();

        return [
            /*
             * Deliberadamente no se incluye num_ficha.
             */
            'id' => $ficha->id,

            'crt' => $ficha->num_crt
                ?? 'Sin especificar',

            'dua' => $ficha->dua
                ?? 'Sin especificar',

            'operacion' => $this->nombreOperacion(
                $ficha->tipo_operacion
            ),

            'estado' => $this->nombreEstado(
                $ficha->estado
            ),

            'origen' => $ficha->ciudad_carga
                ?? 'Sin especificar',

            'destino' => $ficha->ciudad_descarga
                ?? 'Sin especificar',

            /*
             * Se muestra el paso fronterizo completo sin
             * modificar el valor original de la ficha.
             */
            'frontera' => $ficha->frontera_completa,

            'fecha_carga' => $this->formatearFecha(
                $seguimientoCarga
            ),

            'fecha_deposito' => $this->formatearFecha(
                $seguimientoDeposito
            ),

            'fecha_frontera' => $this->formatearFecha(
                $seguimientoFrontera
            ),

            'fecha_aduana_internacional' => $this->formatearFecha(
                $seguimientoAduanaInternacional
            ),

            'canal_aduana_internacional' => $seguimientoAduanaInternacional
                ?->canal_aduanero,

            'fecha_aduana_nacional' => $this->formatearFecha(
                $seguimientoAduanaNacional
            ),

            'canal_aduana_nacional' => $seguimientoAduanaNacional
                ?->canal_aduanero,

            'fecha_liberacion' => $this->formatearFecha(
                $seguimientoLiberacion
            ),

            'fecha_entrega' => $this->formatearFecha(
                $seguimientoEntrega
            ),

            /*
             * Historial completo de observaciones autorizadas.
             */
            'observaciones' => $observaciones,

            /*
             * Se conserva esta clave para mantener compatibilidad
             * con cualquier sección que todavía utilice la última
             * observación notificada.
             */
            'observacion' => ! empty($observaciones)
                ? $observaciones[array_key_last($observaciones)]['texto']
                : null,
        ];
    }

    private function seguimientoDeEtapa(
        Collection $seguimientos,
        string $etapa
    ): ?Seguimiento {
        return $seguimientos->first(
            fn (Seguimiento $seguimiento) => $seguimiento->etapa === $etapa
                && $seguimiento->fecha !== null
        );
    }

    private function formatearFecha(
        ?Seguimiento $seguimiento
    ): ?string {
        return $seguimiento
            ?->fecha
            ?->format('d/m/Y');
    }

    private function nombreEtapa(
        ?string $etapa
    ): string {
        return match ($etapa) {
            'carga' => 'Carga',

            'deposito' => 'Depósito',

            'frontera' => 'Frontera',

            'aduana_internacional' => 'Aduana internacional',

            'aduana_nacional' => 'Aduana nacional',

            'liberacion' => 'Liberación',

            'entrega' => 'Entrega',

            default => 'Observación general',
        };
    }

    private function nombreOperacion(
        ?string $operacion
    ): string {
        return match ($operacion) {
            'importacion' => 'Importación',

            'exportacion' => 'Exportación',

            default => 'Sin especificar',
        };
    }

    private function nombreEstado(
        ?string $estado
    ): string {
        return match ($estado) {
            'iniciada' => 'Iniciada',

            'en_curso' => 'En curso',

            'en_frontera' => 'En frontera',

            'entregada' => 'Entregada',

            default => 'Sin especificar',
        };
    }
}
