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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del método de pago (ej: "Banco Nacional", "Tigo Money")
            $table->string('type'); // Tipo: 'bank', 'mobile_payment', 'digital_wallet', etc.
            $table->text('description')->nullable(); // Descripción del método
            $table->string('account_number')->nullable(); // Número de cuenta o teléfono
            $table->string('account_holder')->nullable(); // Titular de la cuenta
            $table->string('qr_image')->nullable(); // Ruta de la imagen QR
            $table->boolean('is_active')->default(true); // Estado activo/inactivo
            $table->integer('sort_order')->default(0); // Orden de visualización
            $table->json('additional_info')->nullable(); // Información adicional en JSON
            $table->timestamps();
            $table->softDeletes(); // Para eliminación suave
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
};
