<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_xp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inscrito_id');
            $table->integer('current_xp')->default(0);
            $table->integer('total_xp_earned')->default(0);
            $table->integer('current_level')->default(1);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('inscrito_id')
                  ->references('id')
                  ->on('inscritos')
                  ->onDelete('cascade');

            $table->foreign('current_level')
                  ->references('level_number')
                  ->on('levels')
                  ->onDelete('restrict');

            $table->unique('inscrito_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_xp');
    }
}; 