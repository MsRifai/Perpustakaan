@extends('layouts.admin')

@section('title', 'Edit Buku - PustakaSmart')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Data Buku</h1>
            <p class="text-slate-500 text-xs mt-1">Perbarui informasi buku "{{ $book->title }}"</p>
        </div>
        <a href="{{ route('admin.books.index') }}" class="text-slate-500 hover:text-slate-800 text-xs font-bold">
            &larr; Kembali ke Master Buku
        </a>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Buku *</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Buku *</label>
                    <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">ISBN *</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Penulis *</label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Penerbit *</label>
                    <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun Terbit *</label>
                    <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Total Stok Fisik *</label>
                    <input type="number" name="total_stock" value="{{ old('total_stock', $book->total_stock) }}" min="0" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                    <p class="text-[11px] text-slate-400 mt-1">Stok tersedia saat ini: {{ $book->available_stock }}</p>
                </div>
            </div>

            @if($book->cover_url)
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center space-x-4">
                    <img src="{{ $book->cover_url }}" class="w-12 h-16 object-cover rounded-lg border border-slate-300 shrink-0">
                    <div>
                        <p class="text-xs font-bold text-slate-800">Cover Terpasang</p>
                        <p class="text-[10px] text-slate-400">Gambar saat ini siap ditampilkan secara permanen.</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ganti File Cover (Upload)</label>
                    <input type="file" name="cover_image" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2 text-xs text-slate-600">
                    <p class="text-[10px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah cover</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Atau Ubah Link / URL Gambar</label>
                    <input type="url" name="cover_url" value="{{ old('cover_url', str_starts_with($book->cover_image ?? '', 'http') ? $book->cover_image : '') }}" placeholder="https://example.com/cover.jpg" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">{{ old('description', $book->description) }}</textarea>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('admin.books.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-5 py-3 rounded-xl text-xs">Batal</a>
                <button type="submit" class="bg-[#00B074] hover:bg-[#009663] text-white font-bold px-6 py-3 rounded-xl transition-all text-xs shadow-md shadow-[#00B074]/30">
                    Update Data Buku
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
