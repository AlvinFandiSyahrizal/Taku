<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('height', 8, 2)->nullable()->after('discount_percent');
            $table->enum('height_unit', ['cm', 'meter'])->default('cm')->after('height');
            $table->decimal('diameter', 8, 2)->nullable()->after('height_unit');
            $table->enum('diameter_unit', ['cm', 'meter'])->default('cm')->after('diameter');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['height', 'height_unit', 'diameter', 'diameter_unit']);
        });
    }
};
