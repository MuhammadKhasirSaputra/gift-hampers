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

class OrdersExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    public function collection()
    {
        return Order::with(['user', 'items.product'])->latest()->get()->map(function($order, $index) {
            return [
                ($index + 1),
                $order->order_code,
                $order->user->name,
                $order->items->pluck('product.name')->join(', '),
                $order->items->sum('quantity'),
                $order->total_amount,
                $order->created_at->format('d F Y'),
                $order->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            '🌸 No',
            'ID Pesanan',
            '👤 Pelanggan',
            '🎁 Produk',
            '📦 Jumlah',
            '💰 Total (Rp)',
            '📅 Tanggal',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        
        // Header - gradient purple
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => 'A855F7'],
                'endColor' => ['rgb' => '7C3AED'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Alternate rows
        for ($row = 2; $row <= $highestRow; $row++) {
            $bgColor = $row % 2 === 0 ? 'FAF5FF' : 'FFFFFF';
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E9D5FF']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        // Format currency
        for ($row = 2; $row <= $highestRow; $row++) {
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
        }

        // Status colors
        for ($row = 2; $row <= $highestRow; $row++) {
            $status = $sheet->getCell('H' . $row)->getValue();
            $colors = [
                'Diproses' => ['bg' => 'FEF3C7', 'text' => '92400E'],
                'Dikirim' => ['bg' => 'DBEAFE', 'text' => '1E40AF'],
                'Selesai' => ['bg' => 'D1FAE5', 'text' => '065F46'],
                'Dibatalkan' => ['bg' => 'FEE2E2', 'text' => '991B1B'],
                'Pending' => ['bg' => 'F3F4F6', 'text' => '374151'],
            ];
            if (isset($colors[$status])) {
                $sheet->getStyle('H' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => $colors[$status]['text']]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $colors[$status]['bg']]],
                ]);
            }
        }

        // Flower decoration
        $flowerRow = $highestRow + 3;
        $sheet->setCellValue('A' . $flowerRow, '🌸 ✿ ❀ ✿ 🌸 ✿  ✿ 🌸');
        $sheet->mergeCells('A' . $flowerRow . ':H' . $flowerRow);
        $sheet->getStyle('A' . $flowerRow)->applyFromArray([
            'font' => ['size' => 14, 'color' => ['rgb' => 'C084FC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Copyright
        $copyRow = $highestRow + 4;
        $sheet->setCellValue('A' . $copyRow, '© ' . date('Y') . ' Gift & Hampers | Dibuat dengan 💜');
        $sheet->mergeCells('A' . $copyRow . ':H' . $copyRow);
        $sheet->getStyle('A' . $copyRow)->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => 'A78BFA']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        return [];
    }

    public function title(): string
    {
        return '🌷 Daftar Pesanan';
    }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 16, 'C' => 20, 'D' => 35, 'E' => 10, 'F' => 18, 'G' => 16, 'H' => 14];
    }
}