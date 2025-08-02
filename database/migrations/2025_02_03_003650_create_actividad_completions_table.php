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
        Schema::create('actividad_completions', function (Blueprint $table) {
            $table->id();
            $table->morphs('completable');
            $table->unsignedBigInteger('inscritos_id');
            $table->foreign('inscritos_id')->references('id')->on('inscritos')->onDelete('cascade'); // Added cascade
            $table->index('inscritos_id'); // Added index
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_completions');
    }
};
