<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('curso_progreso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->integer('total_estudiantes')->default(0);
            $table->integer('estudiantes_completados')->default(0);
            $table->decimal('porcentaje_progreso', 5, 2)->default(0);
            $table->timestamp('ultima_actualizacion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('curso_progreso');
    }
};
