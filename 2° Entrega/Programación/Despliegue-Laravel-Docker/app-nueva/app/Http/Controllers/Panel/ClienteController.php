<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $clientes = Cliente::query()
            ->where('estado', 'activo')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = trim((string) $request->input('buscar'));

                $query->where(function ($subquery) use ($buscar) {
                    $subquery->where('nombre', 'like', '%'.$buscar.'%')
                        ->orWhere('usuario', 'like', '%'.$buscar.'%')
                        ->orWhere('direccion', 'like', '%'.$buscar.'%');
                });
            })
            ->when(
                in_array($request->input('origen'), ['local', 'brasil', 'argentina'], true),
                function ($query) use ($request) {
                    $query->where('origen', $request->input('origen'));
                }
            )
            ->orderBy('nombre')
            ->get();

        return view(
            'panel.clientes.index',
            compact('clientes')
        );
    }

    public function create(): View
    {
        return view('panel.clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeFields($request);

        $internacional = in_array(
            $request->input('origen'),
            ['brasil', 'argentina'],
            true
        );

        $validated = $request->validate(
            $this->rules(null, false),
            $this->messages()
        );

        $datos = $this->clientData($validated, $internacional);

        $datos['usuario'] = $validated['usuario'];
        $datos['password'] = $validated['password'];
        $datos['estado'] = 'activo';

        Cliente::create($datos);

        return redirect()
            ->route('panel.clientes.index')
            ->with('status', 'El cliente fue registrado correctamente.');
    }

    public function show(Cliente $cliente): View
    {
        $this->ensureActive($cliente);

        return view(
            'panel.clientes.show',
            compact('cliente')
        );
    }

    public function edit(Cliente $cliente): View
    {
        $this->ensureActive($cliente);

        return view(
            'panel.clientes.edit',
            compact('cliente')
        );
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $this->ensureActive($cliente);
        $this->normalizeFields($request);

        $internacional = in_array(
            $request->input('origen'),
            ['brasil', 'argentina'],
            true
        );

        $validated = $request->validate(
            $this->rules(
                $cliente->id,
                true,
                blank($cliente->password)
            ),
            $this->messages()
        );

        $datos = $this->clientData($validated, $internacional);

        $datos['usuario'] = $validated['usuario'];

        if (filled($validated['password'] ?? null)) {
            $datos['password'] = $validated['password'];
        }

        $cliente->update($datos);

        return redirect()
            ->route('panel.clientes.show', $cliente)
            ->with('status', 'El cliente fue actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $this->ensureActive($cliente);

        $cliente->update([
            'estado' => 'inactivo',
        ]);

        return redirect()
            ->route('panel.clientes.index')
            ->with(
                'status',
                'El cliente fue dado de baja correctamente.'
            );
    }

    private function normalizeFields(Request $request): void
    {
        $fields = [
            'nombre',
            'ruc_rut',
            'direccion',
            'telefono',
            'contacto_nombre',
            'contacto_email',
            'despachante',
            'usuario',
        ];

        $normalized = [];

        foreach ($fields as $field) {
            if ($request->exists($field)) {
                $value = $request->input($field);

                if (is_string($value)) {
                    $normalized[$field] = $field === 'usuario'
                        ? strtolower(trim($value))
                        : trim($value);
                }
            }
        }

        $request->merge($normalized);
    }

    private function rules(
        ?int $ignoreId,
        bool $editing,
        bool $sinPassword = false
    ): array {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],

            'origen' => [
                'required',
                Rule::in(['local', 'brasil', 'argentina']),
            ],

            'ruc_rut' => [
                'nullable',
                'string',
                'max:50',
            ],

            'direccion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telefono' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[0-9+\s().-]+$/',
            ],

            'contacto_nombre' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contacto_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'despachante' => [
                'nullable',
                'string',
                'max:255',
            ],

            'usuario' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('clientes', 'usuario')->ignore($ignoreId),
                Rule::unique('usuarios_internos', 'usuario'),
            ],

            'password' => [
                (! $editing || $sinPassword) ? 'required' : 'nullable',
                'string',
                'min:8',
                'max:255',
                'confirmed',
            ],
        ];
    }

    private function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',

            'origen.required' => 'Seleccioná el país de origen.',
            'origen.in' => 'El país seleccionado no es válido.',

            'direccion.string' => 'La dirección debe ser un texto válido.',
            'direccion.max' => 'La dirección no puede superar los 255 caracteres.',

            'telefono.string' => 'El teléfono debe ser un texto válido.',
            'telefono.max' => 'El teléfono no puede superar los 50 caracteres.',
            'telefono.regex' => 'El teléfono solamente puede contener números, espacios y los símbolos +, -, ( ), o punto.',

            'usuario.required' => 'El usuario es obligatorio.',
            'usuario.string' => 'El usuario debe ser un texto válido.',
            'usuario.min' => 'El usuario debe tener al menos 3 caracteres.',
            'usuario.max' => 'El usuario no puede superar los 255 caracteres.',
            'usuario.unique' => 'Ese nombre de usuario ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser un texto válido.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede superar los 255 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',

            'contacto_email.email' => 'Ingresá un correo electrónico válido.',
        ];
    }

    private function clientData(array $validated, bool $internacional): array
    {
        $datos = [
            'nombre' => $validated['nombre'],
            'origen' => $validated['origen'],
            'direccion' => $this->nullableText($validated['direccion'] ?? null),
            'telefono' => $this->nullableText($validated['telefono'] ?? null),
            'contacto_nombre' => $this->nullableText($validated['contacto_nombre'] ?? null),
            'contacto_email' => $this->nullableText($validated['contacto_email'] ?? null),
        ];

        if ($internacional) {
            $datos['despachante'] = $this->nullableText(
                $validated['despachante'] ?? null
            );

            $datos['ruc_rut'] = null;
        } else {
            $datos['ruc_rut'] = $this->nullableText(
                $validated['ruc_rut'] ?? null
            );

            $datos['despachante'] = null;
        }

        return $datos;
    }

    private function nullableText(?string $value): ?string
    {
        return filled($value) ? trim($value) : null;
    }

    private function ensureActive(Cliente $cliente): void
    {
        abort_if(
            $cliente->estado !== 'activo',
            404
        );
    }
}
