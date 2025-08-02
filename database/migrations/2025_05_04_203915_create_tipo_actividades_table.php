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
        Schema::create('tipo_actividades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Tarea, Cuestionario, Foro, Evaluación
            $table->string('slug')->unique(); // Ej: tarea, cuestionario
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_actividades');
    }
};
