<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_repaso_tutor', function (Blueprint $table) {
            $table->foreignId('material_repaso_id')->constrained('materiales_repaso')->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained('tutores')->onDelete('cascade');
            $table->primary(['material_repaso_id', 'tutor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_repaso_tutor');
    }
};
