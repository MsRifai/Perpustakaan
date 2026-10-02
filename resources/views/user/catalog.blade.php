@extends('layouts.app')

@section('title', 'Katalog Buku - PustakaSmart')

@section('content')
<div class="space-y-8">
    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white p-8 md:p-12 shadow-lg">
        <div class="max-w-2xl">
            <span class="px-3.5 py-1 text-xs font-bold uppercase tracking-wider bg-[#00B074] text-white rounded-full inline-block mb-3">Katalog Digital</span>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">Jelajahi Koleksi Buku Perpustakaan</h1>
            <p class="mt-2 text-slate-300 text-sm">Cari buku favorit Anda, cek ketersediaan stok fisik secara realtime, dan lakukan pengajuan peminjaman mandiri.</p>
        </div>

        <!-- Filter & Search Bar -->
        <form action="{{ route('catalog.index') }}" method="GET" class="mt-8 flex flex-col md:flex-row gap-3">
            <div class="relative flex-grow">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Judul Buku, Pengarang, atau ISBN..."
                    class="w-full pl-11 pr-4 py-3 bg-white text-slate-800 border border-slate-200 rounded-2xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#00B074] shadow-xs">
                <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="w-full md:w-64">
                <select name="category" class="w-full py-3 px-4 bg-white text-slate-800 border border-slate-200 rounded-2xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#00B074] shadow-xs">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->books_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-[#00B074] hover:bg-[#009663] text-white font-bold px-6 py-3 rounded-2xl text-xs transition-all shadow-md shadow-[#00B074]/30 shrink-0">
                Cari Buku
            </button>
        </form>
    </div>

    <!-- Category Pills -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 scrollbar-none">
        <a href="{{ route('catalog.index') }}"
            class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all border {{ !request('category') ? 'bg-[#00B074] text-white border-[#00B074]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">
            Semua Buku
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('catalog.index', ['category' => $cat->id]) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all border {{ request('category') == $cat->id ? 'bg-[#00B074] text-white border-[#00B074]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Books Grid -->
    @if($books->isEmpty())
        <div class="text-center py-16 bg-white border border-slate-200 rounded-3xl">
            <h3 class="text-lg font-bold text-slate-800">Tidak ada buku ditemukan</h3>
            <p class="text-slate-500 text-xs mt-1">Coba kata kunci atau kategori lain.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($books as $book)
                <div class="group bg-white border border-slate-200/80 hover:border-[#00B074] rounded-3xl p-5 flex flex-col justify-between transition-all duration-300 hover:shadow-xl hover:shadow-slate-200/50">
                    <div>
                        <!-- Cover Image & Badge -->
                        <a href="{{ route('catalog.show', $book->slug) }}" class="block relative w-full h-52 bg-slate-100 rounded-2xl overflow-hidden mb-4 border border-slate-100">
                            @if($book->cover_url)
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="text-center p-4 flex flex-col items-center justify-center h-full">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    <span class="text-xs text-slate-400 font-medium">Tanpa Cover</span>
                                </div>
                            @endif

                            <span class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-md text-[10px] font-bold uppercase tracking-wider text-[#00B074] rounded-lg border border-slate-200/60 shadow-xs">
                                {{ $book->category->name }}
                            </span>
                        </a>

                        <!-- Book Details -->
                        <a href="{{ route('catalog.show', $book->slug) }}" class="block">
                            <h3 class="text-base font-bold text-slate-800 group-hover:text-[#00B074] transition-colors line-clamp-2">{{ $book->title }}</h3>
                        </a>
                        <p class="text-xs text-slate-500 mt-1">Penulis: <span class="text-slate-700 font-semibold">{{ $book->author }}</span> ({{ $book->publication_year }})</p>
                        <p class="text-[11px] text-slate-400 font-mono">ISBN: {{ $book->isbn }}</p>

                        <p class="text-xs text-slate-600 mt-3 line-clamp-2 leading-relaxed">
                            {{ $book->description ?? 'Tidak ada deskripsi rinci untuk buku ini.' }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <!-- Stock Status -->
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Stok Fisik</span>
                            @if($book->available_stock > 0)
                                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    {{ $book->available_stock }} / {{ $book->total_stock }} Eks
                                </span>
                            @else
                                <span class="text-xs font-bold text-rose-500 flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    Stok Habis
                                </span>
                            @endif
                        </div>

                        <!-- Borrow Button -->
                        @auth
                            @if(auth()->user()->isUser())
                                @if($book->available_stock > 0)
                                    <form action="{{ route('user.borrow.store', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengajukan peminjaman buku {{ addslashes($book->title) }} selama 7 hari?')"
                                            class="bg-[#00B074] hover:bg-[#009663] text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-all shadow-md shadow-[#00B074]/30">
                                            Pinjam Buku
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="bg-slate-100 text-slate-400 font-bold text-xs px-4 py-2.5 rounded-xl cursor-not-allowed border border-slate-200">
                                        Habis
                                    </button>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs px-4 py-2.5 rounded-xl transition-colors border border-slate-200">
                                Login Pinjam
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $books->links() }}
        </div>
    @endif
</div>
@endsection
