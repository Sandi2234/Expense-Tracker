<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Http\Requests\StoreTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // 1. DASHBOARD & LIST TRANSAKSI
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Filter Bulan (Default ke bulan berjalan saat ini)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Optimasi Performa: Menggunakan Rentang Tanggal (Mendukung Database Indexing)
        $startDate = "{$year}-{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        // Query dasar transaksi milik user yang sedang login
        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
        
        // Kalkulasi dashboard langsung dari koleksi data di memori (Efisien)
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;

        // FIX SECURITY: Mencegah kebocoran data kategori antar user dengan Query Grouping
        $categories = Category::where(function ($query) use ($userId) {
            $query->whereNull('user_id')
                  ->orWhere('user_id', $userId);
        })->get();

        return view('dashboard', compact(
            'transactions', 
            'totalIncome', 
            'totalExpense', 
            'totalBalance', 
            'categories',
            'month',
            'year'
        ));
    }

    // 2. SIMPAN TRANSAKSI BARU (CREATE)
    public function store(StoreTransactionRequest $request)
    {
        // Memastikan hanya data tervalidasi yang masuk + inject user_id secara aman
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        Transaction::create($validated);

        return redirect()->back()->with('success', 'Transaksi berhasil dicatat!');
    }

    // 3. UPDATE TRANSAKSI
    public function update(StoreTransactionRequest $request, Transaction $transaction)
    {
        // Proteksi: Pastikan transaksi ini benar milik user yang request (Keamanan Data)
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        $transaction->update($request->validated());

        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui!');
    }

    // 4. HAPUS TRANSAKSI (DELETE)
    public function destroy(Transaction $transaction)
    {
        // Proteksi Keamanan
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        $transaction->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
    }
}