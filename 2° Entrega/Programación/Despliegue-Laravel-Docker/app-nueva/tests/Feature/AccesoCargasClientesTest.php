<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Ficha;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccesoCargasClientesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        /*
         * Protección: estas pruebas solo pueden utilizar
         * SQLite en memoria. Nunca MySQL.
         */
        if (
            config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:'
        ) {
            throw new \RuntimeException(
                'Estas pruebas requieren SQLite en memoria.'
            );
        }

        $schema = DB::connection('sqlite')->getSchemaBuilder();

        $schema->create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('origen');
            $table->string('usuario')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('estado')->default('activo');
            $table->timestamps();
        });

        $schema->create('fichas', function (Blueprint $table) {
            $table->id();
            $table->string('num_ficha')->nullable();
            $table->string('tipo_operacion');
            $table->foreignId('cliente_id');
            $table->foreignId('cliente_internacional_id')->nullable();
            $table->string('estado')->default('iniciada');
            $table->string('dua')->nullable();
            $table->string('num_crt')->nullable();
            $table->string('ciudad_carga')->nullable();
            $table->string('ciudad_descarga')->nullable();
            $table->string('frontera')->nullable();
            $table->timestamps();
        });

        $schema->create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ficha_id');
            $table->string('etapa');
            $table->dateTime('fecha')->nullable();
            $table->text('observacion')->nullable();
            $table->boolean('notificar_cliente')->default(false);
            $table->string('canal_aduanero')->nullable();
            $table->timestamps();
        });
    }

    private function crearCliente(
        string $origen,
        string $usuario,
        string $estado = 'activo'
    ): Cliente {
        return Cliente::create([
            'nombre' => 'Cliente '.$usuario,
            'origen' => $origen,
            'usuario' => $usuario,
            'password' => 'password123',
            'estado' => $estado,
        ]);
    }

    private function crearFicha(
        Cliente $nacional,
        ?Cliente $internacional,
        string $dua,
        string $estado = 'iniciada'
    ): Ficha {
        return Ficha::create([
            'tipo_operacion' => 'importacion',
            'cliente_id' => $nacional->id,
            'cliente_internacional_id' => $internacional?->id,
            'dua' => $dua,
            'estado' => $estado,
        ]);
    }

    public function test_cliente_uruguayo_ve_solo_sus_cargas(): void
    {
        $uruguayo = $this->crearCliente('local', 'uruguay1');
        $otro = $this->crearCliente('local', 'uruguay2');

        $propia = $this->crearFicha($uruguayo, null, '100001');
        $this->crearFicha($otro, null, '100002');

        $this->actingAs($uruguayo, 'cliente');

        $this->get(route('cliente.inicio'))
            ->assertOk()
            ->assertViewHas('cantidadCargas', 1);

        $this->get(route('cliente.cargas.index'))
            ->assertOk()
            ->assertViewHas('cargas', function ($cargas) use ($propia) {
                return $cargas->count() === 1
                    && $cargas->first()->id === $propia->id;
            });
    }

    public function test_cliente_brasileno_ve_solo_sus_cargas(): void
    {
        $uruguayo = $this->crearCliente('local', 'uruguay1');
        $brasileno = $this->crearCliente('brasil', 'brasil1');
        $argentino = $this->crearCliente('argentina', 'argentina1');

        $propia = $this->crearFicha($uruguayo, $brasileno, '200001');
        $this->crearFicha($uruguayo, $argentino, '200002');

        $this->actingAs($brasileno, 'cliente');

        $this->get(route('cliente.inicio'))
            ->assertOk()
            ->assertViewHas('cantidadCargas', 1);

        $this->get(route('cliente.cargas.index'))
            ->assertOk()
            ->assertViewHas('cargas', function ($cargas) use ($propia) {
                return $cargas->count() === 1
                    && $cargas->first()->id === $propia->id;
            });
    }

    public function test_cliente_argentino_ve_solo_sus_cargas(): void
    {
        $uruguayo = $this->crearCliente('local', 'uruguay1');
        $argentino = $this->crearCliente('argentina', 'argentina1');
        $brasileno = $this->crearCliente('brasil', 'brasil1');

        $propia = $this->crearFicha($uruguayo, $argentino, '300001');
        $this->crearFicha($uruguayo, $brasileno, '300002');

        $this->actingAs($argentino, 'cliente');

        $this->get(route('cliente.cargas.index'))
            ->assertOk()
            ->assertViewHas('cargas', function ($cargas) use ($propia) {
                return $cargas->count() === 1
                    && $cargas->first()->id === $propia->id;
            });
    }

    public function test_cliente_no_puede_abrir_carga_ajena(): void
    {
        $uruguayo = $this->crearCliente('local', 'uruguay1');
        $brasileno = $this->crearCliente('brasil', 'brasil1');
        $argentino = $this->crearCliente('argentina', 'argentina1');

        $ajena = $this->crearFicha($uruguayo, $argentino, '400001');

        $this->actingAs($brasileno, 'cliente');

        $this->get(route('cliente.cargas.show', $ajena->id))
            ->assertNotFound();
    }

    public function test_cliente_no_puede_buscar_dua_ajeno(): void
    {
        $uruguayo = $this->crearCliente('local', 'uruguay1');
        $brasileno = $this->crearCliente('brasil', 'brasil1');
        $argentino = $this->crearCliente('argentina', 'argentina1');

        $this->crearFicha($uruguayo, $argentino, '500001');

        $this->actingAs($brasileno, 'cliente');

        $this->from(route('cliente.cargas.index'))
            ->get(route('cliente.cargas.buscar', ['dua' => '500001']))
            ->assertRedirect(route('cliente.cargas.index'))
            ->assertSessionHasErrors('dua');
    }

    public function test_cliente_puede_buscar_su_propio_dua(): void
    {
        $uruguayo = $this->crearCliente('local', 'uruguay1');
        $brasileno = $this->crearCliente('brasil', 'brasil1');

        $propia = $this->crearFicha($uruguayo, $brasileno, '600001');

        $this->actingAs($brasileno, 'cliente');

        $this->get(route('cliente.cargas.buscar', ['dua' => '600001']))
            ->assertRedirect(route('cliente.cargas.show', $propia->id));
    }

    public function test_fichas_anuladas_no_son_visibles(): void
    {
        $uruguayo = $this->crearCliente('local', 'uruguay1');
        $brasileno = $this->crearCliente('brasil', 'brasil1');

        $anulada = $this->crearFicha(
            $uruguayo,
            $brasileno,
            '700001',
            'anulada'
        );

        $this->actingAs($brasileno, 'cliente');

        $this->get(route('cliente.inicio'))
            ->assertOk()
            ->assertViewHas('cantidadCargas', 0);

        $this->get(route('cliente.cargas.index'))
            ->assertOk()
            ->assertViewHas('cargas', function ($cargas) {
                return $cargas->isEmpty();
            });

        $this->get(route('cliente.cargas.show', $anulada->id))
            ->assertNotFound();
    }

    public function test_cliente_inactivo_no_puede_consultar_cargas(): void
    {
        $uruguayo = $this->crearCliente('local', 'uruguay1');
        $inactivo = $this->crearCliente(
            'brasil',
            'brasil1',
            'inactivo'
        );

        $this->crearFicha($uruguayo, $inactivo, '800001');

        $this->actingAs($inactivo, 'cliente');

        $this->get(route('cliente.inicio'))
            ->assertForbidden();

        $this->get(route('cliente.cargas.index'))
            ->assertForbidden();
    }
}
