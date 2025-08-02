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
        Schema::create('intentos_cuestionarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuestionario_id');
            $table->unsignedBigInteger('inscrito_id');
            $table->integer('intento_numero'); // 1, 2, 3...
            $table->timestamp('iniciado_en')->nullable();
            $table->timestamp('finalizado_en')->nullable();
            $table->integer('nota')->nullable(); // puntaje total obtenido
            $table->boolean('aprobado')->default(false);
            $table->timestamps();

            $table->foreign('cuestionario_id')->references('id')->on('cuestionarios')->onDelete('cascade');
            $table->foreign('inscrito_id')->references('id')->on('inscritos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intentos_cuestionarios');
    }
};