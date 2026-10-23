<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fichas', function (Blueprint $table) {
            /*
             * Se deja nullable para conservar las fichas
             * existentes durante la transición.
             */
            $table
                ->foreignId('cliente_internacional_id')
                ->nullable()
                ->after('cliente_id')
                ->constrained(
                    table: 'clientes',
                    indexName: 'fk_fichas_cliente_internacional'
                )
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fichas', function (Blueprint $table) {
            $table->dropConstrainedForeignId(
                'cliente_internacional_id'
            );
        });
    }
};
