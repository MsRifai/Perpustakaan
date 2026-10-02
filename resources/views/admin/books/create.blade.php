@extends('layouts.admin')

@section('title', 'Tambah Buku Baru - PustakaSmart')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Tambah Buku Baru</h1>
            <p class="text-slate-500 text-xs mt-1">Isi data lengkap buku untuk ditambahkan ke katalog perpustakaan.</p>
        </div>
        <a href="{{ route('admin.books.index') }}" class="text-slate-500 hover:text-slate-800 text-xs font-bold">
            &larr; Kembali ke Master Buku
        </a>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Buku *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Buku *</label>
                    <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">ISBN *</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" required placeholder="978-XXXXXXXXX" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Penulis *</label>
                    <input type="text" name="author" value="{{ old('author') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Penerbit *</label>
                    <input type="text" name="publisher" value="{{ old('publisher') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun Terbit *</label>
                    <input type="number" name="publication_year" value="{{ old('publication_year', date('Y')) }}" min="1900" max="{{ date('Y') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jumlah Stok Fisik *</label>
                    <input type="number" name="total_stock" value="{{ old('total_stock', 1) }}" min="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Upload File Cover (Komputer)</label>
                    <input type="file" name="cover_image" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2 text-xs text-slate-600">
                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WebP (Otomatis disimpan permanen)</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Atau Gunakan Link / URL Gambar</label>
                    <input type="url" name="cover_url" value="{{ old('cover_url') }}" placeholder="https://example.com/cover.jpg" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                    <p class="text-[10px] text-slate-400 mt-1">Gunakan URL gambar dari internet jika tidak upload file</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]" placeholder="Ringkasan buku...">{{ old('description') }}</textarea>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <button type="submit" class="bg-[#00B074] hover:bg-[#009663] text-white font-bold px-6 py-3 rounded-xl transition-all text-xs shadow-md shadow-[#00B074]/30">
                    Simpan Buku Ke Katalog
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
