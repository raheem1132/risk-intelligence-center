<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        if(!Schema::hasColumn('news_cache','keyword')) Schema::table('news_cache', fn(Blueprint $table)=>$table->string('keyword')->nullable()->index());
        if(!Schema::hasColumn('news_cache','payload')) Schema::table('news_cache', fn(Blueprint $table)=>$table->longText('payload')->nullable());
        if(!Schema::hasColumn('news_cache','url')) Schema::table('news_cache', fn(Blueprint $table)=>$table->string('url')->nullable());
    }
    public function down(): void { Schema::table('news_cache', fn(Blueprint $table)=>$table->dropColumn(['keyword','payload','url'])); }
};
