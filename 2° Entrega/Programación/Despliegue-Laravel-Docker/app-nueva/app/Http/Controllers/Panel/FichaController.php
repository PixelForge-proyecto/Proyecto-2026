<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Despachante;
use App\Models\Ficha;
use App\Models\Transportista;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FichaController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim(
            (string) $request->query('buscar', '')
        );

        $fichas = Ficha::query()
            ->with([
                'cliente',
                'clienteInternacional',
                'transportista',
                'vendedor',
            ])
            ->where('estado', '!=', 'anulada')
            ->when(
                $buscar !== '',
                function ($query) use ($buscar) {
                    $query->where(
                        function ($subquery) use ($buscar) {
                            $subquery
                                ->where(
                                    'num_ficha',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'num_crt',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'dua',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'cliente_nombre',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhereHas(
                                    'cliente',
                                    function ($clienteQuery) use ($buscar) {
                                        $clienteQuery->where(
                                            'nombre',
                                            'like',
                                            "%{$buscar}%"
                                        );
                                    }
                                )
                                ->orWhereHas(
                                    'clienteInternacional',
                                    function ($clienteQuery) use ($buscar) {
                                        $clienteQuery->where(
                                            'nombre',
                                            'like',
                                            "%{$buscar}%"
                                        );
                                    }
                                );
                        }
                    );
                }
            )
            ->orderByDesc('created_at')
            ->get();

        return view(
            'panel.fichas.index',
            compact('fichas')
        );
    }

    public function create(): View
    {
        $clientes = Cliente::query()
            ->where('estado', 'activo')
            ->where('origen', 'local')
            ->orderBy('nombre')
            ->get();

        $clientesInternacionales = Cliente::query()
            ->where('estado', 'activo')
            ->whereIn('origen', ['brasil', 'argentina'])
            ->orderBy('nombre')
            ->get();

        $transportistas = Transportista::query()
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $despachantes = Despachante::query()
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        return view(
            'panel.fichas.create',
            compact(
                'clientes',
                'clientesInternacionales',
                'transportistas',
                'despachantes'
            )
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $this->validateFicha($request);

        $cliente = Cliente::query()
            ->where('estado', 'activo')
            ->where('origen', 'local')
            ->findOrFail($validated['cliente_id']);

        $data = $this->prepareData(
            $validated,
            $cliente
        );

        $data['estado'] = 'iniciada';
        $data['vendedor_id'] =
            Auth::guard('interno')->id();

        Ficha::create($data);

        return redirect()
            ->route('panel.fichas.index')
            ->with(
                'status',
                'La ficha fue registrada correctamente.'
            );
    }

    public function show(Ficha $ficha): View
    {
        $this->ensureNotCancelled($ficha);

        $ficha->load([
            'cliente',
            'clienteInternacional',
            'transportista',
            'vendedor',
            'seguimientos',
        ]);

        return view(
            'panel.fichas.show',
            compact('ficha')
        );
    }

    public function edit(Ficha $ficha): View
    {
        $this->ensureNotCancelled($ficha);

        $clientes = Cliente::query()
            ->where('estado', 'activo')
            ->where('origen', 'local')
            ->orderBy('nombre')
            ->get();

        $clientesInternacionales = Cliente::query()
            ->where('estado', 'activo')
            ->whereIn('origen', ['brasil', 'argentina'])
            ->orderBy('nombre')
            ->get();

        $transportistas = Transportista::query()
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $despachantes = Despachante::query()
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        return view(
            'panel.fichas.edit',
            compact(
                'ficha',
                'clientes',
                'clientesInternacionales',
                'transportistas',
                'despachantes'
            )
        );
    }

    public function update(
        Request $request,
        Ficha $ficha
    ): RedirectResponse {
        $this->ensureNotCancelled($ficha);

        $validated = $this->validateFicha(
            $request,
            $ficha
        );

        $cliente = Cliente::query()
            ->where('estado', 'activo')
            ->where('origen', 'local')
            ->findOrFail($validated['cliente_id']);

        $ficha->update(
            $this->prepareData(
                $validated,
                $cliente
            )
        );

        return redirect()
            ->route('panel.fichas.index')
            ->with(
                'status',
                'La ficha fue actualizada correctamente.'
            );
    }

    public function destroy(
        Ficha $ficha
    ): RedirectResponse {
        $this->ensureNotCancelled($ficha);

        $numero = $ficha->num_ficha
            ?: (string) $ficha->id;

        $ficha->update([
            'estado' => 'anulada',
        ]);

        return redirect()
            ->route('panel.fichas.index')
            ->with(
                'status',
                "La ficha {$numero} fue anulada correctamente."
            );
    }

    private function validateFicha(
        Request $request,
        ?Ficha $ficha = null
    ): array {
        return $request->validate(
            [
                'tipo_operacion' => [
                    'required',
                    'string',
                    Rule::in([
                        'importacion',
                        'exportacion',
                    ]),
                ],

                'numero_ficha' => [
                    'nullable',
                    'string',
                    'max:50',
                    Rule::unique(
                        'fichas',
                        'num_ficha'
                    )->ignore($ficha?->id),
                ],

                'cliente_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'clientes',
                        'id'
                    )->where(
                        fn ($query) => $query
                            ->where('estado', 'activo')
                            ->where('origen', 'local')
                    ),
                ],

                'cliente_internacional_id' => [
                    'required',
                    'integer',
                    Rule::exists('clientes', 'id')->where(
                        fn ($query) => $query
                            ->where('estado', 'activo')
                            ->whereIn('origen', ['brasil', 'argentina'])
                    ),
                ],

                'transportista' => [
                    'nullable',
                    'integer',
                    Rule::exists(
                        'transportistas',
                        'id'
                    )->where(
                        fn ($query) => $query->where(
                            'estado',
                            'activo'
                        )
                    ),
                ],

                'num_crt' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique(
                        'fichas',
                        'num_crt'
                    )->ignore($ficha?->id),
                ],

                'cliente_nombre' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'cliente_contacto' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'cliente_telefono' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'cliente_ruc' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'exportador_nombre' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'exportador_contacto' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'exportador_email' => [
                    'nullable',
                    'email',
                    'max:255',
                ],
                'exportador_telefono' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'despachante_exportador' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'mercaderia' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'peso' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'volumen' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'num_factura' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'cantidad_bultos' => [
                    'nullable',
                    'integer',
                    'min:0',
                ],
                'tipo_bulto' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'ciudad_carga' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'frontera' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'coleta' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'ciudad_descarga' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'direccion_descarga' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'telefono_descarga' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'contacto_descarga' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'email_descarga' => [
                    'nullable',
                    'email',
                    'max:255',
                ],

                'despachante_uy' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'email_despachante' => [
                    'nullable',
                    'email',
                    'max:255',
                ],
                'telefono_despachante' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'direccion_despachante' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'gestor' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'telefono_gestor' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'contacto_despachante' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'dua' => [
                    'nullable',
                    'regex:/^[0-9]{1,6}$/',
                ],

                /*
                |--------------------------------------------------------------------------
                | Información comercial
                |--------------------------------------------------------------------------
                */

                'hecha_por' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'fecha_comercial' => [
                    'nullable',
                    'date',
                ],
                'puesta_fob' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'flete_internacional' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'flete_nacional' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'ad_valorem_porcentaje' => [
                    'nullable',
                    'numeric',
                    'min:0',
                    'max:100',
                ],
                'ad_valorem' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'despacho_brasil' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'fob_usd' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
                'proforma' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'factura_definitiva' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'cantidad_camiones' => [
                    'nullable',
                    'integer',
                    'min:0',
                ],
                'flete_por_camion' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
                'observaciones_comerciales' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ],
            [
                'tipo_operacion.required' => 'Seleccioná el tipo de operación.',

                'tipo_operacion.in' => 'El tipo de operación seleccionado no es válido.',

                'numero_ficha.unique' => 'El número de ficha ya está registrado.',

                'numero_ficha.max' => 'El número de ficha no puede superar los 50 caracteres.',

                'cliente_id.required' => 'Seleccioná el cliente asociado.',

                'cliente_id.exists' => 'Seleccioná un cliente nacional activo.',

                'cliente_internacional_id.required' => 'Seleccioná un cliente internacional.',

                'cliente_internacional_id.exists' => 'Seleccioná un cliente internacional activo de Brasil o Argentina.',

                'transportista.exists' => 'El transportista seleccionado no es válido o está inactivo.',

                'num_crt.unique' => 'El número de CRT ya está registrado.',

                'num_crt.max' => 'El número de CRT no puede superar los 255 caracteres.',

                'exportador_email.email' => 'El correo del exportador no es válido.',

                'email_descarga.email' => 'El correo de descarga no es válido.',

                'email_despachante.email' => 'El correo del despachante no es válido.',

                'mercaderia.max' => 'La mercadería no puede superar los 255 caracteres.',

                'peso.numeric' => 'El peso debe ser un número.',

                'peso.min' => 'El peso no puede ser negativo.',

                'volumen.numeric' => 'El volumen debe ser un número.',

                'volumen.min' => 'El volumen no puede ser negativo.',

                'cantidad_bultos.integer' => 'La cantidad de bultos debe ser un número entero.',

                'cantidad_bultos.min' => 'La cantidad de bultos no puede ser negativa.',

                'dua.regex' => 'El DUA debe contener entre 1 y 6 números.',

                'fecha_comercial.date' => 'La fecha comercial no es válida.',

                'puesta_fob.numeric' => 'La puesta a FOB debe ser un número.',

                'puesta_fob.min' => 'La puesta a FOB no puede ser negativa.',

                'flete_internacional.numeric' => 'El flete internacional debe ser un número.',

                'flete_internacional.min' => 'El flete internacional no puede ser negativo.',

                'flete_nacional.numeric' => 'El flete nacional debe ser un número.',

                'flete_nacional.min' => 'El flete nacional no puede ser negativo.',

                'ad_valorem_porcentaje.numeric' => 'El porcentaje del ad valorem debe ser un número.',

                'ad_valorem_porcentaje.min' => 'El porcentaje del ad valorem no puede ser negativo.',

                'ad_valorem_porcentaje.max' => 'El porcentaje del ad valorem no puede superar el 100 %.',

                'ad_valorem.numeric' => 'El valor del ad valorem debe ser un número.',

                'ad_valorem.min' => 'El valor del ad valorem no puede ser negativo.',

                'despacho_brasil.numeric' => 'El despacho de Brasil debe ser un número.',

                'despacho_brasil.min' => 'El despacho de Brasil no puede ser negativo.',

                'fob_usd.numeric' => 'El FOB US$ debe ser un número.',

                'fob_usd.min' => 'El FOB US$ no puede ser negativo.',

                'cantidad_camiones.integer' => 'La cantidad de camiones debe ser un número entero.',

                'cantidad_camiones.min' => 'La cantidad de camiones no puede ser negativa.',

                'flete_por_camion.string' => 'El flete por camión debe ser un texto válido.',

                'flete_por_camion.max' => 'El flete por camión no puede superar los 255 caracteres.',

                'observaciones_comerciales.max' => 'Las observaciones no pueden superar los 5000 caracteres.',
            ]
        );
    }

    private function prepareData(
        array $validated,
        Cliente $cliente
    ): array {
        $camposDelTotal = [
            'puesta_fob',
            'flete_internacional',
            'flete_nacional',
            'ad_valorem',
            'despacho_brasil',
        ];

        $totalComercial = 0;
        $tieneImportes = false;

        foreach ($camposDelTotal as $campo) {
            if (
                isset($validated[$campo]) &&
                $validated[$campo] !== ''
            ) {
                $totalComercial +=
                    (float) $validated[$campo];

                $tieneImportes = true;
            }
        }

        return [
            'num_ficha' => $this->trimOrNull(
                $validated['numero_ficha'] ?? null
            ),

            'tipo_operacion' => $validated['tipo_operacion'],

            'cliente_id' => (int) $validated['cliente_id'],

            'cliente_internacional_id' => filled($validated['cliente_internacional_id'] ?? null)
                ? (int) $validated['cliente_internacional_id']
                : null,

            'transportista_id' => filled($validated['transportista'] ?? null)
                ? (int) $validated['transportista']
                : null,

            'num_crt' => $this->trimOrNull(
                $validated['num_crt'] ?? null
            ),

            'cliente_nombre' => $this->trimOrNull(
                $validated['cliente_nombre'] ?? null
            ) ?? $cliente->nombre,

            'cliente_contacto' => $this->trimOrNull(
                $validated['cliente_contacto'] ?? null
            ),

            'cliente_telefono' => $this->trimOrNull(
                $validated['cliente_telefono'] ?? null
            ),

            'cliente_ruc_rut' => $this->trimOrNull(
                $validated['cliente_ruc'] ?? null
            ),

            'nombre_exportador' => $this->trimOrNull(
                $validated['exportador_nombre'] ?? null
            ),

            'contacto_exportador_nombre' => $this->trimOrNull(
                $validated['exportador_contacto'] ?? null
            ),

            'contacto_exportador_email' => $this->trimOrNull(
                $validated['exportador_email'] ?? null
            ),

            'telefono_exportador' => $this->trimOrNull(
                $validated['exportador_telefono'] ?? null
            ),

            'despachante_exportador' => $this->trimOrNull(
                $validated['despachante_exportador'] ?? null
            ),

            'mercaderia' => $this->trimOrNull(
                $validated['mercaderia'] ?? null
            ),

            'peso_bruto' => $validated['peso'] ?? null,

            'volumen' => $validated['volumen'] ?? null,

            'num_factura' => $this->trimOrNull(
                $validated['num_factura'] ?? null
            ),

            'cantidad_bultos' => $validated['cantidad_bultos'] ?? null,

            'tipo_bulto' => $this->trimOrNull(
                $validated['tipo_bulto'] ?? null
            ),

            'ciudad_carga' => $this->trimOrNull(
                $validated['ciudad_carga'] ?? null
            ),

            'frontera' => $this->trimOrNull(
                $validated['frontera'] ?? null
            ),

            'coleta' => $this->trimOrNull(
                $validated['coleta'] ?? null
            ),

            'ciudad_descarga' => $this->trimOrNull(
                $validated['ciudad_descarga'] ?? null
            ),

            'direccion_descarga' => $this->trimOrNull(
                $validated['direccion_descarga'] ?? null
            ),

            'telefono_descarga' => $this->trimOrNull(
                $validated['telefono_descarga'] ?? null
            ),

            'contacto_descarga' => $this->trimOrNull(
                $validated['contacto_descarga'] ?? null
            ),

            'email_descarga' => $this->trimOrNull(
                $validated['email_descarga'] ?? null
            ),

            'despachante_local_nombre' => $this->trimOrNull(
                $validated['despachante_uy'] ?? null
            ),

            'despachante_local_email' => $this->trimOrNull(
                $validated['email_despachante'] ?? null
            ),

            'despachante_local_telefono' => $this->trimOrNull(
                $validated['telefono_despachante'] ?? null
            ),

            'despachante_local_direccion' => $this->trimOrNull(
                $validated['direccion_despachante'] ?? null
            ),

            'despachante_local_gestor' => $this->trimOrNull(
                $validated['gestor'] ?? null
            ),

            'despachante_local_gestor_telefono' => $this->trimOrNull(
                $validated['telefono_gestor'] ?? null
            ),

            'despachante_local_contacto' => $this->trimOrNull(
                $validated['contacto_despachante'] ?? null
            ),

            'dua' => $this->trimOrNull(
                $validated['dua'] ?? null
            ),

            /*
            |--------------------------------------------------------------------------
            | Información comercial
            |--------------------------------------------------------------------------
            */

            'hecha_por' => $this->trimOrNull(
                $validated['hecha_por'] ?? null
            ),

            'fecha_comercial' => $validated['fecha_comercial'] ?? null,

            'puesta_fob' => $validated['puesta_fob'] ?? null,

            'flete_internacional' => $validated['flete_internacional'] ?? null,

            'flete_nacional' => $validated['flete_nacional'] ?? null,

            'ad_valorem_porcentaje' => $validated['ad_valorem_porcentaje']
                ?? null,

            'ad_valorem' => $validated['ad_valorem'] ?? null,

            'despacho_brasil' => $validated['despacho_brasil'] ?? null,

            'total_comercial' => $tieneImportes
                ? round($totalComercial, 2)
                : null,

            'fob_usd' => $validated['fob_usd'] ?? null,

            'proforma' => $this->trimOrNull(
                $validated['proforma'] ?? null
            ),

            'factura_definitiva' => $this->trimOrNull(
                $validated['factura_definitiva'] ?? null
            ),

            'cantidad_camiones' => $validated['cantidad_camiones'] ?? null,

            'flete_por_camion' => $this->trimOrNull(
                $validated['flete_por_camion'] ?? null
            ),

            'observaciones_comerciales' => $this->trimOrNull(
                $validated['observaciones_comerciales']
                    ?? null
            ),
        ];
    }

    private function trimOrNull(
        ?string $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    private function ensureNotCancelled(
        Ficha $ficha
    ): void {
        abort_if(
            $ficha->estado === 'anulada',
            404
        );
    }
}
