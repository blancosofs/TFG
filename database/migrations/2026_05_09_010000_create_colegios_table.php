<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colegios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('entidad', 100)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->boolean('activo')->default(true);

            $table->string('tipo', 50)->nullable();
            $table->string('etapas', 100)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('comunidad', 100)->nullable();
            $table->string('cp', 5)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('web', 255)->nullable();
            $table->unsignedInteger('num_alumnos')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colegios');
    }
};
