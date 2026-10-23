<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Ficha;
use App\Models\Seguimiento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SeguimientoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim(
            (string) $request->query('buscar', '')
        );

        $etapa = (string) $request->query(
            'etapa',
            ''
        );

        $canal = (string) $request->query(
            'canal',
            ''
        );

        /*
         * Se muestra únicamente el seguimiento completo
         * más reciente de cada ficha.
         */
        $seguimientos = Seguimiento::query()
            ->with('ficha.cliente')
            ->whereNotNull('etapa')
            ->whereNotNull('fecha')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get()
            ->unique('ficha_id')
            ->values();

        if ($buscar !== '') {
            $textoBuscado = mb_strtolower($buscar);

            $seguimientos = $seguimientos
                ->filter(
                    function ($seguimiento) use ($textoBuscado) {
                        $ficha = $seguimiento->ficha;

                        $valores = [
                            $ficha?->num_ficha,
                            $ficha?->num_crt,
                            $ficha?->dua,
                            $ficha?->cliente?->nombre,
                        ];

                        foreach ($valores as $valor) {
                            if (
                                $valor !== null
                                && str_contains(
                                    mb_strtolower(
                                        (string) $valor
                                    ),
                                    $textoBuscado
                                )
                            ) {
                                return true;
                            }
                        }

                        return false;
                    }
                )
                ->values();
        }

        if ($etapa !== '') {
            $seguimientos = $seguimientos
                ->where('etapa', $etapa)
                ->values();
        }

        if ($canal !== '') {
            $seguimientos = $seguimientos
                ->where('canal_aduanero', $canal)
                ->values();
        }

        return view(
            'panel.seguimientos.index',
            compact(
                'seguimientos',
                'buscar',
                'etapa',
                'canal'
            )
        );
    }

    public function create(): View
    {
        $fichas = Ficha::query()
            ->with('cliente')
            ->where('estado', '!=', 'anulada')
            ->orderByDesc('id')
            ->get();

        return view(
            'panel.seguimientos.create',
            compact('fichas')
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $this->validarSeguimiento(
            $request,
            true
        );

        $observacion = $this->normalizarObservacion(
            $validated['observacion'] ?? null
        );

        DB::transaction(
            function () use (
                $request,
                $validated,
                $observacion
            ) {
                $etapa = $validated['etapa'] ?? null;

                $seguimiento = Seguimiento::create([
                    'ficha_id' => $validated['ficha_id'],

                    'etapa' => $etapa,

                    'fecha' => $validated['fecha'] ?? null,

                    'canal_aduanero' => $this->permiteCanalAduanero(
                        $etapa
                    )
                        ? ($validated['canal_aduanero'] ?? null)
                        : null,

                    'observacion' => $observacion,

                    'notificar_cliente' => $request->boolean(
                        'notificar_cliente'
                    ),
                ]);

                $this->recalcularEstadoFicha(
                    $seguimiento->ficha
                );
            }
        );

        return redirect()
            ->route('panel.seguimientos.index')
            ->with(
                'status',
                'El seguimiento fue registrado correctamente.'
            );
    }

    public function show(
        Seguimiento $seguimiento
    ): View {
        $seguimiento->load([
            'ficha.cliente',
            'ficha.seguimientos',
        ]);

        $datos = $this->prepararDatos(
            $seguimiento
        );

        return view(
            'panel.seguimientos.show',
            compact(
                'seguimiento',
                'datos'
            )
        );
    }

    public function edit(
        Seguimiento $seguimiento
    ): View {
        $seguimiento->load('ficha.cliente');

        $datos = $this->prepararDatos(
            $seguimiento
        );

        /*
         * Se obtienen todos los avances de la ficha para que,
         * al seleccionar una etapa en el formulario, puedan
         * cargarse su fecha, canal y observación.
         */
        $registrosFicha = $seguimiento
            ->ficha
            ->seguimientos()
            ->whereNotNull('etapa')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        $datosEtapas = [];

        foreach (
            [
                'carga',
                'deposito',
                'frontera',
                'aduana_internacional',
                'aduana_nacional',
                'liberacion',
                'entrega',
            ] as $valorEtapa
        ) {
            $registroEtapa = $registrosFicha->first(
                fn (Seguimiento $registro) => $registro->etapa === $valorEtapa
            );

            if ($registroEtapa === null) {
                continue;
            }

            $datosEtapas[$valorEtapa] = [
                'fecha' => $registroEtapa->fecha?->format(
                    'Y-m-d'
                ),

                'canal_aduanero' => $registroEtapa->canal_aduanero,

                'observacion' => $registroEtapa->observacion,

                'notificar_cliente' => (bool) $registroEtapa->notificar_cliente,
            ];
        }

        return view(
            'panel.seguimientos.edit',
            compact(
                'seguimiento',
                'datos',
                'datosEtapas'
            )
        );
    }

    public function update(
        Request $request,
        Seguimiento $seguimiento
    ): RedirectResponse {
        $validated = $this->validarSeguimiento(
            $request
        );

        $observacion = $this->normalizarObservacion(
            $validated['observacion'] ?? null
        );

        $etapaNueva = $validated['etapa'] ?? null;

        $resultado = DB::transaction(
            function () use (
                $request,
                $validated,
                $observacion,
                $seguimiento,
                $etapaNueva
            ): array {
                $datosSeguimiento = [
                    'etapa' => $etapaNueva,

                    'fecha' => $validated['fecha'] ?? null,

                    'canal_aduanero' => $this->permiteCanalAduanero(
                        $etapaNueva
                    )
                        ? ($validated['canal_aduanero'] ?? null)
                        : null,

                    'observacion' => $observacion,

                    'notificar_cliente' => $request->boolean(
                        'notificar_cliente'
                    ),
                ];

                $seguimientoGuardado = null;
                $accion = 'actualizado';

                /*
                 * Si la etapa seleccionada ya está registrada
                 * para esta ficha, se actualiza ese registro.
                 */
                if ($etapaNueva !== null) {
                    $seguimientoGuardado = $seguimiento
                        ->ficha
                        ->seguimientos()
                        ->where('etapa', $etapaNueva)
                        ->orderByDesc('fecha')
                        ->orderByDesc('id')
                        ->first();
                }

                if ($seguimientoGuardado !== null) {
                    $seguimientoGuardado->update(
                        $datosSeguimiento
                    );
                } elseif (
                    $seguimiento->etapa === null
                    || $seguimiento->etapa === $etapaNueva
                ) {
                    /*
                     * Si el registro abierto todavía no tenía
                     * una etapa o continúa siendo la misma,
                     * se actualiza directamente.
                     */
                    $seguimiento->update(
                        $datosSeguimiento
                    );

                    $seguimientoGuardado =
                        $seguimiento->fresh();
                } else {
                    /*
                     * Si se seleccionó una etapa que todavía no
                     * existe, se crea un registro nuevo y se
                     * conserva todo el historial anterior.
                     */
                    $seguimientoGuardado =
                        Seguimiento::create([
                            'ficha_id' => $seguimiento->ficha_id,

                            ...$datosSeguimiento,
                        ]);

                    $accion = 'creado';
                }

                $this->recalcularEstadoFicha(
                    $seguimientoGuardado->ficha
                );

                return [
                    'seguimiento' => $seguimientoGuardado,

                    'accion' => $accion,
                ];
            }
        );

        $mensaje = $resultado['accion'] === 'creado'
            ? 'El nuevo avance fue registrado correctamente y se conservó el historial anterior.'
            : 'El seguimiento fue actualizado correctamente.';

        return redirect()
            ->route(
                'panel.seguimientos.show',
                $resultado['seguimiento']
            )
            ->with(
                'status',
                $mensaje
            );
    }

    public function destroy(
        Seguimiento $seguimiento
    ): RedirectResponse {
        DB::transaction(
            function () use ($seguimiento) {
                /*
                 * La ficha se guarda antes de eliminar el
                 * seguimiento para poder recalcular su estado.
                 */
                $ficha = $seguimiento->ficha;

                $seguimiento->delete();

                $this->recalcularEstadoFicha(
                    $ficha
                );
            }
        );

        return redirect()
            ->route('panel.seguimientos.index')
            ->with(
                'status',
                'El seguimiento fue eliminado correctamente.'
            );
    }

    private function validarSeguimiento(
        Request $request,
        bool $incluirFicha = false
    ): array {
        $reglas = [
            'etapa' => [
                'nullable',
                'string',
                Rule::in([
                    'carga',
                    'deposito',
                    'frontera',
                    'aduana_internacional',
                    'aduana_nacional',
                    'liberacion',
                    'entrega',
                ]),
                'required_with:fecha',
            ],

            'fecha' => [
                'nullable',
                'date',
                'required_with:etapa',
            ],

            'canal_aduanero' => [
                'nullable',
                'string',
                Rule::in([
                    'verde',
                    'naranja',
                    'rojo',
                ]),
            ],

            'observacion' => [
                'nullable',
                'string',
                'max:2000',
                'required_without_all:etapa,fecha',
            ],

            'notificar_cliente' => [
                'nullable',
                'boolean',
            ],
        ];

        if ($incluirFicha) {
            $reglas = [
                'ficha_id' => [
                    'required',
                    'integer',
                    Rule::exists('fichas', 'id')
                        ->where(
                            fn ($query) => $query->where(
                                'estado',
                                '!=',
                                'anulada'
                            )
                        ),
                ],
                ...$reglas,
            ];
        }

        $validator = Validator::make(
            $request->all(),
            $reglas,
            [
                'ficha_id.required' => 'Seleccioná una ficha.',

                'ficha_id.integer' => 'La ficha seleccionada no es válida.',

                'ficha_id.exists' => 'La ficha seleccionada no existe o está anulada.',

                'etapa.in' => 'La etapa seleccionada no es válida.',

                'etapa.required_with' => 'Debés seleccionar una etapa cuando ingresás una fecha.',

                'fecha.date' => 'La fecha ingresada no es válida.',

                'fecha.required_with' => 'Debés ingresar una fecha cuando seleccionás una etapa.',

                'canal_aduanero.in' => 'El canal aduanero seleccionado no es válido.',

                'observacion.max' => 'La observación no puede superar los 2000 caracteres.',

                'observacion.required_without_all' => 'Ingresá una etapa con fecha o una observación.',

                'notificar_cliente.boolean' => 'La opción de notificación no es válida.',
            ]
        );

        $validator->after(
            function ($validator) use ($request) {
                if (
                    $request->filled('canal_aduanero')
                    && ! $this->permiteCanalAduanero(
                        $request->input('etapa')
                    )
                ) {
                    $validator->errors()->add(
                        'canal_aduanero',
                        'El canal aduanero solo puede indicarse en Aduana internacional o Aduana nacional.'
                    );
                }
            }
        );

        return $validator->validate();
    }

    private function permiteCanalAduanero(
        ?string $etapa
    ): bool {
        return in_array(
            $etapa,
            [
                'aduana_internacional',
                'aduana_nacional',
            ],
            true
        );
    }

    private function normalizarObservacion(
        ?string $observacion
    ): ?string {
        if ($observacion === null) {
            return null;
        }

        $observacion = trim($observacion);

        return $observacion === ''
            ? null
            : $observacion;
    }

    private function recalcularEstadoFicha(
        Ficha $ficha
    ): void {
        /*
         * Una ficha anulada no puede cambiar automáticamente
         * de estado.
         */
        if ($ficha->estado === 'anulada') {
            return;
        }

        /*
         * Se utiliza el seguimiento completo más reciente.
         * Si hay un empate de fechas, se usa el ID más alto.
         */
        $ultimoSeguimiento = $ficha
            ->seguimientos()
            ->whereNotNull('etapa')
            ->whereNotNull('fecha')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->first();

        if ($ultimoSeguimiento === null) {
            $ficha->update([
                'estado' => 'iniciada',
            ]);

            return;
        }

        $estado = match ($ultimoSeguimiento->etapa) {
            'carga',
            'deposito',
            'liberacion' => 'en_curso',

            'frontera',
            'aduana_internacional',
            'aduana_nacional' => 'en_frontera',

            'entrega' => 'entregada',

            default => 'iniciada',
        };

        $ficha->update([
            'estado' => $estado,
        ]);
    }

    private function prepararDatos(
        Seguimiento $seguimiento
    ): array {
        $ficha = $seguimiento->ficha;

        return [
            'numero' => $ficha->num_ficha
                ?? (string) $ficha->id,

            'cliente' => $ficha->cliente?->nombre
                ?? 'Sin especificar',

            'crt' => $ficha->num_crt
                ?? 'Sin especificar',

            'operacion' => $this->nombreOperacion(
                $ficha->tipo_operacion
            ),

            'estado' => $this->nombreEstado(
                $ficha->estado
            ),

            'etapa_valor' => $seguimiento->etapa,

            'fecha' => $seguimiento->fecha?->format(
                'Y-m-d'
            ),

            'canal_aduanero' => $seguimiento->canal_aduanero,

            'observacion' => $seguimiento->observacion,

            'notificar_cliente' => (bool) $seguimiento->notificar_cliente,
        ];
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
            'anulada' => 'Anulada',
            default => 'Sin especificar',
        };
    }
}
