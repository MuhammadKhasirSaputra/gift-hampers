<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gift & Hampers')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 fixed h-full overflow-y-auto z-50">
            <div class="p-6 flex items-center space-x-3">
                <div class="bg-gradient-to-br from-pink-400 to-purple-400 p-2 rounded-lg shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-gray-800">Gift & Hampers</h1>
                    <p class="text-xs text-gray-500">Sistem Pemesanan</p>
                </div>
            </div>

            <!-- User Info -->
            <div class="px-4 mb-4">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-xl border border-purple-100">
                    <p class="font-semibold text-gray-800 text-sm">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    <span class="inline-block mt-2 px-2 py-1 bg-purple-500 text-white text-xs rounded-full capitalize">{{ auth()->user()->role }}</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="px-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-pink-500 to-purple-500 text-white shadow-lg scale-[1.02]' : 'text-gray-600 hover:bg-gray-50 hover:scale-[1.01]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Produk -->
                <a href="{{ route('products.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-gradient-to-r from-pink-500 to-purple-500 text-white shadow-lg scale-[1.02]' : 'text-gray-600 hover:bg-gray-50 hover:scale-[1.01]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="font-medium">Produk</span>
                </a>

                <!-- Pemesanan -->
                <a href="{{ route('orders.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('orders.*') ? 'bg-gradient-to-r from-pink-500 to-purple-500 text-white shadow-lg scale-[1.02]' : 'text-gray-600 hover:bg-gray-50 hover:scale-[1.01]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium">Pemesanan</span>
                    @php
                        $pendingOrders = \App\Models\Order::where('status', 'Pending')->count();
                    @endphp
                    @if($pendingOrders > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingOrders }}</span>
                    @endif
                </a>

                <!-- Laporan -->
                <a href="{{ route('reports.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-gradient-to-r from-pink-500 to-purple-500 text-white shadow-lg scale-[1.02]' : 'text-gray-600 hover:bg-gray-50 hover:scale-[1.01]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">Laporan</span>
                </a>
            </nav>

            <!-- Logout -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t bg-white">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 w-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8 min-h-screen">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r-xl shadow-sm" role="alert">
                    <p class="font-medium">✅ Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm" role="alert">
                    <p class="font-medium">❌ Error!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>