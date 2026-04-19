<?php
namespace Database\Seeders;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'user')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada data customer/product. Jalankan seeder lain dulu!');
            return;
        }

        $orders = [
            ['code' => 'ORD-001', 'total' => 1000000, 'status' => 'Diproses', 'items' => [[0, 2, 500000]]],
            ['code' => 'ORD-002', 'total' => 400000, 'status' => 'Dikirim', 'items' => [[1, 1, 400000]]],
            ['code' => 'ORD-003', 'total' => 1860000, 'status' => 'Selesai', 'items' => [[2, 3, 620000]]],
            ['code' => 'ORD-004', 'total' => 350000, 'status' => 'Pending', 'items' => [[3, 1, 350000]]],
            ['code' => 'ORD-005', 'total' => 750000, 'status' => 'Dibatalkan', 'items' => [[4, 1, 750000]]],
        ];

        foreach ($orders as $o) {
            // Cek apakah order sudah ada
            $order = Order::firstOrNew(['order_code' => $o['code']]);
            
            if (!$order->exists) {
                $order->fill([
                    'user_id' => $customers[0]->id,
                    'total_amount' => $o['total'],
                    'status' => $o['status'],
                    'shipping_address' => 'Jl. Contoh No. 123, Jakarta',
                    'payment_method' => 'Transfer Bank',
                ])->save();

                foreach ($o['items'] as $item) {
                    $product = $products[$item[0]] ?? $products->first();
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item[1],
                        'price' => $item[2],
                        'subtotal' => $item[1] * $item[2],
                    ]);
                }
            }
        }
        
        $this->command->info('✅ OrderSeeder selesai!');
    }
}