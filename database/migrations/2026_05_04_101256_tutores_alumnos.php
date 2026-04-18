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
    Schema::create('tutores_alumnos', function (Blueprint $table) {
        $table->foreignId('tutor_id')->constrained('tutores')->onDelete('cascade');
        $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
        $table->string('parentesco', 30)->nullable();
        $table->primary(['tutor_id', 'alumno_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('tutores_alumnos');
    }
};
