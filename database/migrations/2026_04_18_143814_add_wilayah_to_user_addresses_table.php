<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('user_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('user_addresses', 'province'))
                $table->string('province')->nullable()->after('city');
            if (!Schema::hasColumn('user_addresses', 'district'))
                $table->string('district')->nullable()->after('province');
            if (!Schema::hasColumn('user_addresses', 'village'))
                $table->string('village')->nullable()->after('district');
            if (!Schema::hasColumn('user_addresses', 'postal_code'))
                $table->string('postal_code', 10)->nullable()->after('village');
            if (!Schema::hasColumn('user_addresses', 'province_code'))
                $table->string('province_code', 10)->nullable()->after('postal_code');
            if (!Schema::hasColumn('user_addresses', 'regency_code'))
                $table->string('regency_code', 10)->nullable()->after('province_code');
            if (!Schema::hasColumn('user_addresses', 'district_code'))
                $table->string('district_code', 10)->nullable()->after('regency_code');
        });
    }
    public function down(): void {}
};