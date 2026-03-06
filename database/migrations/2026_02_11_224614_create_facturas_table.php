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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aporte_id'); // Link to the payment
            $table->string('numero_factura'); // Sequential invoice number for the year/entity

            // SIAT Specific
            $table->string('cuf')->unique(); // Código Único de Facturación (64 length usually)
            $table->string('cufd'); // Código Único de Facturación Diaria (Rotates daily)
            $table->string('codigo_control', 20)->nullable(); // Legacy but good for simulation if needed

            // Issuer details (can be config too, but storing snapshot is safer)
            $table->string('nit_emisor');
            $table->string('razon_social_emisor');
            $table->string('direccion_emisor')->nullable();

            // Customer details
            $table->dateTime('fecha_emision');
            $table->string('nit_cliente')->nullable();
            $table->string('razon_social_cliente')->nullable();
            $table->string('complemento_ci')->nullable(); // For special cases

            // Amounts
            $table->decimal('monto_total', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('monto_final', 10, 2); // monto_total - descuento

            // Technical details
            $table->string('leyenda'); // Customizable text
            $table->enum('estado', ['VALIDADA', 'ANULADA'])->default('VALIDADA');
            $table->text('xml_representacion')->nullable(); // For simulation, maybe store the fake XML struct here

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('aporte_id')->references('id')->on('aportes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
};
