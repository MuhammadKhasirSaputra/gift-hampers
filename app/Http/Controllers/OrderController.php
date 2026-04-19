<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])->latest()->get();
        
        // Stats
        $stats = [
            'diproses' => Order::where('status', 'Diproses')->count(),
            'dikirim' => Order::where('status', 'Dikirim')->count(),
            'selesai' => Order::where('status', 'Selesai')->count(),
            'dibatalkan' => Order::where('status', 'Dibatalkan')->count(),
        ];
        
        return view('orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Diproses,Dikirim,Selesai,Dibatalkan'
        ]);
        
        $order->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Status pesanan berhasil diupdate!');
    }

    public function exportExcel()
    {
        return Excel::download(new OrdersExport, 'data-pesanan.xlsx');
    }

    public function exportPdf()
    {
        $orders = Order::with(['user', 'items.product'])->get();
        $pdf = Pdf::loadView('orders.pdf-export', compact('orders'));
        return $pdf->download('laporan-pesanan.pdf');
    }
}