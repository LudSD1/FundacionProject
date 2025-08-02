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
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuestionario_id');
            $table->text('enunciado');
            $table->enum('tipo', ['opcion_multiple', 'abierta', 'boolean'])->default('opcion_multiple');
            $table->integer('puntaje')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('cuestionario_id')->references('id')->on('cuestionarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};
