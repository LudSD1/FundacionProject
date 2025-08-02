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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subtema_id')->nullable(); // Puedes adaptarlo según tu estructura
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_limite')->nullable();
            $table->integer('orden')->nullable();
            $table->boolean('es_publica')->default(true);
            $table->boolean('es_obligatoria')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('subtema_id')->references('id')->on('subtemas')->onDelete('set null');
            $table->unsignedBigInteger('tipo_actividad_id');
            $table->foreign('tipo_actividad_id')->references('id')->on('tipo_actividades')->onDelete('restrict');


        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
