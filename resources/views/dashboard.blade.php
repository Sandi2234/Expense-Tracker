<x-app-layout>
    <style>
        body { -webkit-tap-highlight-color: transparent; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .animate-pop { animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes pop { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    </style>

    <div 
        x-data="financeApp({
            initialBalance: {{ $totalBalance }},
            initialIncome: {{ $totalIncome }},
            initialExpense: {{ $totalExpense }},
            initialTransactions: [
                @foreach($transactions as $tx)
                { 
                    id: {{ $tx->id }}, 
                    type: '{{ $tx->type }}', 
                    amount: {{ $tx->amount }}, 
                    note: '{{ $tx->description ?? ($tx->category?->name ?? 'Transaksi') }}', 
                    category: '{{ $tx->category?->name ?? 'General' }}', 
                    time: '{{ \Carbon\Carbon::parse($tx->date)->translatedFormat('d M') }}' 
                },
                @endforeach
            ]
        })"
        class="min-h-screen transition-colors duration-300 pb-24 md:pb-8"
        :class="darkMode ? 'bg-slate-900 text-white' : 'bg-slate-50 text-slate-900'"
    >
        <header class="sticky top-0 z-30 backdrop-blur-md border-b px-5 py-4 transition-colors"
                :class="darkMode ? 'bg-slate-900/80 border-slate-800' : 'bg-white/80 border-slate-100'">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div @click="toggleTheme()" class="w-10 h-10 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200 cursor-pointer active:scale-90 transition-transform">
                        <template x-if="!darkMode">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        </template>
                        <template x-if="darkMode">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </template>
                    </div>
                    <div>
                        <h1 class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Financely</h1>
                        <p class="font-extrabold" :class="darkMode ? 'text-white' : 'text-slate-800'">{{ Auth::user()->name }}</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-6 mr-4 font-bold text-sm">
                    <button @click="activeTab = 'home'" class="transition-colors py-2 px-3 rounded-xl" :class="activeTab === 'home' ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/40' : 'text-slate-400 hover:text-slate-600'">Beranda</button>
                    <button @click="activeTab = 'reports'" class="transition-colors py-2 px-3 rounded-xl" :class="activeTab === 'reports' ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/40' : 'text-slate-400 hover:text-slate-600'">Laporan</button>
                    <button @click="activeTab = 'history'" class="transition-colors py-2 px-3 rounded-xl" :class="activeTab === 'history' ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/40' : 'text-slate-400 hover:text-slate-600'">Riwayat</button>
                </div>

                <button @click="activeTab = 'profile'" class="relative">
                    <div class="w-10 h-10 rounded-2xl border-2 border-white shadow-md bg-indigo-100 dark:bg-indigo-950 flex items-center justify-center text-indigo-700 dark:text-indigo-400 text-xs font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                </button>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-5 mt-6">
            
            <div x-show="activeTab === 'home'" x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <div class="lg:col-span-2 space-y-6">
                        <section class="bg-indigo-700 rounded-[2.5rem] p-6 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                            <div class="relative z-10">
                                <p class="text-indigo-200 text-sm font-medium">Total Saldo Kamu</p>
                                <h2 class="text-4xl font-black mt-1 tracking-tight" x-text="formatCurrency(totalBalance)">Rp 0</h2>
                                
                                <div class="mt-8 grid grid-cols-2 gap-4">
                                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4">
                                        <p class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest">Pemasukan</p>
                                        <p class="text-xl font-bold text-emerald-300 mt-1" x-text="'↑ ' + formatShort(totalIncome)"></p>
                                    </div>
                                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4">
                                        <p class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest">Pengeluaran</p>
                                        <p class="textxl font-bold text-rose-300 mt-1" x-text="'↓ ' + formatShort(totalExpense)"></p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-extrabold text-lg" :class="darkMode ? 'text-white' : 'text-slate-800'">Riwayat Terakhir</h3>
                                <button @click="activeTab = 'history'" class="text-indigo-600 font-bold text-xs uppercase tracking-widest hover:underline">Semua</button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3" :class="transactions.length > 1 ? 'md:grid-cols-2' : 'md:grid-cols-1'">
                                <template x-for="(tx, index) in transactions.slice(0, 6)" :key="tx.id">
                                    <div class="p-4 rounded-3xl flex items-center justify-between border transition-all animate-pop" :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100 hover:shadow-md'">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-inner" :class="getCategoryMeta(tx.category).bg" x-text="getCategoryMeta(tx.category).icon"></div>
                                            <div>
                                                <p class="font-bold leading-tight" :class="darkMode ? 'text-white' : 'text-slate-800'" x-text="tx.note"></p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="tx.category + ' • ' + tx.time"></p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-black text-lg" :class="tx.type === 'income' ? 'text-emerald-500' : 'text-rose-500'" x-text="(tx.type === 'income' ? '+' : '-') + formatShort(tx.amount)"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </section>
                    </div>

                    <div class="lg:col-span-1">
                        <section class="sticky top-24">
                            <div class="flex justify-between items-center mb-4 lg:mb-6">
                                <h3 class="font-extrabold text-lg" :class="darkMode ? 'text-white' : 'text-slate-800'">Transaksi Baru</h3>
                                <button @click="showForm = !showForm" class="text-indigo-600 font-bold text-sm lg:hidden" x-text="showForm ? 'Tutup' : 'Tambah'"></button>
                            </div>

                            <div x-show="showForm || window.innerWidth >= 1024" x-transition class="p-6 rounded-[2rem] shadow-sm border space-y-4" :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100'">
                                <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="type" :value="newTx.type">

                                    <div class="bg-slate-100 p-1.5 rounded-2xl flex relative overflow-hidden" :class="darkMode ? 'bg-slate-700' : 'bg-slate-100'">
                                        <div class="absolute inset-y-1.5 transition-all duration-300 rounded-xl" 
                                             :class="[ newTx.type === 'expense' ? 'left-1.5 w-[48%] bg-rose-500 shadow-lg shadow-rose-200' : 'left-[50.5%] w-[48%] bg-emerald-500 shadow-lg shadow-emerald-200' ]">
                                        </div>
                                        
                                        <button type="button" @click="newTx.type = 'expense'" class="flex-1 py-3 relative z-10 flex items-center justify-center gap-2 transition-colors duration-300" :class="newTx.type === 'expense' ? 'text-white font-extrabold' : 'text-slate-400 font-bold'">
                                            <span class="text-base"></span> <span class="text-xs uppercase tracking-widest">Keluar</span>
                                        </button>
                                        <button type="button" @click="newTx.type = 'income'" class="flex-1 py-3 relative z-10 flex items-center justify-center gap-2 transition-colors duration-300" :class="newTx.type === 'income' ? 'text-white font-extrabold' : 'text-slate-400 font-bold'">
                                            <span class="text-base"></span> <span class="text-xs uppercase tracking-widest">Masuk</span>
                                        </button>
                                    </div>

                                    <div class="space-y-3">
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                                            <input type="number" step="0.01" name="amount" x-model="newTx.amount" placeholder="Nominal" required class="w-full pl-12 border-0 rounded-2xl p-4 font-bold focus:ring-2 focus:ring-indigo-500 outline-none" :class="darkMode ? 'bg-slate-700 text-white' : 'bg-slate-50 text-slate-800'">
                                        </div>
                                        <input type="text" name="description" x-model="newTx.note" placeholder="Keterangan (e.g. Kopi)" class="w-full border-0 rounded-2xl p-4 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none" :class="darkMode ? 'bg-slate-700 text-white' : 'bg-slate-50 text-slate-800'">
                                        
                                        <select name="category_id" required class="w-full border-0 rounded-2xl p-4 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none appearance-none" :class="darkMode ? 'bg-slate-700 text-white' : 'bg-slate-50 text-slate-600'">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>

                                        <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl shadow-lg active:scale-95 transition-transform disabled:opacity-50" :disabled="!newTx.amount">
                                            Simpan Transaksi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'reports'" x-transition class="max-w-3xl mx-auto">
                <h3 class="font-extrabold text-2xl mb-4" :class="darkMode ? 'text-white' : 'text-slate-800'">Laporan Keuangan</h3>
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-6" :class="darkMode ? 'bg-slate-800 border-slate-700' : ''">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-bold text-slate-400 uppercase">Limit Bulanan</span>
                            <span class="text-sm font-black text-indigo-600" x-text="Math.round((totalExpense/5000000)*100) + '%'"></span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="bg-indigo-600 h-full transition-all duration-1000" :style="'width: ' + Math.min((totalExpense/5000000)*100, 100) + '%'"></div>
                        </div>
                        <p class="text-[10px] mt-2 font-bold text-slate-400 uppercase tracking-widest">Target: Rp 5.000.000</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                            <p class="text-[10px] font-bold text-emerald-600 uppercase">Saving Rate</p>
                            <p class="text-2xl font-black text-emerald-700 dark:text-emerald-400">65%</p>
                        </div>
                        <div class="p-4 bg-orange-50 dark:bg-orange-950/20 rounded-2xl border border-orange-100 dark:border-orange-900/50">
                            <p class="text-[10px] font-bold text-orange-600 uppercase">Bills Rate</p>
                            <p class="text-2xl font-black text-orange-700 dark:text-orange-400">12%</p>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'profile'" x-transition class="max-w-md mx-auto">
                 <div class="flex flex-col items-center mb-8">
                     <div class="relative mb-4">
                         <div class="w-24 h-24 rounded-[2.5rem] border-4 border-indigo-100 bg-indigo-600 flex items-center justify-center text-white text-3xl font-black shadow-xl">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                         </div>
                         <div class="absolute -right-2 -bottom-2 bg-indigo-600 text-white p-2 rounded-2xl shadow-lg cursor-pointer">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                         </div>
                     </div>
                     <h2 class="text-2xl font-black" :class="darkMode ? 'text-white' : 'text-slate-800'">{{ Auth::user()->name }}</h2>
                     <p class="text-slate-400 font-bold text-sm">{{ Auth::user()->email }}</p>
                     <span class="mt-3 px-4 py-1 bg-indigo-100 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-widest">Premium Member</span>
                 </div>

                 <div class="space-y-4">
                     <div @click="toggleTheme()" class="p-5 rounded-3xl border flex items-center justify-between cursor-pointer" :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100'">
                         <div class="flex items-center gap-4">
                             <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">🌙</div>
                             <span class="font-bold">Mode Gelap (Dark Mode)</span>
                         </div>
                         <button type="button" class="w-12 h-6 rounded-full transition-colors relative" :class="darkMode ? 'bg-indigo-600' : 'bg-slate-200'">
                             <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all" :class="darkMode ? 'left-7' : 'left-1'"></div>
                         </button>
                     </div>

                     <form method="POST" action="{{ route('logout') }}">
                         @csrf
                         <button type="submit" class="w-full p-5 rounded-3xl bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 font-bold flex items-center justify-center gap-2 border border-rose-100 dark:border-rose-900/30 active:scale-95 transition-transform">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                             Keluar Akun
                         </button>
                     </form>
                 </div>
            </div>

            <div x-show="activeTab === 'history'" x-transition class="max-w-3xl mx-auto">
                 <h3 class="font-extrabold text-2xl mb-4" :class="darkMode ? 'text-white' : 'text-slate-800'">Semua Transaksi</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <template x-for="tx in transactions" :key="tx.id">
                        <div class="p-4 rounded-3xl flex items-center justify-between border" :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100'">
                             <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" :class="getCategoryMeta(tx.category).bg" x-text="getCategoryMeta(tx.category).icon"></div>
                                <div>
                                    <p class="font-bold leading-tight" x-text="tx.note"></p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-0.5" x-text="formatCurrency(tx.amount)"></p>
                                </div>
                             </div>
                             <form :action="'/transactions/' + tx.id" method="POST" data-swal-delete>
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="text-rose-400 p-2 hover:text-rose-600 active:scale-90 transition-transform">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                 </button>
                             </form>
                        </div>
                    </template>
                 </div>
            </div>

        </main>

        <nav class="fixed bottom-0 left-0 right-0 border-t px-6 py-4 z-40 transition-colors md:hidden" :class="darkMode ? 'bg-slate-900 border-slate-800' : 'bg-white/90 backdrop-blur-xl border-slate-100'">
            <div class="max-w-md mx-auto flex justify-between items-center">
                <button @click="activeTab = 'home'" class="flex flex-col items-center gap-1 transition-colors" :class="activeTab === 'home' ? 'text-indigo-600' : 'text-slate-400'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :fill="activeTab === 'home' ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-[10px] font-bold">Beranda</span>
                </button>
                <button @click="activeTab = 'reports'" class="flex flex-col items-center gap-1 transition-colors" :class="activeTab === 'reports' ? 'text-indigo-600' : 'text-slate-400'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="text-[10px] font-bold">Laporan</span>
                </button>
                
                <div class="-mt-12 bg-white p-2 rounded-full border shadow-lg" :class="darkMode ? 'bg-slate-800 border-slate-700' : 'border-slate-100'">
                    <button @click="showForm = true; activeTab = 'home'" class="w-14 h-14 bg-slate-900 text-white rounded-full flex items-center justify-center shadow-xl active:scale-90 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                </div>

                <button @click="activeTab = 'history'" class="flex flex-col items-center gap-1 transition-colors" :class="activeTab === 'history' ? 'text-indigo-600' : 'text-slate-400'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-[10px] font-bold">Riwayat</span>
                </button>
                <button @click="activeTab = 'profile'" class="flex flex-col items-center gap-1 transition-colors" :class="activeTab === 'profile' ? 'text-indigo-600' : 'text-slate-400'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-[10px] font-bold">Profil</span>
                </button>
            </div>
        </nav>

        <div x-show="notification.show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10"
             class="fixed bottom-24 left-1/2 -translate-x-1/2 z-50 bg-emerald-500 text-white px-6 py-3 rounded-2xl shadow-xl flex items-center gap-2 text-sm font-bold" x-cloak>
            <span>✅</span> <span x-text="notification.message"></span>
        </div>
    </div>

    <script>
        function financeApp(config) {
            return {
                darkMode: localStorage.getItem('theme') === 'dark' || document.documentElement.classList.contains('dark'),
                activeTab: 'home',
                showForm: false,
                totalBalance: config.initialBalance,
                totalIncome: config.initialIncome,
                totalExpense: config.initialExpense,
                notification: { show: false, message: '' },
                user: { notif: true },
                newTx: { type: 'expense', amount: '', note: '' },
                transactions: config.initialTransactions,
                
                init() {
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },
                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }
                },
                formatCurrency(val) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
                },
                formatShort(val) {
                    if (val >= 1000000) return (val / 1000000).toFixed(1) + 'jt';
                    if (val >= 1000) return (val / 1000).toFixed(0) + 'k';
                    return val;
                },
                getCategoryMeta(cat) {
                    const map = {
                        'Food': { icon: '☕', bg: 'bg-orange-100 text-orange-600' },
                        'Makanan & Minuman': { icon: '☕', bg: 'bg-orange-100 text-orange-600' },
                        'Salary': { icon: '💵', bg: 'bg-emerald-100 text-emerald-600' },
                        'Gaji / Bonus': { icon: '💵', bg: 'bg-emerald-100 text-emerald-600' },
                        'Transport': { icon: '🚲', bg: 'bg-blue-100 text-blue-600' },
                        'Transportasi': { icon: '🚲', bg: 'bg-blue-100 text-blue-600' },
                        'Bills': { icon: '📄', bg: 'bg-rose-100 text-rose-600' },
                        'Tagihan': { icon: '📄', bg: 'bg-rose-100 text-rose-600' },
                        'General': { icon: '📦', bg: 'bg-slate-100 text-slate-600' }
                    };
                    return map[cat] || map['General'];
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function initSwalNotifications() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonText: 'Oke',
                    timer: 3500,
                    timerProgressBar: true,
                });
            @endif

            document.querySelectorAll('form[data-swal-delete]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Hapus transaksi?',
                        text: 'Data ini akan dihapus secara permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        }

        if (document.readyState === 'loading') {
            window.addEventListener('DOMContentLoaded', initSwalNotifications);
        } else {
            initSwalNotifications();
        }
    </script>
</x-app-layout>