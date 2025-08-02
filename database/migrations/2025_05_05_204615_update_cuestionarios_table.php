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
        Schema::table('cuestionarios', function (Blueprint $table) {
            // Agregar o modificar las columnas
            if (!Schema::hasColumn('cuestionarios', 'max_intentos')) {
                $table->integer('max_intentos')->default(3)->after('mostrar_resultados');
            }

            if (!Schema::hasColumn('cuestionarios', 'tiempo_limite')) {
                $table->integer('tiempo_limite')->nullable()->after('max_intentos'); // En minutos
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuestionarios', function (Blueprint $table) {
            // Eliminar las columnas si es necesario
            if (Schema::hasColumn('cuestionarios', 'max_intentos')) {
                $table->dropColumn('max_intentos');
            }

            if (Schema::hasColumn('cuestionarios', 'tiempo_limite')) {
                $table->dropColumn('tiempo_limite');
            }
        });
    }
};