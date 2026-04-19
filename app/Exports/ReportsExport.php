<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportsExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
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
        
        return $query->latest()->get()->map(function($order, $index) {
            $status = $order->status === 'Selesai' ? 'Berhasil' : $order->status;
            return [
                ($index + 1),
                'TRX-' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
                $order->order_code,
                $order->user->name,
                $order->items->pluck('product.name')->join(', '),
                $order->items->sum('quantity'),
                $order->total_amount,
                $order->payment_method,
                $order->created_at->format('d F Y'),
                $status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            '🌸 No',
            'ID Transaksi',
            'ID Pesanan',
            '👤 Pelanggan',
            '🎁 Produk',
            '📦 Jumlah',
            '💰 Total (Rp)',
            '💳 Pembayaran',
            '📅 Tanggal',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        
        // Header style - gradient pink-purple
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => 'EC4899'],
                'endColor' => ['rgb' => 'A855F7'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Alternate row colors
        for ($row = 2; $row <= $highestRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FDF2F8'],
                    ],
                ]);
            } else {
                $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFFFF'],
                    ],
                ]);
            }

            // Border for each row
            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E9D5FF'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        // Format currency column (G)
        for ($row = 2; $row <= $highestRow; $row++) {
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
        }

        // Status column styling
        for ($row = 2; $row <= $highestRow; $row++) {
            $statusCell = $sheet->getCell('J' . $row)->getValue();
            
            if ($statusCell === 'Berhasil') {
                $sheet->getStyle('J' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '065F46']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D1FAE5'],
                    ],
                ]);
            } elseif ($statusCell === 'Pending') {
                $sheet->getStyle('J' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '92400E']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF3C7'],
                    ],
                ]);
            } elseif ($statusCell === 'Dibatalkan') {
                $sheet->getStyle('J' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '991B1B']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEE2E2'],
                    ],
                ]);
            }
        }

        // Total row
        $totalRow = $highestRow + 2;
        $totalBerhasil = $this->collection()->where('9', 'Berhasil')->sum('6');
        
        $sheet->setCellValue('A' . $totalRow, '✨ TOTAL PENDAPATAN (BERHASIL):');
        $sheet->mergeCells('A' . $totalRow . ':G' . $totalRow);
        $sheet->getStyle('A' . $totalRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => '7C3AED'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ]);
        
        $sheet->setCellValue('H' . $totalRow, $totalBerhasil);
        $sheet->getStyle('H' . $totalRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'A855F7'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FAF5FF'],
            ],
            'numberFormat' => ['formatCode' => '"Rp" #,##0'],
        ]);

        // Flower decoration rows
        $flowerRow = $highestRow + 4;
        $sheet->setCellValue('A' . $flowerRow, '🌸 ✿ ❀  🌸 ✿  ✿ 🌸  ❀ ✿ 🌸');
        $sheet->mergeCells('A' . $flowerRow . ':J' . $flowerRow);
        $sheet->getStyle('A' . $flowerRow)->applyFromArray([
            'font' => [
                'size' => 14,
                'color' => ['rgb' => 'F472B6'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Copyright
        $copyRow = $highestRow + 5;
        $sheet->setCellValue('A' . $copyRow, '© ' . date('Y') . ' Gift & Hampers | Dibuat dengan 💜 dan ');
        $sheet->mergeCells('A' . $copyRow . ':J' . $copyRow);
        $sheet->getStyle('A' . $copyRow)->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => ['rgb' => 'A78BFA'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        return [];
    }

    public function title(): string
    {
        return '🌸 Laporan Transaksi';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 18,
            'C' => 16,
            'D' => 20,
            'E' => 35,
            'F' => 10,
            'G' => 18,
            'H' => 18,
            'I' => 16,
            'J' => 14,
        ];
    }
}