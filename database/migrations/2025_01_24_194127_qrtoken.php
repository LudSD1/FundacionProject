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
        Schema::create('qr_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curso_id'); // Curso relacionado
            $table->string('token')->unique(); // Token único
            $table->integer('limite_uso')->default(1); // Número de accesos permitidos
            $table->integer('usos_actuales')->default(0); // Contador de usos
            $table->timestamp('expiracion')->nullable(); // Fecha de expiración
            $table->timestamps();

            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
