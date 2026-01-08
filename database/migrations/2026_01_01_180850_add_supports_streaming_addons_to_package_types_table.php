<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_types', function (Blueprint $table) {
            $table->boolean('supports_streaming_addons')->default(false)->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('package_types', function (Blueprint $table) {
            $table->dropColumn('supports_streaming_addons');
        });
    }
};