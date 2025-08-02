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
        Schema::create('xp_event_types', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');                      // Ej: Completar tarea
            $table->string('slug')->unique();              // Ej: completar_tarea
            $table->text('descripcion')->nullable();       // Breve explicación
            $table->integer('xp_base')->default(0);        // Valor base en XP para este tipo
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xp_event_types');
    }
};
