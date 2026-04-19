<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan</title>
    <style>
        @page { margin: 20px; }
        body { 
            font-family: Arial, sans-serif; 
            color: #2d3748; 
            line-height: 1.6; 
            font-size: 11px;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
        .header h1 { 
            font-size: 24px; 
            margin: 0 0 8px 0; 
            font-weight: 600;
        }
        .header .subtitle { 
            font-size: 13px; 
            opacity: 0.95;
        }
        
        .info-bar { 
            background: #f7fafc; 
            border-left: 4px solid #667eea;
            padding: 12px 15px; 
            margin-bottom: 20px; 
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
        }
        .info-bar strong { color: #667eea; }
        
        .table-container {
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        thead { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        th { 
            padding: 12px 8px; 
            text-align: left; 
            font-weight: 600; 
            font-size: 10px;
            text-transform: uppercase;
        }
        td { 
            padding: 10px 8px; 
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        tr:nth-child(even) { background: #f7fafc; }
        
        .status { 
            padding: 4px 10px; 
            border-radius: 20px; 
            font-size: 9px; 
            font-weight: 600; 
            display: inline-block;
        }
        .status-diproses { background: #fef3c7; color: #92400e; }
        .status-dikirim { background: #dbeafe; color: #1e40af; }
        .status-selesai { background: #d1fae5; color: #065f46; }
        .status-pending { background: #f3f4f6; color: #374151; }
        .status-dibatalkan { background: #fee2e2; color: #991b1b; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            font-size: 10px;
            color: #718096;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PESANAN</h1>
        <div class="subtitle">Gift & Hampers - Sistem Pemesanan Online</div>
    </div>

    <div class="info-bar">
        <span><strong>Tanggal Cetak:</strong> {{ date('d F Y, H:i') }}</span>
        <span><strong>Total Pesanan:</strong> {{ $orders->count() }}</span>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $i => $order)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="color:#667eea; font-weight:600;">{{ $order->order_code }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ Str::limit($order->items->pluck('product.name')->join(', '), 25) }}</td>
                    <td>{{ $order->items->sum('quantity') }}</td>
                    <td style="font-weight:600;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        @php
                        $statusClass = 'status-' . strtolower(str_replace(' ', '-', $order->status));
                        @endphp
                        <span class="status {{ $statusClass }}">{{ $order->status }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div>&copy; {{ date('Y') }} Gift & Hampers | Laporan Pesanan</div>
    </div>

</body>
</html>