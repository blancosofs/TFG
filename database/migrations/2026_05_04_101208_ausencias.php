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
    Schema::create('ausencias', function (Blueprint $table) {
        $table->id();
        $table->date('fecha');
        $table->enum('tipo', ['falta', 'retraso'])->default('falta');
        $table->boolean('justificada')->default(false);
        $table->text('justificacion')->nullable();
        $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
        $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
        $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
        $table->timestamps();
        
        $table->index('fecha', 'idx_ausencias_fecha');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ausencias');
    }
};
