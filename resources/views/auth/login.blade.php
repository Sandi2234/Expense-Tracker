<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Masuk ke Financely</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        [x-cloak] { display: none !important; }
        .glass-effect { backdrop-filter: blur(10px); background-color: rgba(255, 255, 255, 0.8); }
        .dark .glass-effect { background-color: rgba(15, 23, 42, 0.8); }
        
        /* Custom input focus ring */
        .input-focus { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .input-focus:focus { transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.2); }
    </style>
</head>

<body 
    x-data="authApp()" 
    class="bg-slate-50 min-h-screen flex flex-col justify-center transition-colors duration-500"
    :class="darkMode ? 'bg-slate-950 text-white' : 'bg-slate-50 text-slate-900'"
>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -right-[10%] w-[50%] h-[40%] bg-indigo-500/10 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-[10%] -left-[10%] w-[50%] h-[40%] bg-emerald-500/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-md mx-auto px-6 py-8">
        
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-[2rem] text-white shadow-2xl shadow-indigo-500/40 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-4.02-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight">Selamat Datang</h1>
            <p class="text-slate-500 font-medium mt-2">Masuk untuk mengelola keuanganmu.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 text-center">
                {{ session('status') }}
            </div>
        @endif

        <div class="p-8 rounded-[2.5rem] shadow-xl border glass-effect transition-all duration-300"
             :class="darkMode ? 'border-slate-800' : 'border-white'">
            
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 ml-1">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus autocomplete="username"
                           class="w-full px-5 py-4 rounded-2xl border-0 bg-slate-100 dark:bg-slate-900/50 outline-none font-bold input-focus"
                           :class="darkMode ? 'text-white' : 'text-slate-800'">
                    @error('email')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 ml-1">Kata Sandi</label>
                    <input id="password" :type="showPassword ? 'text' : 'password'" name="password" placeholder="••••••••" required autocomplete="current-password"
                           class="w-full px-5 py-4 rounded-2xl border-0 bg-slate-100 dark:bg-slate-900/50 outline-none font-bold input-focus"
                           :class="darkMode ? 'text-white' : 'text-slate-800'">
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-[42px] text-slate-400">
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.882 9.882L5.146 5.147m13.712 13.712l-4.242-4.242m-5.46-5.46L3.146 3.147" /></svg>
                    </button>
                    @error('password')
                        <p class="text-xs text-rose-500 font-semibold mt-1.5 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between px-1">
                    <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" class="peer sr-only">
                            <div class="w-5 h-5 border-2 border-slate-200 dark:border-slate-700 rounded-lg peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all"></div>
                            <svg class="absolute w-3.5 h-3.5 text-white left-[3px] opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-500 group-hover:text-slate-700 transition-colors uppercase tracking-wider">Ingat Saya</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">
                            Lupa Sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" 
                        class="w-full bg-slate-900 dark:bg-indigo-600 text-white font-extrabold py-5 rounded-[1.5rem] shadow-xl shadow-indigo-500/20 active:scale-95 transition-all flex items-center justify-center gap-3 group mt-4">
                    <span>Masuk Sekarang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </button>
            </form>
        </div>

        <p class="mt-10 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed px-10">
            © 2026 Financely. Portfolio.
        </p>

        <button @click="darkMode = !darkMode" 
                class="fixed bottom-6 right-6 w-12 h-12 rounded-full shadow-lg flex items-center justify-center transition-all bg-white dark:bg-slate-800"
                :class="darkMode ? 'text-yellow-400 border-slate-700' : 'text-slate-600 border-white'">
            <template x-if="!darkMode">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
            </template>
            <template x-if="darkMode">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </template>
        </button>
    </div>

    <script>
        function authApp() {
            return {
                darkMode: localStorage.getItem('theme') === 'dark' || document.documentElement.classList.contains('dark'),
                showPassword: false,
                init() {
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    this.$watch('darkMode', val => {
                        if (val) {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('theme', 'dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('theme', 'light');
                        }
                    });
                }
            }
        }
    </script>
</body>
</html>