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
    Schema::create('horarios', function (Blueprint $table) {
        $table->id();
        $table->enum('dia_semana', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes']);
        $table->time('hora_inicio');
        $table->time('hora_fin');
        $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
        $table->foreignId('clase_id')->constrained('clases')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
