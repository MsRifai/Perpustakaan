<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F4F7FE]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PustakaSmart - Sistem Informasi Perpustakaan')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full flex flex-col bg-[#F4F7FE] text-slate-700 antialiased selection:bg-[#00B074] selection:text-white">

    <!-- Top Horizontal Navigation Bar (User/Public) -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/60 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                <!-- Logo & Nav Links -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#00B074] to-[#00E599] flex items-center justify-center shadow-lg shadow-[#00B074]/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="font-extrabold text-2xl tracking-tight text-slate-800">
                            pustaka<span class="text-[#00B074]">.</span>
                        </span>
                    </a>

                    <!-- Horizontal Links -->
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('catalog.index') }}"
                            class="text-xs font-bold transition-colors hover:text-[#00B074] {{ request()->routeIs('catalog.*') || request()->is('/') ? 'text-[#00B074]' : 'text-slate-600' }}">
                            Katalog Buku
                        </a>

                        @auth
                            @if(auth()->user()->isUser())
                                <a href="{{ route('user.dashboard') }}"
                                    class="text-xs font-bold transition-colors hover:text-[#00B074] {{ request()->routeIs('user.dashboard') ? 'text-[#00B074]' : 'text-slate-600' }}">
                                    Dashboard Saya
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}"
                                class="text-xs font-bold transition-colors hover:text-[#00B074] {{ request()->routeIs('profile.*') ? 'text-[#00B074]' : 'text-slate-600' }}">
                                Profil Saya
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right Profile / Login Actions -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 bg-slate-50 hover:bg-slate-100 border border-slate-200/80 px-3.5 py-2 rounded-2xl transition-colors group">
                            <div class="w-8 h-8 rounded-full bg-[#00B074] flex items-center justify-center text-xs font-bold text-white shadow-xs group-hover:scale-105 transition-transform">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-xs font-bold text-slate-800 leading-tight group-hover:text-[#00B074] transition-colors">{{ auth()->user()->name }}</p>
                                <span class="text-[10px] font-semibold text-[#00B074] uppercase">{{ auth()->user()->role }}</span>
                            </div>
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-slate-500 hover:text-rose-600 bg-white hover:bg-rose-50 px-4 py-2.5 rounded-xl border border-slate-200 transition-colors">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-4 py-2.5">Masuk</a>
                        <a href="{{ route('register') }}" class="text-xs font-bold text-white bg-[#00B074] hover:bg-[#009663] px-5 py-2.5 rounded-xl transition-all shadow-md shadow-[#00B074]/30">
                            Daftar Anggota
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 w-full">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xs font-bold">{{ session('error') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto bg-white border-t border-slate-200/60 text-slate-500 text-xs py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} PustakaSmart Perpustakaan. All rights reserved.</p>
            <div class="flex space-x-4 text-slate-400">
                <span>Denda: Rp1.000 / hari</span>
                <span>|</span>
                <span>Batas Pinjam: 3 Buku (7 Hari)</span>
            </div>
        </div>
    </footer>

</body>
</html>
