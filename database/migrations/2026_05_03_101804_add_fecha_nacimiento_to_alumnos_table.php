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
        Schema::table('alumnos', function (Blueprint $table) {
            // La ponemos 'nullable' por si ya tienes alumnos guardados sin fecha
            $table->date('fecha_nacimiento')->nullable()->after('apellidos');
        });
    }

    public function down()
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn('fecha_nacimiento');
        });
    }
};
