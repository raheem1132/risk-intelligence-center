<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('news_cache', fn (Blueprint $table) => $table->string('country_code', 16)->nullable()->change());
    }

    public function down(): void
    {
        Schema::table('news_cache', fn (Blueprint $table) => $table->string('country_code', 5)->nullable()->change());
    }
};
