<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F4F7FE]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - PustakaSmart')</title>
    <!-- Tailwind CSS & Chart.js CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full flex bg-[#F4F7FE] text-slate-700 antialiased selection:bg-[#00B074] selection:text-white">

    <!-- Admin Left Sidebar -->
    <aside class="hidden lg:flex w-64 bg-white border-r border-slate-200/60 p-6 flex-col justify-between shrink-0 shadow-sm min-h-screen sticky top-0 h-screen overflow-y-auto">
        <div class="space-y-8">
            <!-- Brand Logo -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-2">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#00B074] to-[#00E599] flex items-center justify-center shadow-lg shadow-[#00B074]/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="font-extrabold text-2xl tracking-tight text-slate-800">
                    pustaka<span class="text-[#00B074]">.</span>
                </span>
            </a>

            <!-- Navigation Links (Admin Only - No Catalog Publik link) -->
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all relative {{ request()->routeIs('admin.dashboard') ? 'bg-[#00B074]/10 text-[#00B074]' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="absolute left-0 top-2 bottom-2 w-1.5 bg-[#00B074] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.transactions.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all relative {{ request()->routeIs('admin.transactions.*') ? 'bg-[#00B074]/10 text-[#00B074]' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    @if(request()->routeIs('admin.transactions.*'))
                        <span class="absolute left-0 top-2 bottom-2 w-1.5 bg-[#00B074] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>Sirkulasi Pinjam</span>
                </a>

                <a href="{{ route('admin.books.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all relative {{ request()->routeIs('admin.books.*') ? 'bg-[#00B074]/10 text-[#00B074]' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    @if(request()->routeIs('admin.books.*'))
                        <span class="absolute left-0 top-2 bottom-2 w-1.5 bg-[#00B074] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>Master Buku</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all relative {{ request()->routeIs('admin.categories.*') ? 'bg-[#00B074]/10 text-[#00B074]' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    @if(request()->routeIs('admin.categories.*'))
                        <span class="absolute left-0 top-2 bottom-2 w-1.5 bg-[#00B074] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span>Kategori</span>
                </a>

                <a href="{{ route('admin.members.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all relative {{ request()->routeIs('admin.members.*') ? 'bg-[#00B074]/10 text-[#00B074]' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    @if(request()->routeIs('admin.members.*'))
                        <span class="absolute left-0 top-2 bottom-2 w-1.5 bg-[#00B074] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Data Anggota</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all relative {{ request()->routeIs('profile.*') ? 'bg-[#00B074]/10 text-[#00B074]' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    @if(request()->routeIs('profile.*'))
                        <span class="absolute left-0 top-2 bottom-2 w-1.5 bg-[#00B074] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Profil Saya</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="pt-6 border-t border-slate-100 space-y-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 rounded-2xl text-xs font-bold text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Header Navigation (NO NOTIFICATION BELL) -->
        <header class="bg-white border-b border-slate-200/60 sticky top-0 z-40 px-6 py-4 flex items-center justify-between shadow-xs">
            <div class="flex items-center space-x-4">
                <button class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <h2 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                    Halo, <span class="text-slate-900">{{ auth()->user()->name }}!</span> 👋
                </h2>
            </div>

            <!-- Header Right Section (Profile click link) -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 pl-2 group">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-[#00B074] to-emerald-400 flex items-center justify-center text-xs font-bold text-white shadow-md shadow-[#00B074]/20 group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-bold text-slate-800 leading-none group-hover:text-[#00B074] transition-colors">{{ auth()->user()->name }}</p>
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-[#00B074]">
                            Administrator
                        </span>
                    </div>
                </a>
            </div>
        </header>

        <!-- Flash Alerts -->
        <div class="px-6 pt-4 max-w-7xl w-full mx-auto">
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
        <main class="flex-grow p-6 md:p-8 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>
    </div>

</body>
</html>
