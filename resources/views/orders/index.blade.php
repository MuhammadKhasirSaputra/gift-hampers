@extends('layouts.admin')

@section('title', 'Pemesanan')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
    
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid #fce7f3;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 13px;
        color: #9ca3af;
        margin-top: 4px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 4px;
    }
    
    .page-sub {
        font-size: 14px;
        color: #9ca3af;
        margin-bottom: 24px;
    }

    .search-bar {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .search-bar input {
        border: none;
        outline: none;
        background: transparent;
        font-size: 14px;
        color: #1a1a2e;
        width: 100%;
    }
    
    .search-bar svg {
        width: 18px;
        height: 18px;
        color: #fbcfe8;
        flex-shrink: 0;
    }

    .export-row {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: transform 0.12s, box-shadow 0.12s;
        letter-spacing: 0.02em;
    }
    
    .btn-export:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    
    .btn-pdf {
        background: #E53E3E;
        color: #fff;
    }
    
    .btn-pdf .icon-wrap {
        width: 26px;
        height: 26px;
        background: rgba(255,255,255,0.2);
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .btn-excel {
        background: #38A169;
        color: #fff;
    }
    
    .btn-excel .icon-wrap {
        width: 26px;
        height: 26px;
        background: rgba(255,255,255,0.2);
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .table-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #fce7f3;
        overflow: hidden;
    }
    
    .table-card table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-card thead {
        background: #fff0f6;
        border-bottom: 1px solid #fce7f3;
    }
    
    .table-card th {
        padding: 14px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #db2777;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }
    
    .table-card td {
        padding: 14px 20px;
        font-size: 14px;
        color: #374151;
        border-bottom: 1px solid #fef3f8;
        vertical-align: middle;
    }
    
    .table-card tbody tr:hover {
        background: #fff7fb;
    }
    
    .table-card tbody tr:last-child td {
        border-bottom: none;
    }

    .order-code {
        font-weight: 700;
        color: #db2777;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }
    
    .s-pending    { background: #f3f4f6; color: #6b7280; }
    .s-diproses   { background: #fef9c3; color: #92400e; }
    .s-dikirim    { background: #dbeafe; color: #1d4ed8; }
    .s-selesai    { background: #dcfce7; color: #15803d; }
    .s-dibatalkan { background: #fee2e2; color: #dc2626; }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        font-weight: 600;
        color: #db2777;
        text-decoration: none;
        padding: 5px 12px;
        border: 1px solid #fbcfe8;
        border-radius: 8px;
        transition: background 0.12s;
    }
    
    .btn-detail:hover {
        background: #fff0f6;
    }
    
    .btn-detail svg {
        width: 14px;
        height: 14px;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #9ca3af;
        font-size: 14px;
    }
</style>

<div class="page-title">Pemesanan</div>
<div class="page-sub">Kelola daftar pemesanan pelanggan</div>

{{-- Search Bar --}}
<div class="search-bar">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
    </svg>
    <input type="text" id="searchInput" placeholder="Cari pesanan berdasarkan ID atau nama pelanggan...">
</div>

{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">📦</div>
        <div>
            <div class="stat-number">{{ $stats['diproses'] }}</div>
            <div class="stat-label">Diproses</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #3b82f6;">🚚</div>
        <div>
            <div class="stat-number">{{ $stats['dikirim'] }}</div>
            <div class="stat-label">Dikirim</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #10b981;">✅</div>
        <div>
            <div class="stat-number">{{ $stats['selesai'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2; color: #ef4444;">❌</div>
        <div>
            <div class="stat-number">{{ $stats['dibatalkan'] }}</div>
            <div class="stat-label">Dibatalkan</div>
        </div>
    </div>
</div>

{{-- Export Buttons --}}
<div class="export-row">
    <a href="{{ route('orders.create') }}" class="btn-export" style="background: #db2777; color: #fff;">
        <span style="font-size: 18px;">+</span>
        <span>Tambah Pesanan</span>
    </a>
    
    <a href="{{ route('orders.export.pdf') }}" class="btn-export btn-pdf">
        <div class="icon-wrap">📄</div>
        <span>Export PDF</span>
    </a>
    
    <a href="{{ route('orders.export.excel') }}" class="btn-export btn-excel">
        <div class="icon-wrap">📊</div>
        <span>Export Excel</span>
    </a>
</div>

{{-- Table --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="ordersTable">
            @forelse($orders as $order)
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
                <tr>
                    <td>
                        <span class="order-code">{{ $order->order_code }}</span>
                    </td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ Str::limit($order->items->pluck('product.name')->join(', '), 40) }}</td>
                    <td>{{ $order->items->sum('quantity') }}</td>
                    <td style="font-weight: 600;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="status-badge {{ $statusClass }}">
                            {{ $statusIcon }} {{ $order->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="btn-detail">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <svg style="width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            Belum ada pesanan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let term = this.value.toLowerCase();
    document.querySelectorAll('#ordersTable tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
});
</script>
@endsection