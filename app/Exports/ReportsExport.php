<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromCollection, WithHeadings, WithStyles
{
    protected $period;

    public function __construct($period = '30')
    {
        $this->period = $period;
    }

    public function collection()
    {
        $query = Order::with(['user', 'items.product']);
        
        if ($this->period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($this->period === '7') {
            $query->whereBetween('created_at', [now()->subDays(7), now()]);
        } elseif ($this->period === '30') {
            $query->whereBetween('created_at', [now()->subDays(30), now()]);
        }
        
        return $query->latest()->get()->map(function($order) {
            return [
                'TRX-' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
                $order->order_code,
                $order->user->name,
                $order->items->pluck('product.name')->join(', '),
                $order->items->sum('quantity'),
                'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                $order->payment_method,
                $order->created_at->format('d F Y'),
                $order->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'ID Pesanan',
            'Pelanggan',
            'Produk',
            'Jumlah',
            'Total',
            'Metode Pembayaran',
            'Tanggal',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '10B981']],
            ],
        ];
    }
}