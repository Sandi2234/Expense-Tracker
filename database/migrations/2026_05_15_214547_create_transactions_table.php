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
        Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        // Menghubungkan transaksi ke user yang membuatnya
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // Menghubungkan transaksi ke kategori yang dipilih
        $table->foreignId('category_id')->constrained()->onDelete('restrict'); 
        
        $table->enum('type', ['income', 'expense']); // Pilihan: pemasukan atau pengeluaran
        $table->decimal('amount', 15, 2);            // Nominal keuangan (mendukung angka besar)
        $table->date('date');                        // Tanggal transaksi
        $table->text('description')->nullable();     // Catatan opsional
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
