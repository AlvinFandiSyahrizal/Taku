<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('store_sections')) {
            return;
        }

        Schema::create('store_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->integer('rows')->default(1);
            $table->boolean('auto_slide')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort')->default(0);
            $table->timestamps();
        });

        if (Schema::hasTable('store_section_products')) {
            return;
        }

        Schema::create('store_section_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_section_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('sort')->default(0);
            $table->foreign('store_section_id')
                  ->references('id')->on('store_sections')
                  ->cascadeOnDelete();
            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('store_section_products');
        Schema::dropIfExists('store_sections');
    }
};