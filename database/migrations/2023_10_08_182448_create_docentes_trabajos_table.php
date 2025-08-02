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
        Schema::create('docentes_trabajos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('docente_id');
            $table->text('empresa');
            $table->string('cargo');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->text('contacto_ref');
            $table->timestamps();
            $table->softDeletes();


            // Definir una clave foránea para relacionar el docente con sus trabajos
            $table->foreign('docente_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docentes_trabajos');
    }
};
