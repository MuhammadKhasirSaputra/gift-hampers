@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold">Produk</h2>
        <p class="text-gray-500">Kelola daftar gift dan hampers</p>
    </div>
    <div class="space-x-2">
        <a href="{{ route('products.export.excel') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">📊 Excel</a>
        <a href="{{ route('products.export.pdf') }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">📄 PDF</a>
        <a href="{{ route('products.create') }}" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-500 text-white rounded-lg shadow-lg hover:shadow-xl">+ Tambah Produk</a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
    <input type="text" id="search" placeholder="Cari produk..." class="w-full bg-gray-50 border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-purple-400">
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="grid">
    @forelse($products as $p)
    <div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden hover:shadow-card-hover transition">
        <div class="h-48 bg-gray-200 relative">
            @if($p->image)
                <img src="{{ Storage::url($p->image) }}" class="w-full h-full object-cover">
            @else
                <div class="flex items-center justify-center h-full text-gray-400">️</div>
            @endif
            <span class="absolute top-3 right-3 bg-black/80 text-white text-xs px-2 py-1 rounded-full">{{ $p->status }}</span>
        </div>
        <div class="p-4">
            <h3 class="font-bold text-gray-800 truncate">{{ $p->name }}</h3>
            <p class="text-xs text-gray-500 mb-2">{{ $p->category?->name ?? 'Tanpa Kategori' }}</p>
            <div class="flex justify-between items-end mt-3">
                <div>
                    <p class="font-bold text-lg">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Stok: {{ $p->stock }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Terjual</p>
                    <p class="font-bold text-pink-500">{{ $p->sold }}</p>
                </div>
            </div>
            <div class="mt-4 flex gap-2 border-t pt-4">
                <a href="{{ route('products.edit', $p) }}" class="flex-1 text-center border rounded-lg py-1.5 text-sm hover:bg-gray-50">✏️ Edit</a>
                <form action="{{ route('products.destroy', $p) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus produk ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full border border-red-200 text-red-500 rounded-lg py-1.5 text-sm hover:bg-red-50">🗑️ Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-10 text-gray-400">Belum ada produk.</div>
    @endforelse
</div>

<script>
document.getElementById('search').addEventListener('keyup', function(e){
    let term = e.target.value.toLowerCase();
    document.querySelectorAll('#grid > div').forEach(card => {
        let title = card.querySelector('h3').textContent.toLowerCase();
        card.style.display = title.includes(term) ? 'block' : 'none';
    });
});
</script>
@endsection