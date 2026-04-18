<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'address'))     $table->text('address')->nullable()->after('city');
            if (!Schema::hasColumn('stores', 'province'))    $table->string('province')->nullable()->after('address');
            if (!Schema::hasColumn('stores', 'district'))    $table->string('district')->nullable()->after('province');
            if (!Schema::hasColumn('stores', 'village'))     $table->string('village')->nullable()->after('district');
            if (!Schema::hasColumn('stores', 'postal_code')) $table->string('postal_code', 10)->nullable()->after('village');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            //
        });
    }
};
