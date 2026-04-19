<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        @page {
            margin: 20px 15px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #4a4a4a;
            line-height: 1.4;
        }
        
        /* Header dengan dekorasi bunga */
        .header {
            text-align: center;
            padding: 15px 0;
            border-bottom: 3px double #ec4899;
            margin-bottom: 15px;
            position: relative;
        }
        .header::before, .header::after {
            content: "🌸 ✿ ❀  🌸";
            display: block;
            color: #f472b6;
            font-size: 14px;
            letter-spacing: 8px;
        }
        .header::before {
            margin-bottom: 8px;
        }
        .header::after {
            margin-top: 8px;
        }
        .header h1 {
            color: #7c3aed;
            font-size: 22px;
            margin: 0;
            font-weight: 700;
        }
        .header .subtitle {
            color: #a855f7;
            font-size: 12px;
            margin: 4px 0 0;
            font-style: italic;
        }
        .header .flower-divider {
            color: #f9a8d4;
            font-size: 10px;
            margin-top: 6px;
        }
        
        /* Info box */
        .info-box {
            background: linear-gradient(to right, #fdf2f8, #faf5ff);
            border: 1px solid #f9a8d4;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }
        .info-box span {
            font-size: 11px;
            color: #6b21a8;
        }
        
        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }
        thead {
            background: linear-gradient(135deg, #ec4899, #a855f7);
        }
        th {
            color: white;
            padding: 10px 6px;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 9px;
        }
        td {
            padding: 8px 6px;
            border-bottom: 1px solid #f3e8ff;
            text-align: center;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #fdf2f8;
        }
        tr:nth-child(odd) {
            background-color: #ffffff;
        }
        tr:hover {
            background-color: #fce7f3;
        }
        
        /* Status badges */
        .status-berhasil {
            background: #d1fae5;
            color: #065f46;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        .status-dibatalkan {
            background: #fee2e2;
            color: #991b1b;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #f9a8d4;
            text-align: center;
        }
        .footer .total-box {
            background: linear-gradient(135deg, #fdf2f8, #faf5ff);
            border: 2px solid #ec4899;
            border-radius: 10px;
            padding: 12px;
            display: inline-block;
            margin: 0 auto;
        }
        .footer .total-box .label {
            font-size: 11px;
            color: #7c3aed;
        }
        .footer .total-box .amount {
            font-size: 20px;
            font-weight: 700;
            color: #a855f7;
        }
        .footer .flower-footer {
            color: #f472b6;
            font-size: 12px;
            margin-top: 8px;
            letter-spacing: 5px;
        }
        .footer .copyright {
            font-size: 9px;
            color: #a78bfa;
            margin-top: 6px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>🌸 Laporan Transaksi 🌸</h1>
        <div class="subtitle">Gift & Hampers - Sistem Pemesanan Online</div>
        <div class="flower-divider">❀ ✿  ✿ ❀ ✿  ✿ ❀</div>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <span>📅 Dicetak: {{ date('d F Y, H:i') }}</span>
        <span> Admin: {{ auth()->user()->name ?? 'Administrator' }}</span>
        <span>📊 Total Transaksi: {{ $transactions->count() }}</span>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>🌸 No</th>
                <th>ID Transaksi</th>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $i => $order)
            <tr>
                <td style="color: #a855f7; font-weight: 600;">{{ $i + 1 }}</td>
                <td style="color: #7c3aed; font-weight: 600;">TRX-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td style="color: #ec4899; font-weight: 600;">{{ $order->order_code }}</td>
                <td>{{ $order->user->name }}</td>
                <td style="text-align: left;">{{ Str::limit($order->items->pluck('product.name')->join(', '), 25) }}</td>
                <td>{{ $order->items->sum('quantity') }}</td>
                <td style="font-weight: 600;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td>{{ $order->payment_method }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>
                    @php
                        $status = $order->status === 'Selesai' ? 'Berhasil' : $order->status;
                        $class = match($status) {
                            'Berhasil' => 'status-berhasil',
                            'Pending' => 'status-pending',
                            'Dibatalkan' => 'status-dibatalkan',
                            default => 'status-pending'
                        };
                    @endphp
                    <span class="{{ $class }}">
                        @if($status === 'Berhasil') ✅
                        @elseif($status === 'Pending') ⏳
                        @elseif($status === 'Dibatalkan') ❌
                        @endif
                        {{ $status }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="total-box">
            <div class="label">✨ Total Pendapatan (Berhasil) ✨</div>
            <div class="amount">Rp {{ number_format($transactions->where('status', 'Selesai')->sum('total_amount'), 0, ',', '.') }}</div>
        </div>
        <div class="flower-footer">🌺 🌷 🌸 🌼 🌻 🌸 🌷 🌺</div>
        <div class="copyright">
            © {{ date('Y') }} Gift & Hampers | Dibuat dengan 💜 dan 🌸
        </div>
    </div>

</body>
</html>