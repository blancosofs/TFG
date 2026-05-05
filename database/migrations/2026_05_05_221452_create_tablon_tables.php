<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tablon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->foreignId('tutor_id')->nullable()->constrained('tutores')->onDelete('set null');
            
            $table->string('titulo', 200);
            $table->enum('categoria', ['General', 'Examen', 'Evento', 'Urgente', 'Tarea'])->default('General');
            $table->enum('dirigido_a', ['Todos', 'Solo familias', 'Solo docentes'])->default('Todos');
            $table->string('contenido', 2000);
            
            $table->foreignId('clase_id')->nullable()->constrained('clases')->onDelete('set null');
            $table->date('fecha_limite')->nullable();
            
            $table->timestamps();
        });

        Schema::create('comentarios_tablon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tablon_id')->constrained('tablon')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('texto', 1000);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tablon_tables');
    }
};
