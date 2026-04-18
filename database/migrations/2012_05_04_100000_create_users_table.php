<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TABLA DE USUARIOS
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('apellidos', 60)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // CLAVE FORÁNEA: El colegio debe existir antes en la BD
            $table->foreignId('colegio_id')->nullable()->constrained('colegios')->onDelete('cascade');
            
            $table->boolean('activo')->default(true);
            $table->rememberToken();
            $table->timestamps();

            // ÍNDICE (Para búsquedas rápidas por colegio)
            $table->index('colegio_id', 'idx_users_colegio');
        });

        // 2. TOKENS DE CONTRASEÑA (Breeze)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. SESIONES (Breeze / Necesaria si usas SESSION_DRIVER=database)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        // El orden aquí es vital para evitar errores de FK
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
