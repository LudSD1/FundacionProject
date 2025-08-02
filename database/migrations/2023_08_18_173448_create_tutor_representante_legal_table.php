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
        Schema::create('tutor_representante_legals', function (Blueprint $table) {
            $table->id();
            $table->string('nombreTutor');
            $table->string('appaternoTutor');
            $table->string('apmaternoTutor');
            $table->string('CI');
            $table->string('Celular');
            $table->string('Direccion');
            $table->string('CorreoElectronicoTutor');
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
        Schema::dropIfExists('tutor_representante_legals');
    }
};
