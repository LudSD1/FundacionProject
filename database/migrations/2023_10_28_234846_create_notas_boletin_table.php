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
        Schema::create('notas_boletin', function (Blueprint $table) {
            $table->id();
            $table->string('nota_nombre');
            $table->integer('nota');
            $table->unsignedBigInteger('boletin_id');
            $table->foreign('boletin_id')->references('id')->on('boletin')->onDelete('cascade');
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
        Schema::dropIfExists('notas_boletin');
    }
};
