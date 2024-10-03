<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('intro_url');
            $table->string('image');
            $table->double('price');
            $table->double('price_sale');
            $table->text('description');
            $table->boolean('status')->default(1);
            $table->enum('level', ['basic', 'intermediate', 'advanced'])->default('basic');
            $table->json('requirements')->nullable();
            $table->json('benefits')->nullable();
            $table->json('qa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
