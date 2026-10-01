@extends('layouts.app')

@section('title', $book->title . ' - PustakaSmart')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 inline-block mb-2">
        &larr; Kembali ke Katalog Buku
    </a>

    <div class="bg-white border border-slate-200/60 rounded-3xl p-8 shadow-sm flex flex-col md:flex-row gap-8">
        <!-- Cover Image -->
        <div class="w-full md:w-64 h-80 bg-slate-100 rounded-2xl overflow-hidden shrink-0 border border-slate-200 flex items-center justify-center">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
            @else
                <div class="text-center p-4">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="text-xs text-slate-400 font-medium">Tanpa Cover</span>
                </div>
            @endif
        </div>

        <!-- Book Details -->
        <div class="flex-grow space-y-4">
            <div>
                <span class="px-3 py-1 bg-[#00B074]/10 text-[#00B074] text-[10px] font-bold rounded-lg uppercase tracking-wider">
                    {{ $book->category->name }}
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight mt-2">{{ $book->title }}</h1>
                <p class="text-xs text-slate-500 mt-1">Penulis: <span class="text-slate-800 font-bold">{{ $book->author }}</span> | Penerbit: <span class="text-slate-800 font-bold">{{ $book->publisher }}</span> ({{ $book->publication_year }})</p>
                <p class="text-xs text-slate-400 font-mono mt-0.5">ISBN: {{ $book->isbn }}</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Status Ketersediaan Stok</span>
                @if($book->available_stock > 0)
                    <p class="text-sm font-bold text-emerald-600 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Stok Tersedia ({{ $book->available_stock }} / {{ $book->total_stock }} Eks)
                    </p>
                @else
                    <p class="text-sm font-bold text-rose-500 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        Stok Fisik Habis Dipinjam
                    </p>
                @endif
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Ringkasan / Sinopsis</h3>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $book->description ?? 'Tidak ada ringkasan rinci untuk buku ini.' }}</p>
            </div>

            <div class="pt-4">
                @auth
                    @if(auth()->user()->isUser())
                        @if($book->available_stock > 0)
                            <form action="{{ route('user.borrow.store', $book->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Ajukan peminjaman buku {{ addslashes($book->title) }} selama 7 hari?')"
                                    class="bg-[#00B074] hover:bg-[#009663] text-white font-bold text-xs px-6 py-3 rounded-2xl transition-all shadow-md shadow-[#00B074]/30">
                                    Ajukan Peminjaman Sekarang
                                </button>
                            </form>
                        @endif
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-[#00B074] text-white font-bold text-xs px-6 py-3 rounded-2xl inline-block shadow-md shadow-[#00B074]/30">
                        Login Untuk Meminjam
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
