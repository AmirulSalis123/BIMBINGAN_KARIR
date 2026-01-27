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
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (!Schema::hasColumn('events', 'lokasi_id')) {
                    $table->foreignId('lokasi_id')
                          ->nullable()
                          ->after('kategori_id')
                          ->constrained('lokasis')
                          ->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                // Hapus foreign key jika ada
                if (Schema::hasColumn('events', 'lokasi_id')) {
                    $table->dropForeign(['lokasi_id']);
                    $table->dropColumn('lokasi_id');
                }

            });
        }

        Schema::dropIfExists('lokasis');
    }
};
