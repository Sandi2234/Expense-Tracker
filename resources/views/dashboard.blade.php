<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-200">
                {{ __('Expense Tracker') }}
            </h2>
            <div class="hidden sm:flex items-center space-x-3">
                <span class="text-sm text-gray-500 dark:text-gray-400 transition-colors duration-200">Halo, {{ Auth::user()->name }}!</span>
                <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-950 border border-indigo-200 dark:border-indigo-800 flex items-center justify-center text-indigo-700 dark:text-indigo-400 text-xs font-bold transition-colors duration-200">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-200 pb-24 sm:pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
            
            <div class="flex sm:hidden justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm transition-colors duration-200" 
                x-data="{ 
                    isDarkMode: document.documentElement.classList.contains('dark'),
                    toggleTheme() {
                        if (this.isDarkMode) {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('theme', 'light');
                        } else {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('theme', 'dark');
                        }
                        this.isDarkMode = !this.isDarkMode;
                    }
                }">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-black shadow-md shadow-indigo-200 dark:shadow-none">
                        💰
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Halo, Selamat Pagi</p>
                        <h4 class="text-base font-black text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</h4>
                    </div>
                </div>
                
                <button @click="toggleTheme()" 
                        type="button"
                        class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-lg border border-gray-100 dark:border-gray-600 shadow-sm active:scale-90 transition-all duration-200 focus:outline-none">
                    <span x-show="!isDarkMode">🌙</span>
                    <span x-show="isDarkMode" x-cloak>☀️</span>
                </button>
            </div>

            @if(session('success'))
                <div class="p-4 text-sm text-green-800 dark:text-green-300 rounded-2xl bg-green-50 dark:bg-green-950/50 border border-green-100 dark:border-green-900 flex items-center gap-3" role="alert">
                    <span class="p-1 bg-green-100 dark:bg-green-900 rounded-md">✅</span>
                    <div><span class="font-semibold">Sukses!</span> {{ session('success') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 text-sm text-red-800 dark:text-red-300 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-100 dark:border-red-900 flex flex-col gap-1" role="alert">
                    <div class="flex items-center gap-3">
                        <span class="p-1 bg-red-100 dark:bg-red-900 rounded-md">⚠️</span>
                        <span class="font-semibold">Gagal Menyimpan:</span>
                    </div>
                    <ul class="list-disc list-inside pl-8 mt-1 text-xs text-red-700 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-gradient-to-br from-indigo-600 to-violet-700 p-6 rounded-[2.5rem] shadow-xl text-white md:hidden space-y-6">
                <div>
                    <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest">Total Saldo Kamu</p>
                    <h3 class="text-3xl font-black mt-1">
                        Rp {{ number_format($totalBalance, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-indigo-500/30">
                    <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-sm">
                        <p class="text-[9px] font-bold text-indigo-200 uppercase tracking-wider">Pemasukan</p>
                        <p class="text-sm font-black text-emerald-300 mt-0.5">▲ Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-sm">
                        <p class="text-[9px] font-bold text-indigo-200 uppercase tracking-wider">Pengeluaran</p>
                        <p class="text-sm font-black text-rose-300 mt-0.5">▼ Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between hover:shadow-md transition-all duration-200">
                    <div>
                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Total Saldo Aktif</p>
                        <h3 class="text-3xl font-black mt-2 {{ $totalBalance >= 0 ? 'text-gray-800 dark:text-gray-100' : 'text-rose-600 dark:text-rose-400' }}">
                            Rp {{ number_format($totalBalance, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="mt-4 text-xs text-gray-400 dark:text-gray-500 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 mr-2"></span> Akumulasi bulan ini
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between hover:shadow-md transition-all duration-200">
                    <div>
                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Total Pemasukan</p>
                        <h3 class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-2">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="mt-4 text-xs text-emerald-500 dark:text-emerald-400 font-medium flex items-center">
                        <span class="mr-1">▲</span> Berhasil dicatat
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between hover:shadow-md transition-all duration-200">
                    <div>
                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Total Pengeluaran</p>
                        <h3 class="text-3xl font-black text-rose-600 dark:text-rose-400 mt-2">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="mt-4 text-xs text-rose-500 dark:text-rose-400 font-medium flex items-center">
                        <span class="mr-1">▼</span> Total konsumsi dana
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 lg:col-span-1 transition-all duration-200" x-data="{ type: 'expense' }">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-6 flex items-center">
                        <span class="p-2 bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 rounded-xl mr-3 text-xl">✍</span> 
                        Catat Transaksi Baru
                    </h4>

                    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="type" :value="type">

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Jenis Transaksi</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative border-2 rounded-2xl p-4 flex flex-col items-center justify-center cursor-pointer transition-all duration-200"
                                    :class="type === 'expense' ? 'border-rose-500 bg-rose-50 dark:bg-rose-950/30 ring-4 ring-rose-100 dark:ring-rose-900/30' : 'border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 opacity-60'" @click="type = 'expense'">
                                    <span class="text-2xl mb-1">💸</span>
                                    <span class="text-xs font-bold text-rose-700 dark:text-rose-400">PENGELUARAN</span>
                                </label>
                                <label class="relative border-2 rounded-2xl p-4 flex flex-col items-center justify-center cursor-pointer transition-all duration-200"
                                    :class="type === 'income' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/30 ring-4 ring-emerald-100 dark:ring-emerald-900/30' : 'border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 opacity-60'" @click="type = 'income'">
                                    <span class="text-2xl mb-1">💰</span>
                                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">PEMASUKAN</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Nominal (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 font-bold">Rp</span>
                                <input type="number" step="0.01" name="amount" id="amount" required class="pl-12 block w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 py-3 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="0" value="{{ old('amount') }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Kategori</label>
                            <select name="category_id" id="category_id" required class="block w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 py-3 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="" class="dark:bg-gray-800">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="dark:bg-gray-800">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Tanggal</label>
                            <input type="date" name="date" id="date" required value="{{ old('date', date('Y-m-d')) }}" class="block w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 py-3 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Catatan</label>
                            <input type="text" name="description" id="description" class="block w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 py-3 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Misal: Kopi susu" value="{{ old('description') }}">
                        </div>

                        <button type="submit" class="w-full bg-gray-900 dark:bg-indigo-600 hover:bg-black dark:hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl transition-all active:scale-95">
                            Simpan Transaksi
                        </button>
                    </form>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 lg:col-span-2 transition-all duration-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200">Riwayat Terakhir</h4>
                            <p class="hidden sm:block text-sm text-gray-400 dark:text-gray-500">Menampilkan aktivitas keuangan terbaru</p>
                        </div>
                        
                        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center bg-gray-50 dark:bg-gray-900 p-1 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <select name="month" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold text-gray-600 dark:text-gray-400 focus:ring-0 cursor-pointer px-3 py-1">
                                @for ($m=1; $m<=12; $m++)
                                    <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }} class="dark:bg-gray-800">
                                        {{ date('M', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </form>
                    </div>

                    <div class="space-y-4">
                        @if($transactions->isEmpty())
                            <p class="py-8 text-center text-sm text-gray-400 dark:text-gray-500">Belum ada transaksi bulan ini.</p>
                        @else
                            @foreach($transactions as $tx)
                                <div class="flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20 p-4 rounded-2xl border border-gray-50 dark:border-gray-700/50 group hover:shadow-sm transition">
                                    <div class="flex items-center space-x-3.5">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm" style="background-color: {{ $tx->category?->color ?? '#6B7280' }}">
                                            {{ strtoupper(substr($tx->category?->name ?? 'T', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $tx->description ?? ($tx->category?->name ?? 'Transaksi') }}</h5>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-semibold uppercase mt-0.5">
                                                {{ \Carbon\Carbon::parse($tx->date)->translatedFormat('d M Y') }} • {{ $tx->category?->name }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm font-black {{ $tx->type == 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                            {{ $tx->type == 'income' ? '+' : '-' }}Rp{{ number_format($tx->amount, 0, ',', '.') }}
                                        </span>
                                        <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1 text-gray-300 dark:text-gray-600 hover:text-rose-500 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 py-3 px-6 flex justify-between items-center md:hidden z-50 shadow-lg">
        <a href="#" class="flex flex-col items-center space-y-1 text-indigo-600 dark:text-indigo-400">
            <span class="text-lg">🏠</span>
            <span class="text-[9px] font-bold">Beranda</span>
        </a>
        <a href="#" class="flex flex-col items-center space-y-1 text-gray-400 hover:text-gray-600">
            <span class="text-lg">📊</span>
            <span class="text-[9px] font-medium">Laporan</span>
        </a>
        <div class="-mt-8">
            <button class="w-12 h-12 rounded-full bg-slate-900 dark:bg-indigo-600 text-white flex items-center justify-center text-xl font-bold shadow-lg active:scale-95 transition-transform">
                +
            </button>
        </div>
        <a href="#" class="flex flex-col items-center space-y-1 text-gray-400 hover:text-gray-600">
            <span class="text-lg">⏱️</span>
            <span class="text-[9px] font-medium">Riwayat</span>
        </a>
        <a href="#" class="flex flex-col items-center space-y-1 text-gray-400 hover:text-gray-600">
            <span class="text-lg">👤</span>
            <span class="text-[9px] font-medium">Profil</span>
        </a>
    </div>
</x-app-layout>