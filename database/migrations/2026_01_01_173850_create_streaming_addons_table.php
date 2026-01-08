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
        Schema::create('streaming_addons', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // "Star Movie", "Planet Movie", dll
            $table->string('key')->unique();      // "star-movie", "planet-movie", dll
            $table->string('color')->default('#8B5CF6'); // Warna default ungu
            $table->string('icon')->nullable();   // Optional icon key
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('package_streaming_addon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('streaming_addon_id')->constrained('streaming_addons')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_streaming_addon');
        Schema::dropIfExists('streaming_addons');
    }
};