<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Transportista;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransportistaController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));

        $transportistas = Transportista::query()
            ->where('estado', 'activo')
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($subquery) use ($buscar) {
                    $subquery
                        ->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%")
                        ->orWhere('contacto', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre')
            ->get();

        return view(
            'panel.transportistas.index',
            compact('transportistas')
        );
    }

    public function create(): View
    {
        return view('panel.transportistas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTransportista($request);

        Transportista::create([
            'nombre' => trim($validated['nombre']),
            'email' => $this->trimOrNull(
                $validated['email'] ?? null
            ),
            'contacto' => $this->trimOrNull(
                $validated['contacto'] ?? null
            ),
            'estado' => 'activo',
        ]);

        return redirect()
            ->route('panel.transportistas.index')
            ->with(
                'status',
                'El transportista fue registrado correctamente.'
            );
    }

    public function show(
        Transportista $transportista
    ): View {
        $this->ensureActive($transportista);

        return view(
            'panel.transportistas.show',
            compact('transportista')
        );
    }

    public function edit(
        Transportista $transportista
    ): View {
        $this->ensureActive($transportista);

        return view(
            'panel.transportistas.edit',
            compact('transportista')
        );
    }

    public function update(
        Request $request,
        Transportista $transportista
    ): RedirectResponse {
        $this->ensureActive($transportista);

        $validated = $this->validateTransportista($request);

        $transportista->update([
            'nombre' => trim($validated['nombre']),
            'email' => $this->trimOrNull(
                $validated['email'] ?? null
            ),
            'contacto' => $this->trimOrNull(
                $validated['contacto'] ?? null
            ),
        ]);

        return redirect()
            ->route('panel.transportistas.index')
            ->with(
                'status',
                'El transportista fue actualizado correctamente.'
            );
    }

    public function destroy(
        Transportista $transportista
    ): RedirectResponse {
        $this->ensureActive($transportista);

        $nombre = $transportista->nombre;

        $transportista->update([
            'estado' => 'inactivo',
        ]);

        return redirect()
            ->route('panel.transportistas.index')
            ->with(
                'status',
                "El transportista {$nombre} fue dado de baja correctamente."
            );
    }

    private function validateTransportista(
        Request $request
    ): array {
        return $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                ],
                'contacto' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ],
            [
                'nombre.required' => 'El nombre del transportista es obligatorio.',

                'nombre.string' => 'El nombre debe ser un texto válido.',

                'nombre.max' => 'El nombre no puede superar los 255 caracteres.',

                'email.email' => 'Ingresá un correo electrónico válido.',

                'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',

                'contacto.string' => 'El contacto debe ser un texto válido.',

                'contacto.max' => 'El contacto no puede superar los 255 caracteres.',
            ]
        );
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

    private function ensureActive(
        Transportista $transportista
    ): void {
        abort_if(
            $transportista->estado !== 'activo',
            404
        );
    }
}
