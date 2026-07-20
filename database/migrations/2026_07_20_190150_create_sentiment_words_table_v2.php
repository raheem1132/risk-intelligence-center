<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kita pakai if untuk memastikan tidak bentrok
        if (!Schema::hasTable('sentiment_words')) {
            Schema::create('sentiment_words', function (Blueprint $table) {
                $table->id();
                $table->string('word')->unique();
                $table->string('type');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('sentiment_words');
    }
};