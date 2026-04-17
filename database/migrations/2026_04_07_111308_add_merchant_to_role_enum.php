<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'merchant', 'admin') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        \DB::statement("UPDATE users SET role = 'user' WHERE role = 'merchant'");
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') NOT NULL DEFAULT 'user'");
    }
};