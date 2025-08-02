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
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->timestamp('earned_at')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('achievement_id');
            $table->unsignedBigInteger('inscrito_id');
            $table->foreign('inscrito_id')->references('id')->on('inscritos')->onDelete('cascade');

            $table->foreign('achievement_id')
                  ->references('id')
                  ->on('achievements')
                  ->onDelete('cascade');

            $table->unique(['inscrito_id', 'achievement_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};
