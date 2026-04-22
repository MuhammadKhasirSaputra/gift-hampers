<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar semua pesanan
     */
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->get();

        // Statistik untuk dashboard cards
        $stats = [
            'diproses' => Order::where('status', 'Diproses')->count(),
            'dikirim' => Order::where('status', 'Dikirim')->count(),
            'selesai' => Order::where('status', 'Selesai')->count(),
            'dibatalkan' => Order::where('status', 'Dibatalkan')->count(),
        ];

        return view('orders.index', compact('orders', 'stats'));
    }

    /**
     * Tampilkan form tambah pesanan baru
     */
    public function create()
    {
        $customers = User::where('role', 'user')
            ->orWhere('role', 'admin')
            ->orderBy('name')
            ->get();
            
        $products = Product::where('status', 'Aktif')
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('customers', 'products'));
    }

    /**
     * Simpan pesanan baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'status' => 'required|in:Pending,Diproses,Dikirim,Selesai,Dibatalkan',
            'payment_method' => 'required|string|max:50',
            'shipping_address' => 'required|string|max:500',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ], [
            'products.required' => 'Minimal pilih satu produk.',
            'products.*.quantity.min' => 'Jumlah produk minimal 1.',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $orderCode = 'ORD-' . str_pad((Order::max('id') ?? 0) + 1, 3, '0', STR_PAD_LEFT);

            // 1. Buat Order
            $order = Order::create([
                'user_id' => $validated['customer_id'],
                'order_code' => $orderCode,
                'status' => $validated['status'],
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
                'total_amount' => 0, // Akan diupdate setelah semua item ditambahkan
            ]);

            // 2. Proses setiap produk
            foreach ($validated['products'] as $item) {
                $product = Product::find($item['product_id']);
                
                // Cek stok tersedia
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak cukup. Tersedia: {$product->stock}");
                }

                $subtotal = $item['quantity'] * $item['price'];
                $totalAmount += $subtotal;

                // Simpan Order Item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                // Kurangi stok & tambah terjual
                $product->decrement('stock', $item['quantity']);
                $product->increment('sold', $item['quantity']);
            }

            // Update total amount di order
            $order->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Pesanan ' . $orderCode . ' berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan detail pesanan
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product.category']);
        return view('orders.show', compact('order'));
    }

    /**
     * Tampilkan form edit pesanan
     */
    public function edit(Order $order)
    {
        $order->load('items.product');
        
        $customers = User::where('role', 'user')
            ->orWhere('role', 'admin')
            ->orderBy('name')
            ->get();
            
        $products = Product::where('status', 'Aktif')
            ->orderBy('name')
            ->get();

        return view('orders.edit', compact('order', 'customers', 'products'));
    }

    /**
     * Update pesanan yang sudah ada
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'status' => 'required|in:Pending,Diproses,Dikirim,Selesai,Dibatalkan',
            'payment_method' => 'required|string|max:50',
            'shipping_address' => 'required|string|max:500',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Kembalikan stok dari item lama (rollback)
            foreach ($order->items as $oldItem) {
                $oldItem->product->increment('stock', $oldItem->quantity);
                $oldItem->product->decrement('sold', $oldItem->quantity);
            }
            
            // Hapus semua item lama
            $order->items()->delete();

            $totalAmount = 0;

            // 2. Buat item baru dengan data yang diupdate
            foreach ($validated['products'] as $item) {
                $product = Product::find($item['product_id']);
                
                // Cek stok tersedia
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak cukup. Tersedia: {$product->stock}");
                }

                $subtotal = $item['quantity'] * $item['price'];
                $totalAmount += $subtotal;

                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                // Kurangi stok & tambah terjual
                $product->decrement('stock', $item['quantity']);
                $product->increment('sold', $item['quantity']);
            }

            // Update informasi order
            $order->update([
                'user_id' => $validated['customer_id'],
                'status' => $validated['status'],
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
                'total_amount' => $totalAmount,
            ]);

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Pesanan ' . $order->order_code . ' berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui pesanan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus pesanan dan kembalikan stok
     */
    public function destroy(Order $order)
    {
        DB::beginTransaction();
        try {
            // Kembalikan stok semua produk yang ada di order ini
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
                $item->product->decrement('sold', $item->quantity);
            }

            // Hapus item dan order
            $order->items()->delete();
            $order->delete();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Pesanan ' . $order->order_code . ' berhasil dihapus! Stok produk telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Update status pesanan saja (untuk halaman show)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Diproses,Dikirim,Selesai,Dibatalkan'
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status pesanan berhasil diupdate menjadi ' . $validated['status']);
    }

    /**
     * Export pesanan ke Excel
     */
    public function exportExcel()
    {
        return Excel::download(new OrdersExport, 'data-pesanan-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export pesanan ke PDF
     */
    public function exportPdf()
    {
        $orders = Order::with(['user', 'items.product'])->get();
        $pdf = Pdf::loadView('orders.pdf-export', compact('orders'));
        return $pdf->download('laporan-pesanan-' . date('Y-m-d') . '.pdf');
    }
}