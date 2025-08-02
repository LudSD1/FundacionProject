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
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade'); // Relación con cursos
            $table->foreignId('inscrito_id')->constrained('inscritos')->onDelete('cascade'); // Relación con usuarios (rol estudiante)
            $table->string('codigo_certificado')->unique(); // Código único para validar el certificado
            $table->string('ruta_certificado'); // Ruta del archivo PDF o imagen del certificado
            $table->timestamp('fecha_emision')->useCurrent(); // Fecha de emisión
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};
