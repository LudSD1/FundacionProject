<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Reglas de recomendación configurables
        Schema::create('recommendation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();          // ej: CATEGORY_AFFINITY
            $table->string('display_name');             // ej: Afinidad por Categoría
            $table->text('description')->nullable();
            $table->unsignedInteger('weight')->default(10); // peso 0-100
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable();         // parámetros extra por regla
            $table->timestamps();
        });

        // Log de recomendaciones mostradas (para análisis y mejora)
        Schema::create('recommendation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->decimal('score', 8, 4);
            $table->json('rules_applied')->nullable();  // detalle de scores por regla
            $table->boolean('clicked')->default(false);
            $table->timestamp('clicked_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_logs');
        Schema::dropIfExists('recommendation_rules');
    }
};
