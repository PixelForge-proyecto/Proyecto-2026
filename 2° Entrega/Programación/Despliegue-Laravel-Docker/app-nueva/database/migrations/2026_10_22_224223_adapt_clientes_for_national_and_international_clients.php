<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table
                ->string('ruc_rut', 50)
                ->nullable()
                ->after('nombre');

            $table
                ->string('contacto_nombre')
                ->nullable()
                ->after('telefono');

            $table
                ->string('contacto_email')
                ->nullable()
                ->after('contacto_nombre');

            $table
                ->index(
                    'ruc_rut',
                    'idx_clientes_ruc_rut'
                );
        });

        Schema::table('clientes', function (Blueprint $table) {
            /*
             * Los clientes internacionales no acceden al portal,
             * por lo que no necesitan usuario ni contraseña.
             */
            $table
                ->string('usuario')
                ->nullable()
                ->change();

            $table
                ->string('password')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        /*
         * Si se revierte la migración, se generan credenciales
         * técnicas para evitar valores nulos antes de restaurar
         * las restricciones anteriores.
         */
        DB::table('clientes')
            ->whereNull('usuario')
            ->orderBy('id')
            ->get()
            ->each(function (object $cliente): void {
                DB::table('clientes')
                    ->where('id', $cliente->id)
                    ->update([
                        'usuario' => 'internacional_rollback_'
                            .$cliente->id,

                        'password' => Hash::make(
                            Str::random(40)
                        ),
                    ]);
            });

        Schema::table('clientes', function (Blueprint $table) {
            $table
                ->string('usuario')
                ->nullable(false)
                ->change();

            $table
                ->string('password')
                ->nullable(false)
                ->change();
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropIndex(
                'idx_clientes_ruc_rut'
            );

            $table->dropColumn([
                'ruc_rut',
                'contacto_nombre',
                'contacto_email',
            ]);
        });
    }
};
