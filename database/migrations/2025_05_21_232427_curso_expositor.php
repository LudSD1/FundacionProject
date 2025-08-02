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
        Schema::create('curso_expositor', function (Blueprint $table) {
            $table->id();

            // Relación con cursos
            $table->foreignId('curso_id')
                  ->constrained('cursos') // Nombre exacto de la tabla cursos
                  ->onDelete('cascade');

            // Relación con expositores
            $table->foreignId('expositor_id')
                  ->constrained('expositores') // Nombre exacto de la tabla expositores
                  ->onDelete('cascade');

            // Campos específicos de la relación
            $table->string('cargo')->nullable()->comment('Rol específico en este curso/evento');
            $table->string('tema')->nullable()->comment('Tema que presentará en este evento');
            $table->integer('orden')->default(0)->comment('Orden de aparición');

            // Metadatos
            $table->timestamps();

            // Índices y restricciones
            $table->unique(['curso_id', 'expositor_id'], 'curso_expositor_unique');

            // Índices para mejor performance
            $table->index('orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
