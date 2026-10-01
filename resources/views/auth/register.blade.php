@extends('layouts.app')

@section('title', 'Pendaftaran Anggota - PustakaSmart')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-8">
    <div class="w-full max-w-lg bg-white border border-slate-200/80 rounded-3xl p-8 shadow-xl shadow-slate-200/50">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pendaftaran Anggota Baru</h2>
            <p class="text-slate-500 text-xs mt-1">Lengkapi formulir untuk mulai meminjam buku perpustakaan</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-slate-800 text-xs font-medium focus:outline-none focus:border-[#00B074]"
                    placeholder="Nama sesuai identitas">
                @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-slate-800 text-xs font-medium focus:outline-none focus:border-[#00B074]"
                    placeholder="nama@email.com">
                @error('email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                    <input type="password" name="password" id="password" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-slate-800 text-xs font-medium focus:outline-none focus:border-[#00B074]"
                        placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-slate-800 text-xs font-medium focus:outline-none focus:border-[#00B074]"
                        placeholder="Ulangi kata sandi">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor Telepon / WA</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-slate-800 text-xs font-medium focus:outline-none focus:border-[#00B074]"
                    placeholder="081234567890">
            </div>

            <button type="submit" class="w-full bg-[#00B074] hover:bg-[#009663] text-white font-bold py-3 px-4 rounded-2xl transition-all shadow-lg shadow-[#00B074]/30 text-xs mt-4">
                Daftar Sebagai Anggota
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="text-[#00B074] font-bold hover:underline">Masuk ke Akun Anda</a>
            </p>
        </div>
    </div>
</div>
@endsection
