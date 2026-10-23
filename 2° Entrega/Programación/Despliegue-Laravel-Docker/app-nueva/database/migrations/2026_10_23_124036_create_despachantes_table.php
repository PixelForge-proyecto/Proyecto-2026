
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('despachantes', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 255);

            $table->string('email', 255)->nullable();

            $table->string('telefono', 50)->nullable();

            $table->string('direccion', 255)->nullable();

            $table->string('gestor', 255)->nullable();

            $table->string('gestor_telefono', 50)->nullable();

            $table->string('contacto', 255)->nullable();

            $table->enum('estado', [
                'activo',
                'inactivo',
            ])->default('activo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('despachantes');
    }
};
