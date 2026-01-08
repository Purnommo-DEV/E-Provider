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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            // FILTER LANDING PAGE
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_promo_id')->nullable()->constrained()->nullOnDelete();

            // INFO UTAMA
            $table->string('name');
            $table->integer('speed_mbps');
            $table->integer('speed_up_to_mbps')->nullable();
            $table->enum('internet_type', ['unlimited', 'quota'])->default('unlimited');

            // BILLING
            $table->enum('billing_type', ['monthly', 'multi_month']);
            $table->integer('duration_month')->default(1);
            $table->bigInteger('base_price');

            // TAX
            $table->boolean('tax_included')->default(false);

            // STATUS
            $table->boolean('is_default')->default(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
