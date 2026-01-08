<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // hanya Residensial
            $table->string('name');                  // "Perbulan", "12 Get 6", "9 Get 3", "5 Get 1"
            $table->integer('months_paid');           // e.g., 12
            $table->integer('months_free');           // e.g., 6
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_promos');
    }
};