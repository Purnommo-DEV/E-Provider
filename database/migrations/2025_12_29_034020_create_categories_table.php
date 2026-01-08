<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  // "Residensial", "Bisnis SME"
            $table->string('slug')->unique();        // "residensial", "bisnis-sme"
            $table->text('description')->nullable(); // judul section, e.g., "Internetan Super Lancar dan Unlimited!"
            $table->text('subtitle')->nullable();    // subjudul, e.g., "Hemat Besar Mulai Rp 235.000..."
            $table->boolean('has_payment_promo')->default(false); // true hanya untuk Residensial
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};