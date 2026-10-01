@extends('layouts.app')

@section('title', 'Dashboard Saya - PustakaSmart')

@section('content')
<div class="space-y-8">
    <!-- Header Summary -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white border border-slate-200/60 p-6 rounded-3xl shadow-sm">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Dashboard Anggota</h1>
            <p class="text-slate-500 text-xs mt-1">Pantau status peminjaman buku, batas waktu pengembalian, dan tagihan denda Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">
                Akun {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
            <a href="{{ route('catalog.index') }}" class="bg-[#00B074] hover:bg-[#009663] text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-all shadow-md shadow-[#00B074]/30">
                + Pinjam Buku Baru
            </a>
        </div>
    </div>

    <!-- 3 Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Active Loans -->
        <div class="bg-gradient-to-br from-[#00B074] to-[#009663] text-white rounded-3xl p-6 shadow-lg shadow-[#00B074]/20 flex flex-col justify-between h-40">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-emerald-100 uppercase tracking-wider">Buku Dipinjam Saat Ini</span>
                    <h3 class="text-4xl font-extrabold mt-1 tracking-tight">{{ $activeBorrowings->count() }} <span class="text-base font-medium text-emerald-200">/ 3 Max</span></h3>
                </div>
                <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <p class="text-xs text-emerald-100">Batas aturan: Maksimal 3 buku sekaligus</p>
        </div>

        <!-- Running Fine Card -->
        <div class="bg-gradient-to-br from-[#FF6422] to-[#E04B0B] text-white rounded-3xl p-6 shadow-lg shadow-[#FF6422]/20 flex flex-col justify-between h-40">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-orange-100 uppercase tracking-wider">Perkiraan Denda Berjalan</span>
                    <h3 class="text-3xl font-extrabold mt-1 tracking-tight">Rp {{ number_format($runningFineTotal, 0, ',', '.') }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-orange-100">Rp1.000 / hari jika melewati due date</p>
        </div>

        <!-- Unpaid Fines Total -->
        <div class="bg-gradient-to-br from-[#9B51E0] to-[#8031CA] text-white rounded-3xl p-6 shadow-lg shadow-[#9B51E0]/20 flex flex-col justify-between h-40">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-purple-100 uppercase tracking-wider">Total Denda Unpaid</span>
                    <h3 class="text-3xl font-extrabold mt-1 tracking-tight">Rp {{ number_format($unpaidFines, 0, ',', '.') }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-purple-100">Bayar langsung di kasir perpustakaan</p>
        </div>
    </div>

    <!-- Active Borrowings Table -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-4">
        <h2 class="text-lg font-bold text-slate-800">Daftar Peminjaman Aktif</h2>

        @if($activeBorrowings->isEmpty())
            <div class="text-center py-8 border border-dashed border-slate-200 rounded-2xl">
                <p class="text-slate-400 text-xs">Anda sedang tidak meminjam buku apapun.</p>
                <a href="{{ route('catalog.index') }}" class="text-[#00B074] text-xs font-bold mt-2 inline-block hover:underline">Jelajahi Katalog Buku &rarr;</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Kode Transaksi</th>
                            <th class="py-3 px-4">Judul Buku</th>
                            <th class="py-3 px-4">Tgl Pinjam</th>
                            <th class="py-3 px-4">Jatuh Tempo</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Denda Berjalan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($activeBorrowings as $b)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5 px-4 font-mono font-bold text-[#00B074]">{{ $b->borrow_code }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-800">{{ $b->book->title }}</td>
                                <td class="py-3.5 px-4 text-slate-500">{{ \Carbon\Carbon::parse($b->borrow_date)->format('d M Y') }}</td>
                                <td class="py-3.5 px-4 font-semibold {{ $b->isOverdue() ? 'text-rose-600 font-bold' : 'text-slate-700' }}">
                                    {{ \Carbon\Carbon::parse($b->due_date)->format('d M Y') }}
                                    @if($b->isOverdue())
                                        <span class="text-[10px] bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full ml-1 font-bold">LEWAT DUE DATE</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($b->status === 'pending')
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold">Menunggu Verifikasi</span>
                                    @elseif($b->status === 'borrowed')
                                        <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-bold">Sedang Dipinjam</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-bold">
                                    @if($b->running_fine > 0)
                                        <span class="text-orange-600 font-mono">Rp {{ number_format($b->running_fine, 0, ',', '.') }} ({{ $b->days_late }} Hari)</span>
                                    @else
                                        <span class="text-emerald-600 font-normal">Rp 0 (Tepat Waktu)</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Borrowing History Table -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-4">
        <h2 class="text-lg font-bold text-slate-800">Riwayat Peminjaman Lalu</h2>

        @if($borrowingHistory->isEmpty())
            <p class="text-slate-400 text-xs py-4">Belum ada riwayat peminjaman terdahulu.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Kode Transaksi</th>
                            <th class="py-3 px-4">Judul Buku</th>
                            <th class="py-3 px-4">Tgl Pengembalian</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Catatan Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($borrowingHistory as $h)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4 font-mono text-slate-500">{{ $h->borrow_code }}</td>
                                <td class="py-3 px-4 font-bold text-slate-800">{{ $h->book->title }}</td>
                                <td class="py-3 px-4 text-slate-500">{{ $h->return_date ? \Carbon\Carbon::parse($h->return_date)->format('d M Y') : '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $h->status === 'returned' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $h->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-mono">
                                    @if($h->fine)
                                        <span class="font-bold text-rose-600">Rp {{ number_format($h->fine->amount, 0, ',', '.') }}</span>
                                        <span class="text-[10px] ml-1 font-bold {{ $h->fine->status === 'paid' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            ({{ strtoupper($h->fine->status) }})
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
