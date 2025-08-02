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
        Schema::create('recurso_subtemas', function (Blueprint $table) {
            $table->id();
            $table->string('nombreRecurso');
            $table->text('descripcionRecursos');
            $table->text('tipoRecurso');
            $table->string('archivoRecurso')->nullable();
            $table->unsignedBigInteger('subtema_id'); // Relación con el tema
            $table->foreign('subtema_id')->references('id')->on('subtemas')->onDelete('cascade');
            $table->boolean('progreso')->default(false);
            $table->unsignedInteger('clics')->default(0); // Columna para el contador de clics
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurso_subtemas');
    }
};
