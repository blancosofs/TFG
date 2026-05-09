<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiales_repaso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('tipo_contenido', ['archivo', 'url_externa'])->default('archivo');
            $table->string('archivo_nombre_original')->nullable();
            $table->string('archivo_ruta', 500)->nullable();
            $table->integer('archivo_tamaño')->nullable();
            $table->string('url_externa', 500)->nullable();
            $table->string('materia', 100)->nullable();
            $table->string('tema', 150)->nullable();
            $table->boolean('publicado')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('docente_id');
            $table->index('publicado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiales_repaso');
    }
};
