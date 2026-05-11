<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tablon', function (Blueprint $table) {
            // Rastrear al creador independientemente del rol
            $table->foreignId('user_id')->nullable()->after('id')
                  ->constrained('users')->onDelete('cascade');

            // Permitir que coordinadores publiquen (sin docente_id)
            $table->dropForeign(['docente_id']);
            $table->unsignedBigInteger('docente_id')->nullable()->change();
            $table->foreign('docente_id')->references('id')->on('docentes')->onDelete('cascade');
        });

        // Rellenar user_id en registros existentes desde la relación docente→user
        DB::statement('
            UPDATE tablon t
            INNER JOIN docentes d ON t.docente_id = d.id
            SET t.user_id = d.user_id
            WHERE t.user_id IS NULL AND t.docente_id IS NOT NULL
        ');
    }

    public function down(): void
    {
        Schema::table('tablon', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->dropForeign(['docente_id']);
            $table->unsignedBigInteger('docente_id')->nullable(false)->change();
            $table->foreign('docente_id')->references('id')->on('docentes')->onDelete('cascade');
        });
    }
};
