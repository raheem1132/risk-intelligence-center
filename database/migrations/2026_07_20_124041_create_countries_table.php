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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code_iso2', 2)->unique(); // ID, CN, DE, AU
            $table->string('region')->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->string('language')->nullable();
            $table->bigInteger('population')->nullable();
            $table->double('gdp', 15, 2)->nullable();
            $table->double('inflation_rate', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};