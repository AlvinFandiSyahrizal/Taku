<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Simpan sebagai string, bukan FK — supaya histori order tidak hilang
            // kalau variant dihapus di kemudian hari
            $table->string('variant_label')->nullable()->after('product_image');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('variant_label');
        });
    }
};
