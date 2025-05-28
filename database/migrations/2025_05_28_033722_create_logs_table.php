<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('level'); // info, warning, error, etc.
            $table->string('message');
            $table->text('context')->nullable(); // dados adicionais em JSON
            $table->string('channel')->default('default'); // canal do log
            $table->timestamps();

            // Índices para performance
            $table->index('level');
            $table->index('channel');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
