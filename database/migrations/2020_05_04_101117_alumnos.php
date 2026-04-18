<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('alumnos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 25);
        $table->string('apellidos', 60);
        
        // Claves Foráneas
        $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
        $table->foreignId('curso_id')->constrained('cursos')->onDelete('restrict');
        $table->foreignId('clase_id')->constrained('clases')->onDelete('restrict');
        
        $table->boolean('activo')->default(true);
        $table->timestamps();

        // Índice para velocidad (como tenías en tu SQL)
        $table->index('clase_id', 'idx_alumnos_clase');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('alumnos');
}
};
