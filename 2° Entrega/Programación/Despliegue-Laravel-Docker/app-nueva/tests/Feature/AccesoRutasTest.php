<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccesoRutasTest extends TestCase
{
    public function test_la_pagina_principal_es_publica(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_el_login_es_publico(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function test_un_visitante_no_puede_ingresar_al_panel(): void
    {
        $response = $this->get('/panel');

        $response->assertRedirect('/login');
    }

    public function test_un_visitante_no_puede_ingresar_al_area_del_cliente(): void
    {
        $response = $this->get('/cliente');

        $response->assertRedirect('/login');
    }

    public function test_una_ruta_inexistente_devuelve_error_404(): void
    {
        $response = $this->get('/ruta-que-no-existe');

        $response->assertNotFound();
    }
}
