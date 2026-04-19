<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30');
        
        // Query berdasarkan periode
        $query = Order::with(['user', 'items.product']);
        
        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === '7') {
            $query->whereBetween('created_at', [now()->subDays(7), now()]);
        } elseif ($period === '30') {
            $query->whereBetween('created_at', [now()->subDays(30), now()]);
        }
        
        $transactions = $query->latest()->get();
        
        // Stats
        $stats = [
            'total_transaksi' => $transactions->count(),
            'transaksi_berhasil' => $transactions->where('status', 'Selesai')->count(),
            'total_pendapatan' => $transactions->where('status', 'Selesai')->sum('total_amount'),
        ];
        
        return view('reports.index', compact('transactions', 'stats', 'period'));
    }

    public function exportExcel(Request $request)
    {
        $period = $request->get('period', '30');
        return Excel::download(new ReportsExport($period), 'laporan-transaksi.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $period = $request->get('period', '30');
        
        $query = Order::with(['user', 'items.product']);
        
        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === '7') {
            $query->whereBetween('created_at', [now()->subDays(7), now()]);
        } elseif ($period === '30') {
            $query->whereBetween('created_at', [now()->subDays(30), now()]);
        }
        
        $transactions = $query->latest()->get();
        
        $pdf = Pdf::loadView('reports.pdf-export', compact('transactions'));
        return $pdf->download('laporan-transaksi.pdf');
    }
}