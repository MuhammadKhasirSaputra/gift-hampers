<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class ExportController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'data-produk-hampers.xlsx');
    }

    public function exportPdf()
    {
        $products = Product::with('category')->get();
        $pdf = Pdf::loadView('products.pdf-export', compact('products'));
        return $pdf->download('laporan-produk.pdf');
    }
}