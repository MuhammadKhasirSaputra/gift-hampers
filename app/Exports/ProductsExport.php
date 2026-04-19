<?php
namespace App\Exports;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithStyles};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Product::with('category')->get()->map(fn($p) => [
            $p->name,
            $p->category?->name ?? '-',
            'Rp ' . number_format($p->price, 0, ',', '.'),
            $p->stock,
            $p->sold,
            $p->status,
        ]);
    }

    public function headings(): array
    {
        return ['Nama Produk', 'Kategori', 'Harga', 'Stok', 'Terjual', 'Status'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EC4899']],
            ],
        ];
    }
}