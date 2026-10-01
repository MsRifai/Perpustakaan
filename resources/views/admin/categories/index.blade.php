@extends('layouts.admin')

@section('title', 'Kelola Kategori Buku - Panel Admin')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border border-slate-200/60 p-6 rounded-3xl shadow-sm">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Kelola Kategori Buku</h1>
            <p class="text-slate-500 text-xs mt-1">Tambah, perbarui, dan atur genre / kategori koleksi buku perpustakaan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Kategori -->
        <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm h-fit">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Tambah Kategori Baru</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Kategori *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Pemrograman, Novel, Sains"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">
                    @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Ringkas</label>
                    <textarea name="description" rows="3" placeholder="Ringkasan kategori..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#00B074]">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="w-full bg-[#00B074] hover:bg-[#009663] text-white font-bold py-3 px-4 rounded-2xl text-xs transition-all shadow-md shadow-[#00B074]/30">
                    + Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Tabel Kategori -->
        <div class="lg:col-span-2 bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Daftar Kategori Terdaftar</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Nama Kategori</th>
                            <th class="py-3.5 px-4">Deskripsi</th>
                            <th class="py-3.5 px-4">Jumlah Buku</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($categories as $cat)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5 px-4 font-bold text-slate-800">
                                    {{ $cat->name }}
                                    <span class="block text-[10px] text-slate-400 font-mono">slug: {{ $cat->slug }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate">{{ $cat->description ?? '-' }}</td>
                                <td class="py-3.5 px-4 font-bold text-[#00B074]">
                                    <span class="px-2.5 py-1 bg-[#00B074]/10 rounded-full text-[11px]">
                                        {{ $cat->books_count }} Buku
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ addslashes($cat->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-[11px] px-3 py-1.5 rounded-xl transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
