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
        Schema::table('tv_addons', function (Blueprint $table) {
            $table->string('title')->default('Hiburan Terlengkap Untuk Keluarga');
            $table->text('subtitle')->nullable()->after('title');
            $table->text('description')->nullable()->after('subtitle');
            $table->string('price_text')->nullable()->after('price'); // e.g., "Mulai dari Rp 20 ribuan / bulan"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tv_addons', function (Blueprint $table) {
            //
        });
    }
};
