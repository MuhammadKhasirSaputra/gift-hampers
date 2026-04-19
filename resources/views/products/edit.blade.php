@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Edit Produk</h2>
    <p class="text-gray-500">Ubah informasi produk di bawah</p>
</div>

<div class="bg-white p-8 rounded-2xl shadow-card border border-gray-100">
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Nama Produk -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none @error('name') border-red-500 @enderror">
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
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Status <span class="text-red-500">*</span></label>
                <select name="status" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none">
                    <option value="Aktif" {{ old('status', $product->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Nonaktif" {{ old('status', $product->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none">
            </div>

            <!-- Stok -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Stok <span class="text-red-500">*</span></label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none">
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none">
{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Gambar Saat Ini -->
            @if($product->image)
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Gambar Saat Ini</label>
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" 
                    class="w-48 h-48 object-cover rounded-xl border border-gray-200">
            </div>
            @endif

            <!-- Upload Gambar Baru -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Upload Gambar Baru (Opsional)</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" 
                class="px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-semibold rounded-xl hover:shadow-lg transition">
                💾 Update Produk
            </button>
            <a href="{{ route('products.index') }}" 
                class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection