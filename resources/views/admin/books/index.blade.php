@extends('layouts.admin')

@section('title', 'Master Buku - Panel Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border border-slate-200/60 p-6 rounded-3xl shadow-sm">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Master Katalog Buku</h1>
            <p class="text-slate-500 text-xs mt-1">Kelola data buku, ISBN, penulis, kategori, dan jumlah stok fisik perpustakaan.</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="bg-[#00B074] hover:bg-[#009663] text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-all shadow-md shadow-[#00B074]/30">
            + Tambah Buku Baru
        </a>
    </div>

    <!-- Table Master Buku -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Cover & Judul</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Penulis / Penerbit</th>
                        <th class="py-3.5 px-4">ISBN</th>
                        <th class="py-3.5 px-4">Stok (Tersedia / Total)</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($books as $book)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3.5 px-4 flex items-center space-x-3">
                                <div class="w-10 h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0 border border-slate-200 flex items-center justify-center">
                                    @if($book->cover_url)
                                        <img src="{{ $book->cover_url }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[9px] text-slate-400">No Cover</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-sm leading-tight">{{ $book->title }}</p>
                                    <p class="text-[11px] text-slate-400">Tahun: {{ $book->publication_year }}</p>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-[#00B074]/10 text-[#00B074] text-[10px] font-bold rounded-lg border border-[#00B074]/20">
                                    {{ $book->category->name }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-semibold text-slate-700">{{ $book->author }}</p>
                                <p class="text-[11px] text-slate-400">{{ $book->publisher }}</p>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-500">{{ $book->isbn }}</td>
                            <td class="py-3.5 px-4 font-bold">
                                <span class="{{ $book->available_stock > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $book->available_stock }}
                                </span> / {{ $book->total_stock }} Eks
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.books.edit', $book->id) }}" class="bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-[11px] font-bold px-3 py-1.5 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini dari katalog?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-[11px] font-bold px-3 py-1.5 rounded-lg transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $books->links() }}
        </div>
    </div>
</div>
@endsection
