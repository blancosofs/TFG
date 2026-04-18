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
       Schema::create('colegios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('entidad',100)->nullable();
            $table->string('direccion',200)->nullable();
            $table->boolean('activo')->default(true);
            //esto te hace el created_at y updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colegios');
    }
};


