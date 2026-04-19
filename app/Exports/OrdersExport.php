<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Order::with(['user', 'items.product'])->get()->map(function($order) {
            return [
                $order->order_code,
                $order->user->name,
                $order->items->pluck('product.name')->join(', '),
                $order->items->sum('quantity'),
                'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                $order->created_at->format('d F Y'),
                $order->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Pelanggan',
            'Produk',
            'Jumlah',
            'Total',
            'Tanggal',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'A855F7']],
            ],
        ];
    }
}