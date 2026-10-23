<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_el_usuario_y_la_contrasena_son_obligatorios(): void
    {
        $response = $this
            ->from(route('login'))
            ->post(route('login.authenticate'), []);

        $response->assertRedirect(route('login'));

        $response->assertSessionHasErrors([
            'usuario',
            'password',
        ]);

        $this->assertGuest('interno');
        $this->assertGuest('cliente');
    }

    public function test_la_contrasena_debe_tener_al_menos_ocho_caracteres(): void
    {
        $response = $this
            ->from(route('login'))
            ->post(route('login.authenticate'), [
                'usuario' => 'cliente',
                'password' => '1234567',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('password');

        $this->assertGuest('interno');
        $this->assertGuest('cliente');
    }

    public function test_el_usuario_no_puede_superar_los_255_caracteres(): void
    {
        $response = $this
            ->from(route('login'))
            ->post(route('login.authenticate'), [
                'usuario' => str_repeat('a', 256),
                'password' => 'password123',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('usuario');

        $this->assertGuest('interno');
        $this->assertGuest('cliente');
    }

    public function test_logout_redirige_al_login(): void
    {
        $response = $this->post(route('logout'));

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHas('status');

        $this->assertGuest('interno');
        $this->assertGuest('cliente');
    }
}
