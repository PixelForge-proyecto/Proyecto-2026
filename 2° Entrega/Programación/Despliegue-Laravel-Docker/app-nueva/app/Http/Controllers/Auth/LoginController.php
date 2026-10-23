<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (Auth::guard('interno')->check()) {
            return redirect()->route('panel.dashboard');
        }

        if (Auth::guard('cliente')->check()) {
            return redirect()->route('cliente.inicio');
        }

        return view('login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'usuario' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:255',
                ],
            ],
            [
                'usuario.required' => 'El usuario es obligatorio.',

                'usuario.string' => 'El usuario debe ser un texto válido.',

                'usuario.max' => 'El usuario no puede superar los 255 caracteres.',

                'password.required' => 'La contraseña es obligatoria.',

                'password.string' => 'La contraseña debe ser un texto válido.',

                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',

                'password.max' => 'La contraseña no puede superar los 255 caracteres.',
            ]
        );

        $usuario = strtolower(trim($validated['usuario']));
        $password = $validated['password'];

        $credenciales = [
            'usuario' => $usuario,
            'password' => $password,
            'estado' => 'activo',
        ];

        if (Auth::guard('interno')->attempt($credenciales)) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route('panel.dashboard'))
                ->with(
                    'status',
                    'Inicio de sesión interno realizado correctamente.'
                );
        }

        if (Auth::guard('cliente')->attempt($credenciales)) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route('cliente.inicio'))
                ->with(
                    'status',
                    'Inicio de sesión realizado correctamente.'
                );
        }

        return back()
            ->withInput([
                'usuario' => $usuario,
            ])
            ->withErrors([
                'usuario' => 'El usuario o la contraseña no son correctos.',
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('interno')->logout();
        Auth::guard('cliente')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'status',
                'La sesión se cerró correctamente.'
            );
    }
}
