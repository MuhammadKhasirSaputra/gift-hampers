@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Tambah Produk Baru</h2>
    <p class="text-gray-500">Lengkapi form di bawah untuk menambah produk</p>
</div>

<div class="bg-white p-8 rounded-2xl shadow-card border border-gray-100">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Nama Produk -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none @error('name') border-red-500 @enderror"
                    placeholder="Contoh: Hampers Premium Lebaran">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                <select name="category_id" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Status <span class="text-red-500">*</span></label>
                <select name="status" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none">
                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price') }}" required min="0"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none @error('price') border-red-500 @enderror"
                    placeholder="500000">
                @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stok -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Stok <span class="text-red-500">*</span></label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none @error('stock') border-red-500 @enderror"
                    placeholder="25">
                @error('stock')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none @error('description') border-red-500 @enderror"
                    placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Gambar -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Gambar Produk</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none @error('image') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 2MB)</p>
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" 
                class="px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-semibold rounded-xl hover:shadow-lg transition">
                💾 Simpan Produk
            </button>
            <a href="{{ route('products.index') }}" 
                class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection