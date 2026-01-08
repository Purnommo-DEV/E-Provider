<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();

            $table->string('title');                // judul keunggulan
            $table->text('description');            // deskripsi (bisa dari editor)
            $table->string('icon', 100)->nullable(); // icon class / name
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);   // urutan tampil

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
