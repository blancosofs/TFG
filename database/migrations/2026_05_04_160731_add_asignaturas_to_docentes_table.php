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
    Schema::table('docentes', function (Blueprint $table) {
        // Usamos nullable() por si ya tienes docentes en la base de datos, 
        // para que no dé error al no tener este dato inicial.
        // Usamos after() para colocar la columna ordenada justo después del teléfono.
        $table->string('asignaturas')->nullable()->after('telefono'); 
    });
    }

    public function down()
    {
        Schema::table('docentes', function (Blueprint $table) {
            // Esto es por si haces un rollback, para que sepa cómo deshacer el cambio
            $table->dropColumn('asignaturas');
        });
    }
};
