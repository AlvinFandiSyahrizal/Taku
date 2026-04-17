<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_code')->unique();  
            $table->string('name');                  
            $table->string('phone');                 
            $table->text('address');                 
            $table->text('note')->nullable();        
            $table->bigInteger('total');            
            $table->enum('status', [
                'pending',       
                'confirmed',     
                'shipped',       
                'completed',     
                'cancelled',     
            ])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
