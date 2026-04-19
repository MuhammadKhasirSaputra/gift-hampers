@extends('layouts.admin')

@section('title', 'Pemesanan')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Pemesanan</h2>
    <p class="text-gray-500">Kelola daftar pemesanan pelanggan</p>
</div>

<!-- Search Bar -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
    <input type="text" id="searchInput" placeholder="Cari pesanan berdasarkan ID atau nama pelanggan..." 
        class="w-full bg-gray-50 border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-purple-400">
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-6 rounded-2xl border border-yellow-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['diproses'] }}</p>
                <p class="text-sm text-gray-600 mt-1">Diproses</p>
            </div>
            <div class="bg-yellow-400 p-3 rounded-xl">📦</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 p-6 rounded-2xl border border-blue-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['dikirim'] }}</p>
                <p class="text-sm text-gray-600 mt-1">Dikirim</p>
            </div>
            <div class="bg-blue-400 p-3 rounded-xl">🚚</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['selesai'] }}</p>
                <p class="text-sm text-gray-600 mt-1">Selesai</p>
            </div>
            <div class="bg-green-400 p-3 rounded-xl">✅</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-50 to-pink-50 p-6 rounded-2xl border border-red-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['dibatalkan'] }}</p>
                <p class="text-sm text-gray-600 mt-1">Dibatalkan</p>
            </div>
            <div class="bg-red-400 p-3 rounded-xl">❌</div>
        </div>
    </div>
</div>

<!-- Export Buttons -->
<div class="flex gap-3 mb-6">
    <a href="{{ route('orders.export.pdf') }}" 
        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
        📄 Export PDF
    </a>
    <a href="{{ route('orders.export.excel') }}" 
        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
        📊 Export Excel
    </a>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">ID Pesanan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Pelanggan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Produk</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Jumlah</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Total</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="ordersTable">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <span class="font-semibold text-pink-600">{{ $order->order_code }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 text-gray-700">
                        {{ $order->items->pluck('product.name')->join(', ') }}
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $order->items->sum('quantity') }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $order->created_at->format('d F Y') }}</td>
                    <td class="px-6 py-4">
                        @php
                        $statusColors = [
                            'Pending' => 'bg-gray-100 text-gray-700',
                            'Diproses' => 'bg-yellow-100 text-yellow-700',
                            'Dikirim' => 'bg-blue-100 text-blue-700',
                            'Selesai' => 'bg-green-100 text-green-700',
                            'Dibatalkan' => 'bg-red-100 text-red-700',
                        ];
                        $statusIcons = [
                            'Pending' => '⏳',
                            'Diproses' => '📦',
                            'Dikirim' => '🚚',
                            'Selesai' => '✅',
                            'Dibatalkan' => '❌',
                        ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] }}">
                            {{ $statusIcons[$order->status] }} {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('orders.show', $order) }}" 
                            class="text-purple-600 hover:text-purple-700 font-medium text-sm">
                            👁️ Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                        Belum ada pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    let term = e.target.value.toLowerCase();
    document.querySelectorAll('#ordersTable tr').forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
});
</script>
@endsection