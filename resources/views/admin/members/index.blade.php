@extends('layouts.admin')

@section('title', 'Kelola Anggota - Panel Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border border-slate-200/60 p-6 rounded-3xl shadow-sm">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Anggota Perpustakaan</h1>
            <p class="text-slate-500 text-xs mt-1">Kelola status keaktifan akun anggota, pantau jumlah peminjaman aktif, dan riwayat pinjam.</p>
        </div>
    </div>

    <!-- Member List Table -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-4">
        <div class="flex justify-end">
            <form action="{{ route('admin.members.index') }}" method="GET" class="w-full sm:w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau HP..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-800 focus:outline-none focus:border-[#00B074]">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Nama Anggota</th>
                        <th class="py-3.5 px-4">Kontak & Alamat</th>
                        <th class="py-3.5 px-4">Pinjaman Aktif</th>
                        <th class="py-3.5 px-4">Status Akun</th>
                        <th class="py-3.5 px-4 text-center">Aksi Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($members as $m)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-800 text-sm">{{ $m->name }}</p>
                                <p class="text-[11px] text-slate-400">{{ $m->email }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="text-slate-700 font-semibold">{{ $m->phone ?? 'Tidak ada HP' }}</p>
                                <p class="text-[11px] text-slate-400 line-clamp-1">{{ $m->address ?? 'Alamat belum diisi' }}</p>
                            </td>
                            <td class="py-3.5 px-4 font-bold">
                                <span class="{{ $m->active_loans_count >= 3 ? 'text-orange-600 font-extrabold' : 'text-slate-700' }}">
                                    {{ $m->active_loans_count }} / 3 Buku
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($m->is_active)
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold">
                                        AKTIF
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-700 rounded-full text-[10px] font-bold">
                                        NONAKTIF
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('admin.members.toggle', $m->id) }}" method="POST">
                                    @csrf
                                    @if($m->is_active)
                                        <button type="submit" onclick="return confirm('Nonaktifkan akun {{ addslashes($m->name) }}?')"
                                            class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-[11px] px-3 py-1.5 rounded-lg transition-colors">
                                            Nonaktifkan
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold text-[11px] px-3 py-1.5 rounded-lg transition-colors">
                                            Aktifkan Akun
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection
