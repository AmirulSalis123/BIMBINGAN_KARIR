<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        // Cek dulu biar gak error kalau kolomnya ternyata sudah ada
        if (!Schema::hasColumn('orders', 'status')) {
            $table->string('status')->default('success')->after('total_harga');
        }
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        if (Schema::hasColumn('orders', 'status')) {
            $table->dropColumn('status');
        }
    });
}
};
