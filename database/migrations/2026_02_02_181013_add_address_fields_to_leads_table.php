<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->text('address')->nullable()->after('phone');
            $table->string('kelurahan')->nullable()->after('address');
            $table->string('rt', 10)->nullable()->after('kelurahan');
            $table->string('blok', 20)->nullable()->after('rt');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'kelurahan',
                'rt',
                'blok'
            ]);
        });
    }
};