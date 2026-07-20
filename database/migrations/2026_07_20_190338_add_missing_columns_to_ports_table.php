<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ports', function (Blueprint $table) {
            if (!Schema::hasColumn('ports', 'country_code')) {
                $table->string('country_code', 5)->nullable()->after('port_name');
            }
            if (!Schema::hasColumn('ports', 'country_name')) {
                $table->string('country_name')->nullable()->after('country_code');
            }
            if (!Schema::hasColumn('ports', 'risk_status')) {
                $table->string('risk_status')->default('Low')->after('longitude');
            }
            if (!Schema::hasColumn('ports', 'risk_score')) {
                $table->decimal('risk_score', 5, 2)->default(0.00)->after('risk_status');
            }
            if (!Schema::hasColumn('ports', 'details')) {
                $table->text('details')->nullable()->after('risk_score');
            }
        });
    }

    public function down()
    {
        Schema::table('ports', function (Blueprint $table) {
            // Biarkan kosong saja lek biar aman
        });
    }
};