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
    Schema::create('docentes_clases', function (Blueprint $table) {
        $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
        $table->foreignId('clase_id')->constrained('clases')->onDelete('cascade');
        $table->primary(['docente_id', 'clase_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes_clases');
    }
};
