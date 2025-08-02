<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->string('primary_color')->default('#000000')->after('template_back_path');
            $table->string('font_family')->default('Arial')->after('primary_color');
            $table->integer('font_size')->default(12)->after('font_family'); // Tamaño de fuente en puntos
        });
    }

    public function down(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'font_family', 'font_size']);
        });
    }
};

