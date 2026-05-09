<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colegios', function (Blueprint $table) {
            $table->string('tipo', 50)->nullable()->after('nombre');
            $table->string('etapas', 100)->nullable()->after('tipo');
            $table->string('calle', 100)->nullable()->after('etapas');
            $table->string('ciudad', 100)->nullable()->after('calle');
            $table->string('comunidad', 100)->nullable()->after('ciudad');
            $table->string('cp', 5)->nullable()->after('comunidad');
            $table->string('telefono', 20)->nullable()->after('cp');
            $table->string('email', 100)->nullable()->after('telefono');
            $table->string('web', 255)->nullable()->after('email');
            $table->unsignedInteger('num_alumnos')->nullable()->after('web');
            $table->text('notas')->nullable()->after('num_alumnos');
        });
    }

    public function down(): void
    {
        Schema::table('colegios', function (Blueprint $table) {
            $table->dropColumn([
                'tipo', 'etapas', 'calle', 'ciudad', 'comunidad',
                'cp', 'telefono', 'email', 'web', 'num_alumnos', 'notas',
            ]);
        });
    }
};
