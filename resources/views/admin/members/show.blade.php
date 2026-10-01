@extends('layouts.admin')

@section('title', 'Detail Anggota - PustakaSmart')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail Anggota Perpustakaan</h1>
            <p class="text-slate-500 text-xs mt-1">Informasi profil dan riwayat peminjaman {{ $user->name }}</p>
        </div>
        <a href="{{ route('admin.members.index') }}" class="text-slate-500 hover:text-slate-800 text-xs font-bold">
            &larr; Kembali ke Daftar Anggota
        </a>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-[#00B074] text-white font-extrabold text-xl flex items-center justify-center shadow-lg shadow-[#00B074]/30">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">{{ $user->name }}</h2>
                <p class="text-xs text-slate-500">{{ $user->email }} | HP: {{ $user->phone ?? '-' }}</p>
                <p class="text-xs text-slate-400 mt-1">Alamat: {{ $user->address ?? 'Belum diisi' }}</p>
            </div>
        </div>
        <div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                Status Akun: {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
    </div>

    <!-- Borrowing History Table -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-4">
        <h3 class="text-base font-bold text-slate-800">Riwayat Transaksi Peminjaman</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Kode TRX</th>
                        <th class="py-3 px-4">Judul Buku</th>
                        <th class="py-3 px-4">Tgl Pinjam</th>
                        <th class="py-3 px-4">Due Date</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($user->borrowings as $b)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-mono font-bold text-[#00B074]">{{ $b->borrow_code }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $b->book->title }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ \Carbon\Carbon::parse($b->borrow_date)->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ \Carbon\Carbon::parse($b->due_date)->format('d M Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $b->status === 'returned' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700' }}">
                                    {{ $b->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400 text-xs">Belum ada riwayat transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
