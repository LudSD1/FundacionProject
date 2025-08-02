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
        Schema::create('xp_events', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('curso_id');
            $table->unsignedBigInteger('xp_event_type_id');

            $table->morphs('origen');

            $table->integer('xp');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
            $table->foreign('xp_event_type_id')->references('id')->on('xp_event_types')->onDelete('restrict');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xp_events');
    }
};
