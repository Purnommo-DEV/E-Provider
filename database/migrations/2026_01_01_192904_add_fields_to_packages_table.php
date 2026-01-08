<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->boolean('has_tv')->default(false)->after('image');
            $table->integer('channel_count')->nullable()->after('has_tv');
            $table->string('stb_info')->nullable()->after('channel_count');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['has_tv', 'channel_count', 'stb_info']);
        });
    }
};