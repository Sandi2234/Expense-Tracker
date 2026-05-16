<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Mengarahkan halaman utama langsung ke login jika belum auth
Route::get('/', function () {
    return redirect()->route('login');
});

// Group Route yang wajib LOGIN (Diproteksi Middleware Auth)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Gantikan route dashboard bawaan breeze dengan controller kita
    Route::get('/dashboard', [TransactionController::class, 'index'])->name('dashboard');
    
    // Resource Route untuk CRUD Transaksi
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});