<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('risk_threshold')->default(65);
            $table->unsignedTinyInteger('refresh_interval')->default(10);
            $table->string('timezone', 50)->default('Asia/Jakarta');
            $table->string('base_currency', 3)->default('USD');
            $table->string('density', 20)->default('comfortable');
            $table->boolean('email_alerts')->default(true);
            $table->boolean('browser_alerts')->default(false);
            $table->boolean('weekly_digest')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
