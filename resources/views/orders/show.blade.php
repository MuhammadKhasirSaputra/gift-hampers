@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="mb-6">
    <a href="{{ route('orders.index') }}" class="text-purple-600 hover:text-purple-700 font-medium mb-4 inline-block">
        ← Kembali ke Daftar Pesanan
    </a>
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Detail Pesanan</h2>
    <p class="text-gray-500">{{ $order->order_code }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Info Pesanan -->
    <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100">
        <h3 class="font-bold text-lg mb-4 text-gray-800">Informasi Pesanan</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-500">ID Pesanan:</span>
                <span class="font-semibold text-pink-600">{{ $order->order_code }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal:</span>
                <span class="font-semibold">{{ $order->created_at->format('d F Y, H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status:</span>
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
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Metode Pembayaran:</span>
                <span class="font-semibold">{{ $order->payment_method }}</span>
            </div>
            <div class="border-t pt-3 mt-3">
                <div class="flex justify-between text-lg font-bold">
                    <span>Total:</span>
                    <span class="text-purple-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Pelanggan -->
    <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100">
        <h3 class="font-bold text-lg mb-4 text-gray-800">Informasi Pelanggan</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-500">Nama:</span>
                <span class="font-semibold">{{ $order->user->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Email:</span>
                <span class="font-semibold">{{ $order->user->email }}</span>
            </div>
            <div>
                <span class="text-gray-500 block mb-1">Alamat Pengiriman:</span>
                <p class="text-gray-700">{{ $order->shipping_address }}</p>
            </div>
        </div>
    </div>

    <!-- Update Status -->
    <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100">
        <h3 class="font-bold text-lg mb-4 text-gray-800">Update Status</h3>
        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
            @csrf
            <select name="status" 
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none mb-3">
                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                <option value="Diproses" {{ $order->status == 'Diproses' ? 'selected' : '' }}>📦 Diproses</option>
                <option value="Dikirim" {{ $order->status == 'Dikirim' ? 'selected' : '' }}>🚚 Dikirim</option>
                <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                <option value="Dibatalkan" {{ $order->status == 'Dibatalkan' ? 'selected' : '' }}>❌ Dibatalkan</option>
            </select>
            <button type="submit" 
                class="w-full px-4 py-3 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-semibold rounded-xl hover:shadow-lg transition">
                Update Status
            </button>
        </form>
    </div>
</div>

<!-- Daftar Produk -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-lg text-gray-800">Produk yang Dipesan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Produk</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Harga</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Jumlah</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" 
                                    alt="{{ $item->product->name }}" 
                                    class="w-16 h-16 object-cover rounded-lg mr-4">
                            @endif
                            <div>
                                <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $item->product->category->name ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gradient-to-r from-purple-50 to-pink-50 border-t">
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700">Total:</td>
                    <td class="px-6 py-4 font-bold text-xl text-purple-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Action Buttons -->
<div class="mt-6 flex gap-3">
    <a href="{{ route('orders.export.pdf') }}?order_id={{ $order->id }}" 
        class="px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition flex items-center gap-2">
        📄 Export PDF
    </a>
    <a href="{{ route('orders.index') }}" 
        class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition">
        Kembali
    </a>
</div>
@endsection