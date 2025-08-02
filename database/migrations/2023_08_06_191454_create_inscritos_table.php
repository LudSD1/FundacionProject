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
        Schema::create('inscritos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cursos_id');
            $table->foreign('cursos_id')->references('id')->on('cursos')->onDelete('cascade');
            $table->unsignedBigInteger('estudiante_id');
            $table->enum('estado', ['pendiente', 'activo', 'cancelado', 'finalizado'])->default('activo');
            $table->double('progreso')->default(0);
            $table->boolean('completado')->default(false);
            $table->boolean('pago_completado')->default(false);
            $table->foreign('estudiante_id')->references('id')->on('users');
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
        Schema::dropIfExists('inscritos');
    }
};
