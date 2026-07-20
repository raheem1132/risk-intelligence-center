<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ports', function (Blueprint $table) {
            // Mengubah kolom country_id menjadi boleh dikosongkan (nullable)
            $table->unsignedBigInteger('country_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable(false)->change();
        });
    }
};