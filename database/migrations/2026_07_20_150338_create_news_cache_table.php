<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('news_cache', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 5)->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source_name')->nullable();
            $table->string('sentiment_result')->default('Neutral'); // Positive, Negative, Neutral
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_cache');
    }
};