@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')

<style>
    .page-back { display:inline-flex; align-items:center; gap:6px; font-size:14px; font-weight:600; color:#db2777; text-decoration:none; margin-bottom:16px; }
    .page-back:hover { color:#be185d; }
    .page-title { font-size:24px; font-weight:700; color:#1a1a2e; margin-bottom:4px; }
    .page-sub { font-size:14px; color:#9ca3af; margin-bottom:24px; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }
    @media (max-width: 900px) { .info-grid { grid-template-columns: 1fr; } }

    .info-card {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 16px;
        padding: 22px;
    }
    .info-card h3 {
        font-size: 15px; font-weight: 700; color: #1a1a2e;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #fce7f3;
    }
    .info-row {
        display: flex; justify-content: space-between; align-items: flex-start;
        gap: 12px; margin-bottom: 10px; font-size: 14px;
    }
    .info-row:last-child { margin-bottom: 0; }
    .info-label { color: #9ca3af; flex-shrink: 0; }
    .info-value { font-weight: 600; color: #1a1a2e; text-align: right; }
    .info-value.pink { color: #db2777; }

    .status-badge {
        padding: 4px 12px; border-radius: 20px;
        font-size: 12px; font-weight: 600; display: inline-block;
    }
    .s-pending    { background:#f3f4f6; color:#6b7280; }
    .s-diproses   { background:#fef9c3; color:#92400e; }
    .s-dikirim    { background:#dbeafe; color:#1d4ed8; }
    .s-selesai    { background:#dcfce7; color:#15803d; }
    .s-dibatalkan { background:#fee2e2; color:#dc2626; }

    .total-row {
        display: flex; justify-content: space-between; align-items: center;
        padding-top: 14px; border-top: 1px solid #fce7f3; margin-top: 12px;
        font-size: 16px; font-weight: 700; color: #1a1a2e;
    }
    .total-row span:last-child { color: #db2777; font-size: 18px; }

    .status-form select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #fce7f3;
        border-radius: 10px;
        font-size: 14px;
        color: #1a1a2e;
        outline: none;
        margin-bottom: 12px;
        background: #fff;
    }
    .status-form select:focus { border-color: #db2777; box-shadow: 0 0 0 3px #fce7f3; }
    .btn-update-status {
        width: 100%;
        padding: 12px;
        background: #db2777;
        color: white;
        font-weight: 700;
        font-size: 14px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-update-status:hover { background: #be185d; }

    .table-card {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .table-card-header {
        padding: 16px 22px;
        border-bottom: 1px solid #fce7f3;
        font-size: 15px; font-weight: 700; color: #1a1a2e;
    }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead { background: #fff0f6; }
    .table-card th {
        padding: 12px 22px;
        text-align: left; font-size: 11px; font-weight: 700;
        color: #db2777; text-transform: uppercase; letter-spacing: 0.05em;
        white-space: nowrap;
    }
    .table-card td {
        padding: 14px 22px;
        font-size: 14px; color: #374151;
        border-bottom: 1px solid #fef3f8;
        vertical-align: middle;
    }
    .table-card tfoot td {
        padding: 14px 22px;
        background: #fff0f6;
        font-weight: 700; font-size: 15px;
        border-bottom: none;
    }
    .table-card tfoot .total-label { color: #6b7280; text-align: right; }
    .table-card tfoot .total-amount { color: #db2777; font-size: 18px; }
    .product-img { width: 52px; height: 52px; object-fit: cover; border-radius: 10px; margin-right: 14px; }
    .product-name { font-weight: 600; color: #1a1a2e; }
    .product-cat { font-size: 12px; color: #9ca3af; margin-top: 2px; }

    .action-row { display: flex; gap: 10px; }
    .btn-pdf {
        display:inline-flex; align-items:center; gap:6px;
        padding:10px 20px; border-radius:10px;
        background:#fee2e2; color:#dc2626;
        font-weight:600; font-size:14px;
        text-decoration:none; transition:opacity 0.15s;
    }
    .btn-pdf:hover { opacity:0.8; }
    .btn-back {
        display:inline-flex; align-items:center; gap:6px;
        padding:10px 20px; border-radius:10px;
        background:#f3f4f6; color:#6b7280;
        font-weight:600; font-size:14px;
        text-decoration:none; transition:background 0.15s;
    }
    .btn-back:hover { background:#e5e7eb; }
</style>

<a href="{{ route('orders.index') }}" class="page-back">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    Kembali ke Daftar Pesanan
</a>

<p class="page-title">Detail Pesanan</p>
<p class="page-sub">{{ $order->order_code }}</p>

@php
    $statusClass = [
        'Pending'    => 's-pending',
        'Diproses'   => 's-diproses',
        'Dikirim'    => 's-dikirim',
        'Selesai'    => 's-selesai',
        'Dibatalkan' => 's-dibatalkan',
    ][$order->status] ?? 's-pending';
    $statusIcon = [
        'Pending'    => '⏳',
        'Diproses'   => '📦',
        'Dikirim'    => '🚚',
        'Selesai'    => '✅',
        'Dibatalkan' => '❌',
    ][$order->status] ?? '';
@endphp

<div class="info-grid">
    {{-- Info Pesanan --}}
    <div class="info-card">
        <h3>Informasi Pesanan</h3>
        <div class="info-row">
            <span class="info-label">ID Pesanan</span>
            <span class="info-value pink">{{ $order->order_code }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal</span>
            <span class="info-value">{{ $order->created_at->format('d F Y, H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="status-badge {{ $statusClass }}">{{ $statusIcon }} {{ $order->status }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Pembayaran</span>
            <span class="info-value">{{ $order->payment_method }}</span>
        </div>
        <div class="total-row">
            <span>Total</span>
            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Info Pelanggan --}}
    <div class="info-card">
        <h3>Informasi Pelanggan</h3>
        <div class="info-row">
            <span class="info-label">Nama</span>
            <span class="info-value">{{ $order->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value" style="word-break:break-all;">{{ $order->user->email }}</span>
        </div>
        <div style="margin-top:10px;">
            <span class="info-label" style="font-size:13px;">Alamat Pengiriman</span>
            <p style="font-size:14px;color:#374151;margin-top:6px;line-height:1.6;">{{ $order->shipping_address }}</p>
        </div>
    </div>

    {{-- Update Status --}}
    <div class="info-card">
        <h3>Update Status</h3>
        <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="status-form">
            @csrf
            <select name="status">
                <option value="Pending"    {{ $order->status == 'Pending'    ? 'selected' : '' }}>⏳ Pending</option>
                <option value="Diproses"   {{ $order->status == 'Diproses'   ? 'selected' : '' }}>📦 Diproses</option>
                <option value="Dikirim"    {{ $order->status == 'Dikirim'    ? 'selected' : '' }}>🚚 Dikirim</option>
                <option value="Selesai"    {{ $order->status == 'Selesai'    ? 'selected' : '' }}>✅ Selesai</option>
                <option value="Dibatalkan" {{ $order->status == 'Dibatalkan' ? 'selected' : '' }}>❌ Dibatalkan</option>
            </select>
            <button type="submit" class="btn-update-status">Update Status</button>
        </form>
    </div>
</div>

{{-- Tabel Produk --}}
<div class="table-card">
    <div class="table-card-header">Produk yang Dipesan</div>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;">
                            @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}"
                                    alt="{{ $item->product->name }}" class="product-img">
                            @endif
                            <div>
                                <div class="product-name">{{ $item->product->name }}</div>
                                <div class="product-cat">{{ $item->product->category->name ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td style="font-weight:600;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="total-label">Total Pesanan</td>
                    <td class="total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Action Buttons --}}
<div class="action-row">
    <a href="{{ route('orders.export.pdf') }}?order_id={{ $order->id }}" class="btn-pdf">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        Export PDF
    </a>
    <a href="{{ route('orders.index') }}" class="btn-back">Kembali</a>
</div>

@endsection