<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            color: #1A1A2E;
            font-size: 10px;
            background: #fff;
        }

        /* ── Top accent bar ── */
        .top-bar {
            height: 10px;
            background: linear-gradient(90deg, #C2185B 0%, #E91E63 50%, #F48FB1 100%);
        }
        .accent-stripe {
            height: 3px;
            background: #FCE4EC;
        }

        /* ── Header ── */
        .header-wrapper {
            background: #FFF0F6;
            padding: 18px 24px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #FCE4EC;
        }
        .brand h1 {
            font-size: 20px;
            font-weight: 800;
            color: #C2185B;
            letter-spacing: -0.5px;
        }
        .brand h1 span { color: #E91E63; }
        .brand p {
            font-size: 9px;
            color: #9CA3AF;
            margin-top: 3px;
        }
        .header-meta {
            text-align: right;
        }
        .header-meta .date {
            font-size: 9px;
            color: #6B7280;
        }
        .header-meta .total-badge {
            display: inline-block;
            background: #C2185B;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            margin-top: 4px;
        }

        /* ── Stats pills ── */
        .stats-row {
            display: flex;
            gap: 10px;
            padding: 10px 24px;
            background: #fff;
            border-bottom: 1px solid #FCE4EC;
        }
        .pill {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }
        .pill-yellow { background:#FEF3C7; color:#92400E; }
        .pill-blue   { background:#DBEAFE; color:#1E40AF; }
        .pill-green  { background:#D1FAE5; color:#065F46; }
        .pill-red    { background:#FEE2E2; color:#991B1B; }

        /* ── Table ── */
        .table-wrapper {
            padding: 12px 24px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead tr {
            background: linear-gradient(90deg, #C2185B 0%, #AD1457 100%);
        }
        thead th {
            padding: 9px 8px;
            color: #fff;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            text-align: left;
        }
        thead th.center { text-align: center; }
        thead th.right  { text-align: right; }

        tbody tr:nth-child(odd)  { background: #FFF0F6; }
        tbody tr:nth-child(even) { background: #fff; }
        tbody tr:hover           { background: #FCE4EC; }

        td {
            padding: 8px 8px;
            border-bottom: 1px solid #FCE4EC;
            font-size: 9px;
            vertical-align: middle;
        }
        td.center { text-align: center; }
        td.right  { text-align: right; font-weight: 600; }
        td.code   { font-weight: 700; color: #C2185B; }

        /* ── Status badges ── */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: 700;
        }
        .b-selesai    { background:#D1FAE5; color:#065F46; }
        .b-dikirim    { background:#DBEAFE; color:#1E40AF; }
        .b-diproses   { background:#FEF3C7; color:#92400E; }
        .b-pending    { background:#F3F4F6; color:#6B7280; }
        .b-dibatalkan { background:#FEE2E2; color:#991B1B; }

        /* ── Total row ── */
        tfoot tr {
            background: linear-gradient(90deg, #C2185B 0%, #AD1457 100%);
        }
        tfoot td {
            padding: 10px 8px;
            color: #fff;
            font-weight: 700;
            font-size: 10px;
            border: none;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 14px;
            padding: 8px 24px;
            background: #FFF0F6;
            border-top: 2px solid #FCE4EC;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer span { font-size: 8px; color: #9CA3AF; font-style: italic; }

        /* ── Bottom bar ── */
        .bottom-bar {
            height: 8px;
            background: linear-gradient(90deg, #F48FB1 0%, #E91E63 50%, #C2185B 100%);
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
</head>
<body>

<div class="top-bar"></div>
<div class="accent-stripe"></div>

<div class="header-wrapper">
    <div class="brand">
        <h1>🌸 LAPORAN <span>PESANAN</span></h1>
        <p>Gift &amp; Hampers — Sistem Pemesanan Online</p>
    </div>
    <div class="header-meta">
        <div class="date">📅 Dicetak: {{ date('d F Y, H:i') }} WIB</div>
        <div class="total-badge">{{ $orders->count() }} Pesanan</div>
    </div>
</div>

<div class="stats-row">
    <span class="pill pill-yellow">📦 Diproses: {{ $orders->where('status','Diproses')->count() }}</span>
    <span class="pill pill-blue">🚚 Dikirim: {{ $orders->where('status','Dikirim')->count() }}</span>
    <span class="pill pill-green">✅ Selesai: {{ $orders->where('status','Selesai')->count() }}</span>
    <span class="pill pill-red">❌ Dibatalkan: {{ $orders->where('status','Dibatalkan')->count() }}</span>
    <span class="pill" style="background:#FCE4EC;color:#C2185B;margin-left:auto;">
        Total Nilai: Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}
    </span>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width:28px;" class="center">No</th>
                <th style="width:90px;">ID Pesanan</th>
                <th style="width:100px;">Pelanggan</th>
                <th>Produk</th>
                <th style="width:90px;">Metode Bayar</th>
                <th style="width:35px;" class="center">Qty</th>
                <th style="width:80px;" class="right">Total</th>
                <th style="width:75px;" class="center">Tanggal</th>
                <th style="width:80px;" class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $i => $order)
            @php
                $badgeClass = [
                    'Pending'    => 'b-pending',
                    'Diproses'   => 'b-diproses',
                    'Dikirim'    => 'b-dikirim',
                    'Selesai'    => 'b-selesai',
                    'Dibatalkan' => 'b-dibatalkan',
                ][$order->status] ?? 'b-pending';
                $icon = [
                    'Pending'=>'⏳','Diproses'=>'📦','Dikirim'=>'🚚','Selesai'=>'✅','Dibatalkan'=>'❌'
                ][$order->status] ?? '';
            @endphp
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td class="code">{{ $order->order_code }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ Str::limit($order->items->pluck('product.name')->join(', '), 40) }}</td>
                <td class="center">{{ $order->payment_method }}</td>
                <td class="center">{{ $order->items->sum('quantity') }}</td>
                <td class="right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td class="center">{{ $order->created_at->format('d M Y') }}</td>
                <td class="center">
                    <span class="badge {{ $badgeClass }}">{{ $icon }} {{ $order->status }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align:right;padding-right:12px;">GRAND TOTAL</td>
                <td class="right">Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="footer">
    <span>© {{ date('Y') }} Gift &amp; Hampers — Laporan ini digenerate secara otomatis oleh sistem</span>
    <span>Halaman 1 dari 1</span>
</div>

<div class="bottom-bar"></div>

</body>
</html>