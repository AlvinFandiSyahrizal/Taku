<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('height', 8, 2)->nullable();
            $table->enum('height_unit', ['cm', 'meter'])->default('cm');
            $table->decimal('diameter', 8, 2)->nullable();
            $table->enum('diameter_unit', ['cm', 'meter'])->default('cm');
            $table->bigInteger('price');           // harga variant ini
            $table->integer('stock')->default(0);  // stok variant ini
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
