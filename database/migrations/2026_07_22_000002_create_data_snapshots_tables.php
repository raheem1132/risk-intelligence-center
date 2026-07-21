<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('economic_indicators', function (Blueprint $table) {
            $table->id(); $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year'); $table->decimal('gdp', 22, 2)->nullable();
            $table->decimal('inflation', 10, 4)->nullable(); $table->unsignedBigInteger('population')->nullable();
            $table->timestamps(); $table->unique(['country_id', 'year']);
        });
        Schema::create('weather_snapshots', function (Blueprint $table) {
            $table->id(); $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->decimal('temperature', 7, 2)->nullable(); $table->decimal('precipitation', 9, 2)->nullable();
            $table->decimal('wind_speed', 8, 2)->nullable(); $table->unsignedTinyInteger('weather_code')->nullable();
            $table->unsignedTinyInteger('risk_score'); $table->timestamp('observed_at'); $table->timestamps();
        });
        Schema::create('currency_snapshots', function (Blueprint $table) {
            $table->id(); $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('base_currency', 3)->default('USD'); $table->string('quote_currency', 3);
            $table->decimal('rate', 20, 8); $table->decimal('change_percent', 10, 4)->default(0);
            $table->timestamp('observed_at'); $table->timestamps();
        });
        Schema::table('ports', function (Blueprint $table) {
            $table->string('wpi_number')->nullable()->unique();
            $table->string('harbor_size')->nullable(); $table->string('harbor_type')->nullable();
            $table->string('source')->default('NGA World Port Index');
        });
    }
    public function down(): void
    {
        Schema::table('ports', fn (Blueprint $table) => $table->dropColumn(['wpi_number','harbor_size','harbor_type','source']));
        Schema::dropIfExists('currency_snapshots'); Schema::dropIfExists('weather_snapshots'); Schema::dropIfExists('economic_indicators');
    }
};
