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
        Schema::create('aportes', function (Blueprint $table) {
            $table->id();
            $table->string('codigopago');
            $table->string('pagante');
            $table->string('paganteci');
            $table->string('datosEstudiante');
            $table->string('DescripcionDelPago');
            $table->double('monto_pagado');
            $table->decimal('monto_a_pagar', 10, 2);
            $table->decimal('restante_a_pagar', 10, 2);
            $table->decimal('Saldo', 10, 2);
            $table->string('comprobante');
            $table->string('tipopago');
            $table->unsignedBigInteger('estudiante_id');
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
        Schema::dropIfExists('aportes');
    }
};
