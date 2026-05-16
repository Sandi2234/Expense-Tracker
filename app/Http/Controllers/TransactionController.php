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

        // Query dasar transaksi milik user yang sedang login
        $query = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year);

        // Ambil data untuk kalkulasi dashboard
        $transactions = $query->orderBy('date', 'desc')->get();
        
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;

        // Ambil kategori untuk pilihan di form (Kategori sistem + Kategori buatan user)
        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', $userId)
            ->get();

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
        Transaction::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

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