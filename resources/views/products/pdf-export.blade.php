<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Laporan Produk</title>
<style>body{font-family:sans-serif;margin:20px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:8px;text-align:left;font-size:12px}th{background:#ec4899;color:#fff}tr:nth-child(even){background:#fdf2f8}</style>
</head>
<body>
<h2 style="text-align:center">🎁 Gift & Hampers - Laporan Produk</h2>
<p style="text-align:center">Dicetak: {{ date('d F Y H:i') }}</p>
<table>
<thead><tr><th>No</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Terjual</th><th>Status</th></tr></thead>
<tbody>
@foreach($products as $i=>$p)
<tr><td>{{ $i+1 }}</td><td>{{ $p->name }}</td><td>{{ $p->category?->name ?? '-' }}</td><td>Rp {{ number_format($p->price,0,',','.') }}</td><td>{{ $p->stock }}</td><td>{{ $p->sold }}</td><td>{{ $p->status }}</td></tr>
@endforeach
</tbody>
</table>
</body></html>