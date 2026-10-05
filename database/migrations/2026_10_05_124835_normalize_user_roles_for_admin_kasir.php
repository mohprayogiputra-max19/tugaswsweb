<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'role')) {
            throw new RuntimeException('Tabel users dan kolom role harus tersedia sebelum migrasi role dijalankan.');
        }

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role VARCHAR(50) NOT NULL DEFAULT 'kasir'");
        }

        DB::table('users')->where('role', 'user')->update(['role' => 'kasir']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'kasir')->update(['role' => 'user']);

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
        }
    }
};
