<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon');
            $table->string('type');
            $table->integer('requirement_value');
            $table->string('category')->nullable();
            $table->integer('xp_reward');
            $table->boolean('is_secret')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('achievement_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('earned_at');
            $table->timestamps();

            $table->unique(['achievement_id', 'user_id']);
        });

        Schema::create('achievement_inscrito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->foreignId('inscrito_id')->constrained('inscritos')->onDelete('cascade');
            $table->timestamp('earned_at');
            $table->timestamps();

            $table->unique(['achievement_id', 'inscrito_id']);
        });
    }

    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        Schema::dropIfExists('achievement_inscrito');
        Schema::dropIfExists('achievement_user');
        Schema::dropIfExists('achievements');
    }
};