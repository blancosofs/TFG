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
    Schema::create('docentes', function (Blueprint $table) {
        $table->id();
        $table->string('telefono', 20)->nullable();
        $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
        $table->foreignId('coordinador_id')->constrained('coordinadores');
        $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('docentes');      
    }
};
