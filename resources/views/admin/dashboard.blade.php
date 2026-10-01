@extends('layouts.admin')

@section('title', 'Admin Dashboard - PustakaSmart')

@section('content')
<div class="space-y-8">

    <!-- 4 Colorful Top Stat Cards (Reference Match: Green, Blue, Purple, Orange) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Green Solid Card (Total Members) -->
        <div class="bg-gradient-to-br from-[#00B074] to-[#009663] text-white rounded-3xl p-6 shadow-lg shadow-[#00B074]/20 flex flex-col justify-between h-44 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-emerald-100 uppercase tracking-wider">Total Anggota</span>
                    <h3 class="text-4xl font-extrabold mt-1 tracking-tight">{{ number_format($totalMembers) }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="border-t border-white/20 pt-3 flex justify-between items-center text-xs text-emerald-100 font-medium">
                <span>Terdaftar di sistem</span>
                <span class="font-bold text-white">Aktif</span>
            </div>
        </div>

        <!-- Card 2: Blue Solid Card (Total Books) -->
        <div class="bg-gradient-to-br from-[#2F80ED] to-[#1B66CB] text-white rounded-3xl p-6 shadow-lg shadow-[#2F80ED]/20 flex flex-col justify-between h-44 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-blue-100 uppercase tracking-wider">Total Eksemplar Buku</span>
                    <h3 class="text-4xl font-extrabold mt-1 tracking-tight">{{ number_format($totalBooks) }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <div class="border-t border-white/20 pt-3 flex justify-between items-center text-xs text-blue-100 font-medium">
                <span>Stok Perpustakaan</span>
                <a href="{{ route('admin.books.index') }}" class="font-bold text-white underline">Kelola Buku</a>
            </div>
        </div>

        <!-- Card 3: Purple Solid Card (Circulation Status) -->
        <div class="bg-gradient-to-br from-[#9B51E0] to-[#8031CA] text-white rounded-3xl p-6 shadow-lg shadow-[#9B51E0]/20 flex flex-col justify-between h-44 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-purple-100 uppercase tracking-wider">Peminjaman Aktif</span>
                    <h3 class="text-4xl font-extrabold mt-1 tracking-tight">{{ $activeBorrowingsCount }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="border-t border-white/20 pt-2 text-[11px] text-purple-100 space-y-1">
                <div class="flex justify-between">
                    <span>Sedang Dipinjam:</span>
                    <span class="font-bold text-white">{{ $activeBorrowingsCount }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pengajuan Pending:</span>
                    <span class="font-bold text-amber-200">{{ $pendingBorrowingsCount }}</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Orange Solid Card (Total Unpaid Fines) -->
        <div class="bg-gradient-to-br from-[#FF6422] to-[#E04B0B] text-white rounded-3xl p-6 shadow-lg shadow-[#FF6422]/20 flex flex-col justify-between h-44 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-orange-100 uppercase tracking-wider">Tunggakan Denda</span>
                    <h3 class="text-2xl font-extrabold mt-1 tracking-tight">Rp {{ number_format($totalFinesUnpaid, 0, ',', '.') }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="border-t border-white/20 pt-3 flex justify-between items-center text-xs text-orange-100 font-medium">
                <span>Denda Terbayar:</span>
                <span class="font-bold text-white">Rp {{ number_format($totalFinesPaid, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Middle Section: Recent Circulation Table + Line Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Table Card: Active Borrowings & Requests -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Sirkulasi Transaksi Terbaru</h3>
                    <a href="{{ route('admin.transactions.index') }}" class="text-xs font-bold text-[#00B074] hover:underline">Lihat Semua &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentBorrowings as $b)
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100/80 rounded-2xl transition-colors border border-slate-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-[#00B074]/10 text-[#00B074] flex items-center justify-center font-mono font-bold text-xs">
                                    {{ substr($b->borrow_code, -4) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $b->book->title }}</h4>
                                    <p class="text-[11px] text-slate-500">Peminjam: <span class="font-semibold text-slate-700">{{ $b->user->name }}</span></p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($b->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">Pending</span>
                                @elseif($b->status === 'borrowed')
                                    <span class="px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold">Dipinjam</span>
                                @elseif($b->status === 'returned')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Selesai</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold">{{ strtoupper($b->status) }}</span>
                                @endif
                                <p class="text-[10px] text-slate-400 mt-1">Due: {{ \Carbon\Carbon::parse($b->due_date)->format('d M') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-6">Belum ada aktivitas transaksi baru.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Card: Line Chart Analytics -->
        <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-800">Aktivitas Peminjaman</h3>
                <span class="text-xs font-bold text-[#00B074] bg-[#00B074]/10 px-3 py-1 rounded-full">Bulanan</span>
            </div>
            <div class="h-64 relative">
                <canvas id="borrowingChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Recent Members List + Doughnut Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Members Card -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <h3 class="text-lg font-bold text-slate-800">Anggota Baru Terdaftar</h3>
                <a href="{{ route('admin.members.index') }}" class="text-xs font-bold text-[#00B074] hover:underline">Kelola Anggota &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($recentMembers as $m)
                    <div class="flex items-center space-x-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-[#00B074] text-white flex items-center justify-center font-bold text-xs shadow-md shadow-[#00B074]/20">
                            {{ strtoupper(substr($m->name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">{{ $m->name }}</h4>
                            <p class="text-[11px] text-slate-500 font-mono">ID: ANG-{{ str_pad($m->id, 4, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Status: {{ $m->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Category Distribution Doughnut Chart -->
        <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Kategori Buku</h3>
            <div class="h-56 relative flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart (Borrowings Trend)
        const ctxLine = document.getElementById('borrowingChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['1 Dec', '8 Dec', '16 Dec', '24 Dec', '31 Dec'],
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: [50, 110, 80, 165, 125],
                    borderColor: '#00B074',
                    backgroundColor: 'rgba(0, 176, 116, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#00B074',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { borderDash: [5, 5] }, min: 0 }
                }
            }
        });

        // Doughnut Chart (Categories)
        const ctxPie = document.getElementById('categoryChart').getContext('2d');
        const categoryLabels = {!! json_encode($categories->pluck('name')) !!};
        const categoryData = {!! json_encode($categories->pluck('books_count')) !!};

        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: ['#FF6422', '#00B074', '#2F80ED', '#9B51E0', '#F2C94C'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection
