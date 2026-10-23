<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Despachante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DespachanteController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));

        $despachantes = Despachante::query()
            ->where('estado', 'activo')
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($subquery) use ($buscar) {
                    $subquery
                        ->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%")
                        ->orWhere('contacto', 'like', "%{$buscar}%")
                        ->orWhere('gestor', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre')
            ->get();

        return view(
            'panel.despachantes.index',
            compact('despachantes')
        );
    }

    public function create(): View
    {
        return view('panel.despachantes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDespachante($request);

        Despachante::create([
            ...$this->normalizarDatos($validated),
            'estado' => 'activo',
        ]);

        return redirect()
            ->route('panel.despachantes.index')
            ->with('status', 'El despachante local fue registrado correctamente.');
    }

    public function show(Despachante $despachante): View
    {
        $this->ensureActive($despachante);

        return view(
            'panel.despachantes.show',
            compact('despachante')
        );
    }

    public function edit(Despachante $despachante): View
    {
        $this->ensureActive($despachante);

        return view(
            'panel.despachantes.edit',
            compact('despachante')
        );
    }

    public function update(
        Request $request,
        Despachante $despachante
    ): RedirectResponse {
        $this->ensureActive($despachante);

        $validated = $this->validateDespachante($request);

        $despachante->update(
            $this->normalizarDatos($validated)
        );

        return redirect()
            ->route('panel.despachantes.index')
            ->with('status', 'El despachante local fue actualizado correctamente.');
    }

    public function destroy(Despachante $despachante): RedirectResponse
    {
        $this->ensureActive($despachante);

        $nombre = $despachante->nombre;

        $despachante->update([
            'estado' => 'inactivo',
        ]);

        return redirect()
            ->route('panel.despachantes.index')
            ->with(
                'status',
                "El despachante {$nombre} fue dado de baja correctamente."
            );
    }

    private function validateDespachante(Request $request): array
    {
        return $request->validate(
            [
                'nombre' => ['required', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'telefono' => ['nullable', 'string', 'max:50'],
                'direccion' => ['nullable', 'string', 'max:255'],
                'gestor' => ['nullable', 'string', 'max:255'],
                'gestor_telefono' => ['nullable', 'string', 'max:50'],
                'contacto' => ['nullable', 'string', 'max:255'],
            ],
            [
                'nombre.required' => 'El nombre del despachante es obligatorio.',
                'nombre.string' => 'El nombre debe ser un texto válido.',
                'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
                'email.email' => 'Ingresá un correo electrónico válido.',
                'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
                'telefono.max' => 'El teléfono no puede superar los 50 caracteres.',
                'direccion.max' => 'La dirección no puede superar los 255 caracteres.',
                'gestor.max' => 'El gestor no puede superar los 255 caracteres.',
                'gestor_telefono.max' => 'El teléfono del gestor no puede superar los 50 caracteres.',
                'contacto.max' => 'El contacto no puede superar los 255 caracteres.',
            ]
        );
    }

    private function normalizarDatos(array $validated): array
    {
        return [
            'nombre' => trim($validated['nombre']),
            'email' => $this->trimOrNull($validated['email'] ?? null),
            'telefono' => $this->trimOrNull($validated['telefono'] ?? null),
            'direccion' => $this->trimOrNull($validated['direccion'] ?? null),
            'gestor' => $this->trimOrNull($validated['gestor'] ?? null),
            'gestor_telefono' => $this->trimOrNull($validated['gestor_telefono'] ?? null),
            'contacto' => $this->trimOrNull($validated['contacto'] ?? null),
        ];
    }

    private function trimOrNull(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    private function ensureActive(Despachante $despachante): void
    {
        abort_if($despachante->estado !== 'activo', 404);
    }
}
