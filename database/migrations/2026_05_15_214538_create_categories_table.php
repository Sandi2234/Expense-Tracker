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
        Schema::create('categories', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel users (boleh null jika kategori bawaan sistem)
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); 
        $table->string('name');
        $table->string('slug');
        $table->string('color')->default('#6B7280'); // Warna default abu-abu Tailwind
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
