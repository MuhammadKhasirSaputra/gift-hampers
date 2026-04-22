@extends('layouts.admin')

@section('title', 'Tambah Pesanan')

@section('content')

<style>
    .page-back { display:inline-flex; align-items:center; gap:6px; font-size:14px; font-weight:600; color:#db2777; text-decoration:none; margin-bottom:16px; }
    .page-back:hover { color:#be185d; }
    .page-title { font-size:24px; font-weight:700; color:#1a1a2e; margin-bottom:24px; }

    .form-grid {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 900px) { .form-grid { grid-template-columns: 1fr; } }

    .form-card {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 16px;
        padding: 22px;
    }
    .form-card h3 { font-size:15px; font-weight:700; color:#1a1a2e; margin-bottom:18px; padding-bottom:12px; border-bottom:1px solid #fce7f3; }

    .field { margin-bottom: 14px; }
    .field label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
    .field select,
    .field textarea,
    .field input[type="text"],
    .field input[type="number"] {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #fce7f3;
        border-radius: 10px;
        font-size: 14px; color: #1a1a2e;
        outline: none; background: #fff;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .field select:focus,
    .field textarea:focus,
    .field input:focus { border-color: #db2777; box-shadow: 0 0 0 3px #fce7f3; }
    .field textarea { resize: vertical; }

    .product-row {
        display: grid;
        grid-template-columns: 1fr 90px 110px 130px 40px;
        gap: 10px;
        align-items: end;
        background: #fff7fb;
        border: 1px solid #fce7f3;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 10px;
    }
    .product-row label { font-size:11px; font-weight:600; color:#9ca3af; display:block; margin-bottom:5px; }
    .product-row select,
    .product-row input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #fce7f3;
        border-radius: 8px;
        font-size: 13px; color: #1a1a2e;
        outline: none; background: #fff;
    }
    .product-row input[readonly] { background: #f9fafb; color: #6b7280; }
    .product-row select:focus,
    .product-row input:focus { border-color: #db2777; }
    .btn-remove {
        width: 34px; height: 34px;
        border: 1px solid #fee2e2;
        background: #fff;
        border-radius: 8px;
        color: #ef4444;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
        transition: background 0.12s;
        flex-shrink: 0;
    }
    .btn-remove:hover { background: #fee2e2; }

    .total-bar {
        display: flex; justify-content: space-between; align-items: center;
        padding-top: 14px; border-top: 1px solid #fce7f3; margin-top: 14px;
    }
    .total-bar span:first-child { font-size:15px; font-weight:700; color:#374151; }
    .total-amount { font-size:22px; font-weight:700; color:#db2777; }

    .btn-add-product {
        display: inline-flex; align-items: center; gap:6px;
        padding: 8px 14px;
        background: #fce7f3; color: #db2777;
        border: 1px solid #fbcfe8;
        border-radius: 8px;
        font-size: 13px; font-weight: 600;
        cursor: pointer; transition: background 0.12s;
    }
    .btn-add-product:hover { background: #fbcfe8; }

    .form-actions { display:flex; gap:12px; margin-top:20px; }
    .btn-save {
        flex: 1; padding: 13px;
        background: #db2777; color: white;
        font-weight: 700; font-size:15px;
        border: none; border-radius: 12px;
        cursor: pointer; transition: background 0.15s;
        display: flex; align-items:center; justify-content:center; gap:8px;
    }
    .btn-save:hover { background: #be185d; }
    .btn-cancel {
        padding: 13px 24px;
        background: #f3f4f6; color: #6b7280;
        font-weight: 700; font-size:15px;
        border-radius: 12px; text-decoration: none;
        display: flex; align-items: center;
        transition: background 0.15s;
    }
    .btn-cancel:hover { background: #e5e7eb; }
</style>

<a href="{{ route('orders.index') }}" class="page-back">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    Kembali
</a>
<p class="page-title">Tambah Pesanan Baru</p>

<form action="{{ route('orders.store') }}" method="POST">
    @csrf
    <div class="form-grid">

        {{-- Kolom Kiri --}}
        <div class="form-card">
            <h3>Detail Pelanggan</h3>

            <div class="field">
                <label>Pelanggan</label>
                <select name="customer_id" required>
                    <option value="">Pilih Pelanggan</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Status</label>
                <select name="status" required>
                    <option value="Pending">⏳ Pending</option>
                    <option value="Diproses">📦 Diproses</option>
                    <option value="Dikirim">🚚 Dikirim</option>
                    <option value="Selesai">✅ Selesai</option>
                    <option value="Dibatalkan">❌ Dibatalkan</option>
                </select>
            </div>

            <div class="field">
                <label>Metode Pembayaran</label>
                <select name="payment_method" required>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="E-Wallet">E-Wallet</option>
                    <option value="Kartu Kredit">Kartu Kredit</option>
                    <option value="COD">COD</option>
                </select>
            </div>

            <div class="field">
                <label>Alamat Pengiriman</label>
                <textarea name="shipping_address" rows="3" required placeholder="Jl. Contoh No. 123..."></textarea>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div>
            <div class="form-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #fce7f3;">
                    <h3 style="margin:0;padding:0;border:none;">Daftar Produk</h3>
                    <button type="button" onclick="addProductRow()" class="btn-add-product">
                        + Tambah Produk
                    </button>
                </div>

                <div id="productRows">
                    <div class="product-row">
                        <div>
                            <label>Produk</label>
                            <select name="products[0][product_id]" required onchange="updatePrice(0)" class="product-select">
                                <option value="">Pilih Produk</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }} (Stok: {{ $p->stock }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Jumlah</label>
                            <input type="number" name="products[0][quantity]" value="1" min="1" required onchange="calculateRow(0)" class="quantity-input">
                        </div>
                        <div>
                            <label>Harga</label>
                            <input type="number" name="products[0][price]" readonly class="price-input">
                        </div>
                        <div>
                            <label>Subtotal</label>
                            <input type="text" readonly class="subtotal-input">
                        </div>
                        <div style="display:flex;align-items:flex-end;">
                            <button type="button" onclick="removeRow(this)" class="btn-remove">×</button>
                        </div>
                    </div>
                </div>

                <div class="total-bar">
                    <span>Total Pesanan</span>
                    <span class="total-amount" id="grandTotal">Rp 0</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Simpan Pesanan
                </button>
                <a href="{{ route('orders.index') }}" class="btn-cancel">Batal</a>
            </div>
        </div>

    </div>
</form>

<script>
let rowCount = 1;

function addProductRow() {
    const container = document.getElementById('productRows');
    const firstRow = container.querySelector('.product-row');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelectorAll('input').forEach(el => el.value = '');
    newRow.querySelector('.quantity-input').value = 1;
    newRow.querySelector('.product-select').setAttribute('onchange', `updatePrice(${rowCount})`);
    newRow.querySelector('.quantity-input').setAttribute('onchange', `calculateRow(${rowCount})`);
    newRow.querySelector('[name*="product_id"]').name = `products[${rowCount}][product_id]`;
    newRow.querySelector('[name*="quantity"]').name = `products[${rowCount}][quantity]`;
    newRow.querySelector('[name*="price"]').name = `products[${rowCount}][price]`;
    container.appendChild(newRow);
    rowCount++;
}

function updatePrice(index) {
    const select = document.querySelector(`select[name="products[${index}][product_id]"]`);
    const priceInput = document.querySelector(`input[name="products[${index}][price]"]`);
    const price = select.options[select.selectedIndex]?.dataset.price || 0;
    priceInput.value = price;
    calculateRow(index);
}

function calculateRow(index) {
    const qty = parseFloat(document.querySelector(`input[name="products[${index}][quantity]"]`).value) || 0;
    const price = parseFloat(document.querySelector(`input[name="products[${index}][price]"]`).value) || 0;
    const subtotal = qty * price;
    const row = document.querySelector(`input[name="products[${index}][price]"]`).closest('.product-row');
    row.querySelector('.subtotal-input').value = 'Rp ' + subtotal.toLocaleString('id-ID');
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal-input').forEach(el => {
        const val = parseFloat(el.value.replace('Rp ', '').replace(/\./g, '')) || 0;
        total += val;
    });
    document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function removeRow(btn) {
    if (document.querySelectorAll('.product-row').length > 1) {
        btn.closest('.product-row').remove();
        calculateGrandTotal();
    }
}
</script>

@endsection