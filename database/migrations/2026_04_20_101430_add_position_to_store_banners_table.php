<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('store_banners', function (Blueprint $table) {
            $table->enum('position', ['top', 'after_sections', 'bottom'])
                  ->default('top')
                  ->after('sort');
        });
    }

    public function down(): void
    {
        Schema::table('store_banners', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};