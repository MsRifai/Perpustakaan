@extends('layouts.admin')

@section('title', 'Sirkulasi Peminjaman - Panel Admin')

@section('content')
<div class="space-y-8">
    <!-- Header Admin -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white border border-slate-200/60 p-6 rounded-3xl shadow-sm">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Sirkulasi Peminjaman</h1>
            <p class="text-slate-500 text-xs mt-1">Verifikasi pengajuan pinjaman, catat pengembalian buku, hitung denda, dan kelola status rusak/hilang.</p>
        </div>
        <div>
            <a href="{{ route('admin.transactions.export') }}" class="bg-[#00B074] hover:bg-[#009663] text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-all shadow-md shadow-[#00B074]/30 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Ekspor Laporan (CSV)
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200/60 p-4 rounded-2xl shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Pengajuan Pending</span>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['pending'] }} Transaksi</p>
        </div>
        <div class="bg-white border border-slate-200/60 p-4 rounded-2xl shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">Sedang Dipinjam</span>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['borrowed'] }} Transaksi</p>
        </div>
        <div class="bg-white border border-slate-200/60 p-4 rounded-2xl shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Total Selesai</span>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['returned'] }} Transaksi</p>
        </div>
        <div class="bg-white border border-slate-200/60 p-4 rounded-2xl shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Belum Bayar Denda</span>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">Rp {{ number_format($stats['unpaid_fines'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-white border border-slate-200/60 p-4 rounded-2xl shadow-xs">
        <div class="flex items-center space-x-2 overflow-x-auto w-full md:w-auto">
            <a href="{{ route('admin.transactions.index') }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap border {{ !request('status') ? 'bg-[#00B074] text-white border-[#00B074]' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                Semua Status
            </a>
            <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap border {{ request('status') === 'pending' ? 'bg-amber-500 text-white border-amber-500' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                Pending (Verifikasi)
            </a>
            <a href="{{ route('admin.transactions.index', ['status' => 'borrowed']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap border {{ request('status') === 'borrowed' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                Sedang Dipinjam
            </a>
            <a href="{{ route('admin.transactions.index', ['status' => 'returned']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap border {{ request('status') === 'returned' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                Dikembalikan
            </a>
        </div>

        <form action="{{ route('admin.transactions.index') }}" method="GET" class="w-full md:w-80">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari Kode TRX, Anggota, atau Buku..."
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-800 focus:outline-none focus:border-[#00B074]">
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm">
        @if($borrowings->isEmpty())
            <div class="text-center py-12">
                <p class="text-slate-400 text-xs">Tidak ada transaksi sirkulasi ditemukan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Kode Transaksi</th>
                            <th class="py-3.5 px-4">Peminjam / Anggota</th>
                            <th class="py-3.5 px-4">Buku Yang Dipinjam</th>
                            <th class="py-3.5 px-4">Pinjam & Due Date</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Denda / Overdue</th>
                            <th class="py-3.5 px-4 text-center">Aksi Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($borrowings as $b)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5 px-4 font-mono font-bold text-[#00B074]">{{ $b->borrow_code }}</td>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-slate-800">{{ $b->user->name }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $b->user->email }}</p>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-slate-800 line-clamp-1">{{ $b->book->title }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">ISBN: {{ $b->book->isbn }}</p>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="text-slate-600">Pinjam: {{ \Carbon\Carbon::parse($b->borrow_date)->format('d/m/Y') }}</p>
                                    <p class="font-bold {{ $b->isOverdue() ? 'text-rose-600' : 'text-slate-500' }}">
                                        Tempo: {{ \Carbon\Carbon::parse($b->due_date)->format('d/m/Y') }}
                                    </p>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($b->status === 'pending')
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold">Pending</span>
                                    @elseif($b->status === 'borrowed')
                                        <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-bold">Dipinjam</span>
                                    @elseif($b->status === 'returned')
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold">Kembali</span>
                                    @elseif($b->status === 'rejected')
                                        <span class="px-2.5 py-1 bg-rose-100 text-rose-700 rounded-full text-[10px] font-bold">Ditolak</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-full text-[10px] font-bold">{{ strtoupper($b->status) }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($b->status === 'borrowed' && $b->current_days_late > 0)
                                        <span class="text-orange-600 font-bold font-mono">Rp {{ number_format($b->current_fine_amount, 0, ',', '.') }}</span>
                                        <p class="text-[10px] text-rose-600 font-bold">Terlambat {{ $b->current_days_late }} Hari</p>
                                    @elseif($b->fine)
                                        <span class="font-bold font-mono text-slate-800">Rp {{ number_format($b->fine->amount, 0, ',', '.') }}</span>
                                        @if($b->fine->status === 'unpaid')
                                            <form action="{{ route('admin.fines.pay', $b->fine->id) }}" method="POST" class="mt-1">
                                                @csrf
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-xs">
                                                    Bayar Lunas
                                                </button>
                                            </form>
                                        @else
                                            <span class="block text-[10px] font-bold text-emerald-600">LUNAS</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($b->status === 'pending')
                                        <div class="flex items-center justify-center space-x-2">
                                            <form action="{{ route('admin.transactions.approve', $b->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg shadow-xs">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.transactions.reject', $b->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Tolak pengajuan ini?')" class="bg-rose-600 hover:bg-rose-500 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg shadow-xs">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($b->status === 'borrowed')
                                        <details class="group relative inline-block text-left">
                                            <summary class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg shadow-xs cursor-pointer list-none">
                                                Proses Pengembalian
                                            </summary>
                                            <div class="absolute right-0 mt-2 w-72 bg-white border border-slate-200 p-4 rounded-2xl shadow-xl z-50 text-left space-y-3">
                                                <h4 class="text-xs font-bold text-slate-800 border-b border-slate-100 pb-2">Pengembalian Buku</h4>
                                                <form action="{{ route('admin.transactions.return', $b->id) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kondisi Buku</label>
                                                        <select name="condition_status" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs text-slate-800">
                                                            <option value="returned">Kembali Normal</option>
                                                            <option value="damaged">Buku Rusak (Denda Khusus)</option>
                                                            <option value="lost">Buku Hilang (Ganti Rugi)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Denda Tambahan / Penalty (Rp)</label>
                                                        <input type="number" name="penalty_amount" value="0" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs text-slate-800">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Catatan</label>
                                                        <input type="text" name="note" placeholder="Catatan..." class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs text-slate-800">
                                                    </div>
                                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-2 rounded-lg transition-colors">
                                                        Simpan Pengembalian
                                                    </button>
                                                </form>
                                            </div>
                                        </details>
                                    @else
                                        <span class="text-slate-400 font-medium text-[11px]">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $borrowings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
