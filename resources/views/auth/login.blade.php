@extends('layouts.app')

@section('title', 'Masuk - PustakaSmart')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-8">
    <div class="w-full max-w-md bg-white border border-slate-200/80 rounded-3xl p-8 shadow-xl shadow-slate-200/50">
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-[#00B074]/10 text-[#00B074] flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Selamat Datang Kembali</h2>
            <p class="text-slate-500 text-xs mt-1">Masuk ke akun anggota atau administrator Anda</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-slate-800 text-xs font-medium focus:outline-none focus:border-[#00B074] focus:ring-1 focus:ring-[#00B074] transition-all placeholder-slate-400"
                    placeholder="nama@email.com">
                @error('email')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi</label>
                <input type="password" name="password" id="password" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-slate-800 text-xs font-medium focus:outline-none focus:border-[#00B074] focus:ring-1 focus:ring-[#00B074] transition-all placeholder-slate-400"
                    placeholder="••••••••">
                @error('password')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-[#00B074] hover:bg-[#009663] text-white font-bold py-3 px-4 rounded-2xl transition-all shadow-lg shadow-[#00B074]/30 text-xs">
                Masuk Ke Sistem
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="text-[#00B074] font-bold hover:underline">Daftar Anggota Baru</a>
            </p>

            <div class="mt-4 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 text-left">
                <p class="font-bold text-[#00B074] mb-1">Kredensial Demo:</p>
                <p>🔑 Admin: <code class="font-bold text-slate-800">admin@perpustakaan.com</code> | Pass: <code class="font-bold text-slate-800">password</code></p>
                <p>🔑 User: <code class="font-bold text-slate-800">user@perpustakaan.com</code> | Pass: <code class="font-bold text-slate-800">password</code></p>
            </div>
        </div>
    </div>
</div>
@endsection
