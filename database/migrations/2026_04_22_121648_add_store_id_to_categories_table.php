<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // NULL  = kategori global (dibuat admin, terlihat oleh semua)
            // ada   = kategori milik store tersebut saja
            $table->foreignId('store_id')
                  ->nullable()
                  ->after('parent_id')
                  ->constrained('stores')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Store::class);
            $table->dropColumn('store_id');
        });
    }
};
