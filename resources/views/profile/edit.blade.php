@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.app')

@section('title', 'Profil Saya - PustakaSmart')

@section('content')
<div class="space-y-8">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Profil & Pengaturan Akun</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">Kelola data pribadi dan keamanan akun Anda.</p>
        </div>
        <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500 bg-white px-4 py-2 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Status Akun: <strong class="text-slate-800">{{ $user->is_active ? 'Aktif & Terverifikasi' : 'Nonaktif' }}</strong></span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kiri: Profile Identity & Quick Stats -->
        <div class="space-y-6">
            
            <!-- Profile Overview Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-xs flex flex-col items-center text-center">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-[#00B074] to-emerald-400 flex items-center justify-center text-3xl font-extrabold text-white shadow-xl shadow-[#00B074]/30 ring-4 ring-emerald-50">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <span class="absolute bottom-1 right-1 w-5 h-5 bg-emerald-500 border-2 border-white rounded-full"></span>
                </div>

                <h3 class="text-lg font-bold text-slate-900 mt-4 leading-tight">{{ $user->name }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $user->email }}</p>

                <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase {{ $user->isAdmin() ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-[#00B074]' }}">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    {{ $user->isAdmin() ? 'Administrator Utama' : 'Anggota Perpustakaan' }}
                </div>

                <div class="w-full border-t border-slate-100 mt-6 pt-4 text-xs text-slate-500 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Bergabung Sejak:</span>
                        <span class="font-bold text-slate-700">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">ID Anggota:</span>
                        <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded-md">PS-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>

            @if($user->isUser())
            <!-- Ringkasan Statistik Anggota -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-xs space-y-4">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Ringkasan Aktivitas Pinjam</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Total Pinjam</p>
                        <p class="text-xl font-extrabold text-slate-800 mt-1">{{ $stats['total_borrowed'] }} <span class="text-xs font-normal text-slate-500">kali</span></p>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-100">
                        <p class="text-[10px] font-bold text-[#00B074] uppercase">Pinjam Aktif</p>
                        <p class="text-xl font-extrabold text-[#00B074] mt-1">{{ $stats['active_borrowed'] }} <span class="text-xs font-normal text-emerald-600">buku</span></p>
                    </div>
                </div>
                @if($stats['unpaid_fines'] > 0)
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-between text-rose-700">
                    <div>
                        <p class="text-[10px] font-bold uppercase">Denda Belum Dibayar</p>
                        <p class="text-base font-extrabold">Rp {{ number_format($stats['unpaid_fines'], 0, ',', '.') }}</p>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="text-[11px] font-bold underline hover:text-rose-900">Bayar</a>
                </div>
                @endif
            </div>
            @endif

        </div>

        <!-- Kanan: Form Edit Profil & Form Ubah Password -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Form Update Informasi Diri -->
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200/60 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900">Informasi Pribadi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui nama lengkap, kontak telepon, dan alamat domisili Anda.</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-[#00B074] focus:ring-4 focus:ring-[#00B074]/10 text-sm font-semibold transition-all outline-none">
                        @error('name')
                            <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-[#00B074] focus:ring-4 focus:ring-[#00B074]/10 text-sm font-semibold transition-all outline-none">
                        @error('email')
                            <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor HP / WhatsApp</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-[#00B074] focus:ring-4 focus:ring-[#00B074]/10 text-sm font-semibold transition-all outline-none">
                            @error('phone')
                                <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Peran Akun</label>
                            <input type="text" disabled value="{{ $user->isAdmin() ? 'Administrator' : 'Anggota Regular' }}"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-100 bg-slate-50 text-slate-500 text-sm font-bold cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Lengkap Tempat Tinggal</label>
                        <textarea name="address" id="address" rows="3" placeholder="Masukkan alamat lengkap domisili..."
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-[#00B074] focus:ring-4 focus:ring-[#00B074]/10 text-sm font-medium transition-all outline-none resize-none">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-[#00B074] hover:bg-[#009663] text-white text-xs font-bold transition-all shadow-md shadow-[#00B074]/30 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Simpan Perubahan Profile</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form Update Password -->
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200/60 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900">Keamanan & Kata Sandi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pastikan Anda menggunakan kata sandi yang kuat untuk menjaga keamanan akun.</p>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-[#00B074] focus:ring-4 focus:ring-[#00B074]/10 text-sm font-semibold transition-all outline-none">
                        @error('current_password')
                            <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password" required placeholder="••••••••"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-[#00B074] focus:ring-4 focus:ring-[#00B074]/10 text-sm font-semibold transition-all outline-none">
                            @error('password')
                                <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-[#00B074] focus:ring-4 focus:ring-[#00B074]/10 text-sm font-semibold transition-all outline-none">
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-all shadow-md shadow-slate-900/20 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
