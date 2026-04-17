<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->integer('rejection_count')->default(0)->after('reject_reason');
            $table->timestamp('rejected_at')->nullable()->after('rejection_count');
            $table->timestamp('resubmitted_at')->nullable()->after('rejected_at');
            $table->string('city')->nullable()->after('phone');
            $table->boolean('agreed_terms')->default(false)->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['rejection_count','rejected_at','resubmitted_at','city','agreed_terms']);
        });
    }
};
