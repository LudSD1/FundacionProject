<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foros_mensajes', function (Blueprint $table) {
            $table->id();
            $table->string('tituloMensaje');
            $table->string('mensaje');
            $table->unsignedBigInteger('estudiante_id');
            $table->foreign('estudiante_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('foro_id');
            $table->foreign('foro_id')->references('id')->on('foros')->onDelete('cascade');
            $table->unsignedBigInteger('respuesta_a')->nullable(); // Relación para mensajes respondidos
            $table->foreign('respuesta_a')->references('id')->on('foros_mensajes')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foros_mensajes');
    }
};
