@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Laporan</h2>
        <p class="text-gray-500">Laporan transaksi dan penjualan</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('reports.export.pdf', ['period' => $period]) }}" 
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium flex items-center gap-2">
            <span>📄</span> Export PDF
        </a>
        <a href="{{ route('reports.export.excel', ['period' => $period]) }}" 
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium flex items-center gap-2">
            <span>📊</span> Export Excel
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-pink-500 to-purple-500 p-6 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-4xl font-bold text-white">{{ $stats['total_transaksi'] }}</p>
                <p class="text-white/90 text-sm mt-1">Total Transaksi</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">📈</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-emerald-500 p-6 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-4xl font-bold text-white">{{ $stats['transaksi_berhasil'] }}</p>
                <p class="text-white/90 text-sm mt-1">Transaksi Berhasil</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">✅</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-indigo-500 p-6 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-white">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
                <p class="text-white/90 text-sm mt-1">Total Pendapatan</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">💰</div>
        </div>
    </div>
</div>

<!-- Period Filter -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-4">
        <span class="text-gray-700 font-medium">Periode:</span>
        <button type="submit" name="period" value="today" 
            class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === 'today' ? 'bg-pink-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Hari ini
        </button>
        <button type="submit" name="period" value="7" 
            class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === '7' ? 'bg-pink-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            7 Hari Terakhir
        </button>
        <button type="submit" name="period" value="30" 
            class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === '30' ? 'bg-pink-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            30 Hari Terakhir
        </button>
        <button type="submit" name="period" value="custom" 
            class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === 'custom' ? 'bg-pink-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Custom
        </button>
    </form>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-lg text-gray-800">Data Transaksi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">ID Transaksi</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">ID Pesanan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Pelanggan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Produk</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Jumlah</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Total</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Metode Pembayaran</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <span class="font-semibold text-purple-600">TRX-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-pink-600">{{ $order->order_code }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 text-gray-700">
                        {{ Str::limit($order->items->pluck('product.name')->join(', '), 40) }}
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $order->items->sum('quantity') }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 text-sm">{{ $order->payment_method }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $order->created_at->format('d F Y') }}</td>
                    <td class="px-6 py-4">
                        @php
                        $statusClass = [
                            'Berhasil' => 'bg-green-100 text-green-700',
                            'Pending' => 'bg-gray-100 text-gray-700',
                            'Dibatalkan' => 'bg-red-100 text-red-700',
                        ];
                        $status = $order->status === 'Selesai' ? 'Berhasil' : $order->status;
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass[$status] ?? 'bg-gray-100' }}">
                            {{ $status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-10 text-center text-gray-400">
                        Tidak ada data transaksi untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($transactions->count() > 0)
            <tfoot class="bg-gradient-to-r from-purple-50 to-pink-50 border-t border-gray-200">
                <tr>
                    <td colspan="5" class="px-6 py-4 text-right font-semibold text-gray-700">Total Pendapatan (Berhasil):</td>
                    <td colspan="4" class="px-6 py-4 font-bold text-xl text-gray-800">
                        Rp {{ number_format($transactions->where('status', 'Selesai')->sum('total_amount'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection