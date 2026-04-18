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
    Schema::create('tutores', function (Blueprint $table) {
        $table->id();
        $table->string('telefono', 20)->nullable();
        
        // Relación 1:1 con Users (Un tutor es un usuario)
        $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('tutores');
}

 


};
