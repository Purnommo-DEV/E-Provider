<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tv_addons', function (Blueprint $table) {
            $table->id();
            $table->integer('price')->default(20000); // Rp 20.000
            $table->integer('channel_count')->default(70); // 70+ channel
            $table->string('device_image')->nullable(); // gambar box + remote
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tv_addons');
    }
};