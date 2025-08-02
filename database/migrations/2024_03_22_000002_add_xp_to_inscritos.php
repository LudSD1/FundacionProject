<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscritos', function (Blueprint $table) {
            $table->integer('xp')->default(0);
            $table->timestamp('last_activity_at')->nullable();
        });

        Schema::create('xp_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscrito_id')->constrained()->onDelete('cascade');
            $table->integer('amount');
            $table->string('reason');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('inscritos', function (Blueprint $table) {
            $table->dropColumn(['xp', 'last_activity_at']);
        });

        Schema::dropIfExists('xp_history');
    }
}; 