@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Dashboard</h2>
    <p class="text-gray-500">Selamat datang di sistem pemesanan gift dan hampers</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100 hover:shadow-card-hover transition">
        <div class="flex justify-between items-start">
            <div class="bg-pink-100 p-3 rounded-xl">
                <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <span class="text-green-500 text-sm font-bold bg-green-100 px-2 py-1 rounded-lg">+12%</span>
        </div>
        <h3 class="text-3xl font-bold mt-4 text-gray-800">248</h3>
        <p class="text-gray-500 text-sm mt-1">Total Pemesanan</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100 hover:shadow-card-hover transition">
        <div class="flex justify-between items-start">
            <div class="bg-purple-100 p-3 rounded-xl">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <span class="text-green-500 text-sm font-bold bg-green-100 px-2 py-1 rounded-lg">+3%</span>
        </div>
        <h3 class="text-3xl font-bold mt-4 text-gray-800">84</h3>
        <p class="text-gray-500 text-sm mt-1">Total Produk</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100 hover:shadow-card-hover transition">
        <div class="flex justify-between items-start">
            <div class="bg-yellow-100 p-3 rounded-xl">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-green-500 text-sm font-bold bg-green-100 px-2 py-1 rounded-lg">+18%</span>
        </div>
        <h3 class="text-3xl font-bold mt-4 text-gray-800">Rp 45.8M</h3>
        <p class="text-gray-500 text-sm mt-1">Pendapatan</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100 hover:shadow-card-hover transition">
        <div class="flex justify-between items-start">
            <div class="bg-green-100 p-3 rounded-xl">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <span class="text-green-500 text-sm font-bold bg-green-100 px-2 py-1 rounded-lg">+5%</span>
        </div>
        <h3 class="text-3xl font-bold mt-4 text-gray-800">24.5%</h3>
        <p class="text-gray-500 text-sm mt-1">Pertumbuhan</p>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100">
        <h4 class="font-bold text-lg mb-4 text-gray-800">Grafik Penjualan</h4>
        <div class="relative h-72">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100">
        <h4 class="font-bold text-lg mb-4 text-gray-800">Jumlah Pesanan</h4>
        <div class="relative h-72">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>
</div>

<!-- Produk Terlaris -->
<div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100">
    <h4 class="font-bold text-lg mb-4 text-gray-800">Produk Terlaris</h4>
    <div class="space-y-3">
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <span class="bg-gradient-to-br from-pink-500 to-purple-500 text-white w-10 h-10 flex items-center justify-center rounded-xl font-bold shadow-lg">1</span>
                <div>
                    <p class="font-bold text-gray-800">Hampers Premium Lebaran</p>
                    <p class="text-sm text-gray-500">45 terjual</p>
                </div>
            </div>
            <span class="font-bold text-gray-800 text-lg">Rp 22.5M</span>
        </div>
        
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <span class="bg-gradient-to-br from-pink-500 to-purple-500 text-white w-10 h-10 flex items-center justify-center rounded-xl font-bold shadow-lg">2</span>
                <div>
                    <p class="font-bold text-gray-800">Gift Box Valentine</p>
                    <p class="text-sm text-gray-500">38 terjual</p>
                </div>
            </div>
            <span class="font-bold text-gray-800 text-lg">Rp 15.2M</span>
        </div>

        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <span class="bg-gradient-to-br from-pink-500 to-purple-500 text-white w-10 h-10 flex items-center justify-center rounded-xl font-bold shadow-lg">3</span>
                <div>
                    <p class="font-bold text-gray-800">Hampers Natal Deluxe</p>
                    <p class="text-sm text-gray-500">32 terjual</p>
                </div>
            </div>
            <span class="font-bold text-gray-800 text-lg">Rp 19.8M</span>
        </div>

        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <span class="bg-gradient-to-br from-pink-500 to-purple-500 text-white w-10 h-10 flex items-center justify-center rounded-xl font-bold shadow-lg">4</span>
                <div>
                    <p class="font-bold text-gray-800">Gift Set Ulang Tahun</p>
                    <p class="text-sm text-gray-500">28 terjual</p>
                </div>
            </div>
            <span class="font-bold text-gray-800 text-lg">Rp 8.4M</span>
        </div>

        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <span class="bg-gradient-to-br from-pink-500 to-purple-500 text-white w-10 h-10 flex items-center justify-center rounded-xl font-bold shadow-lg">5</span>
                <div>
                    <p class="font-bold text-gray-800">Hampers Pernikahan</p>
                    <p class="text-sm text-gray-500">25 terjual</p>
                </div>
            </div>
            <span class="font-bold text-gray-800 text-lg">Rp 12.5M</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Grafik Penjualan
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        datasets: [{
            label: 'Penjualan',
            data: [4000, 3000, 5000, 4500, 6000, 5500],
            borderColor: '#ec4899',
            backgroundColor: 'rgba(236, 72, 153, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});

// Grafik Pesanan
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        datasets: [{
            label: 'Pesanan',
            data: [24, 18, 32, 28, 39, 35],
            backgroundColor: [
                'rgba(236, 72, 153, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(168, 85, 247, 0.8)'
            ],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
@endsection