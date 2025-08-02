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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombreCurso');
            $table->string('codigoCurso')->nullable();
            $table->string('descripcionC');
            $table->dateTime('fecha_ini');
            $table->dateTime('fecha_fin');
            $table->string('archivoContenidodelCurso')->nullable();
            $table->integer('notaAprobacion')->nullable();
            $table->string('formato');
            $table->string('estado')->default('Activo');
            $table->string('tipo')->default('curso');
            $table->unsignedBigInteger('docente_id');
            $table->foreign('docente_id')->references('id')->on('users');
            $table->string('edad_dirigida')->nullable(); // Puede contener valores como "Niños", "Adolescentes", "Adultos"
            $table->string('nivel')->nullable();
            $table->decimal('precio', 8, 2)->default(0); // Nueva columna para el precio con valor por defecto 0
            $table->string('imagen')->nullable(); // Nueva columna para la imagen del curso
            $table->integer('duracion'); // Nueva columna para la duración del curso
            $table->integer('cupos');
            $table->enum('visibilidad', ['Público', 'Privado', 'Solo Registrados'])->default('Privado');

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
        Schema::dropIfExists('cursos');
    }
};
