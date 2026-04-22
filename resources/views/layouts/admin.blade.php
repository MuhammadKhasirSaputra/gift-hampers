<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gift & Hampers')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Figtree', sans-serif;
            background: #fff7f9;
            color: #1a1a2e;
        }

        /* ─── Sidebar ─── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 240px;
            background: #ffffff;
            border-right: 1px solid #fce7f3;
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        #sidebar.collapsed { width: 56px; }

        /* ─── Toggle button ─── */
        .sb-top {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 10px;
            border-bottom: 1px solid #fce7f3;
            flex-shrink: 0;
        }

        .toggle-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1px solid #fce7f3;
            background: transparent;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: background 0.12s;
        }
        .toggle-btn:hover { background: #fff0f6; }
        .toggle-btn svg {
            width: 16px; height: 16px;
            color: #db2777;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #sidebar.collapsed .toggle-btn svg { transform: rotate(180deg); }

        /* ─── Brand ─── */
        .brand {
            display: flex; align-items: center; gap: 8px;
            overflow: hidden;
        }
        .brand-icon {
            width: 28px; height: 28px;
            border-radius: 8px;
            background: #db2777;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-icon svg { width: 14px; height: 14px; }
        .brand-text {
            white-space: nowrap; overflow: hidden;
            transition: opacity 0.2s, width 0.25s;
        }
        .brand-name { font-size: 13px; font-weight: 600; color: #1a1a2e; }
        .brand-sub { font-size: 11px; color: #9ca3af; }
        #sidebar.collapsed .brand-text { opacity: 0; width: 0; }

        /* ─── User card ─── */
        .sb-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px;
            border-bottom: 1px solid #fce7f3;
            flex-shrink: 0; overflow: hidden;
        }
        .avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: #fce7f3;
            color: #db2777;
            font-size: 12px; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .user-info {
            white-space: nowrap; overflow: hidden;
            transition: opacity 0.2s, width 0.25s;
        }
        .user-name { font-size: 12px; font-weight: 600; color: #1a1a2e; }
        .user-role {
            font-size: 10px;
            background: #fce7f3; color: #db2777;
            padding: 1px 7px; border-radius: 20px;
            display: inline-block; margin-top: 2px;
            font-weight: 600; text-transform: capitalize;
        }
        #sidebar.collapsed .user-info { opacity: 0; width: 0; }

        /* ─── Nav ─── */
        .sb-nav { flex: 1; padding: 8px 6px; overflow-y: auto; overflow-x: hidden; }
        .sb-nav::-webkit-scrollbar { width: 3px; }
        .sb-nav::-webkit-scrollbar-thumb { background: #fbcfe8; border-radius: 10px; }

        .sec-label {
            font-size: 10px; font-weight: 700;
            color: #f9a8d4;
            text-transform: uppercase; letter-spacing: 0.1em;
            padding: 0 8px; margin: 10px 0 3px;
            white-space: nowrap; overflow: hidden;
            transition: opacity 0.2s;
        }
        #sidebar.collapsed .sec-label { opacity: 0; }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 8px;
            border-radius: 8px;
            font-size: 13px; font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            transition: background 0.12s, color 0.12s;
            white-space: nowrap;
            position: relative;
        }
        .nav-item:hover {
            background: #fff0f6;
            color: #db2777;
        }
        .nav-item.active {
            background: #fce7f3;
            color: #db2777;
        }
        .nav-item.active .ni-icon { color: #db2777; }

        .ni-icon { width: 16px; height: 16px; flex-shrink: 0; }
        .ni-label {
            flex: 1; overflow: hidden;
            transition: opacity 0.2s, width 0.25s;
        }
        #sidebar.collapsed .ni-label { opacity: 0; width: 0; }

        .ni-badge {
            font-size: 10px; font-weight: 700;
            background: #ef4444; color: white;
            padding: 1px 6px; border-radius: 20px;
            flex-shrink: 0;
            transition: opacity 0.2s;
        }
        #sidebar.collapsed .ni-badge { opacity: 0; }

        /* Tooltip saat collapsed */
        .nav-tooltip {
            display: none;
            position: absolute;
            left: calc(100% + 8px);
            top: 50%; transform: translateY(-50%);
            background: #1f2937; color: white;
            font-size: 12px; font-weight: 600;
            padding: 4px 10px; border-radius: 6px;
            white-space: nowrap; z-index: 999; pointer-events: none;
        }
        .nav-tooltip::before {
            content: '';
            position: absolute; right: 100%; top: 50%;
            transform: translateY(-50%);
            border: 4px solid transparent;
            border-right-color: #1f2937;
        }
        #sidebar.collapsed .nav-item:hover .nav-tooltip { display: block; }
        #sidebar.collapsed .logout:hover .nav-tooltip { display: block; }

        /* ─── Logout ─── */
        .sb-footer {
            padding: 6px;
            border-top: 1px solid #fce7f3;
            flex-shrink: 0;
        }
        .logout {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 8px; border-radius: 8px;
            font-size: 13px; font-weight: 500;
            color: #ef4444;
            cursor: pointer;
            transition: background 0.12s;
            white-space: nowrap; position: relative;
            border: none; background: transparent; width: 100%;
            text-align: left;
        }
        .logout:hover { background: #fef2f2; }
        .logout-icon { width: 16px; height: 16px; flex-shrink: 0; }
        .logout-label {
            overflow: hidden;
            transition: opacity 0.2s, width 0.25s;
        }
        #sidebar.collapsed .logout-label { opacity: 0; width: 0; }

        /* ─── Main content ─── */
        #mainContent {
            margin-left: 240px;
            min-height: 100vh;
            transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #mainContent.expanded { margin-left: 56px; }

        /* ─── Mobile overlay ─── */
        #overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 40;
        }

        /* ─── Mobile toggle button ─── */
        #mobileToggleBtn {
            display: none;
            position: fixed;
            top: 14px; left: 14px;
            z-index: 30;
            width: 38px; height: 38px;
            border-radius: 8px;
            background: #fff;
            border: 1px solid #fce7f3;
            align-items: center; justify-content: center;
            cursor: pointer;
        }
        #mobileToggleBtn svg { width: 16px; height: 16px; color: #db2777; }

        /* ─── Alerts ─── */
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .alert-icon { font-size: 18px; flex-shrink: 0; }
        .alert-title { font-weight: 700; font-size: 13px; }
        .alert-msg { font-size: 13px; margin-top: 1px; }

        /* ─── Mobile responsive ─── */
        @media (max-width: 1023px) {
            #sidebar {
                width: 240px !important;
                transform: translateX(-100%);
                transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }
            #sidebar.mobile-open { transform: translateX(0) !important; }
            #sidebar.collapsed .brand-text,
            #sidebar.collapsed .user-info,
            #sidebar.collapsed .ni-label,
            #sidebar.collapsed .ni-badge,
            #sidebar.collapsed .sec-label,
            #sidebar.collapsed .logout-label {
                opacity: 1 !important; width: auto !important; height: auto !important;
            }
            #mainContent { margin-left: 0 !important; }
            #mobileToggleBtn { display: flex; }
        }
    </style>
</head>
<body>

    <!-- Mobile Overlay -->
    <div id="overlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar">

        <!-- Top: toggle + brand -->
        <div class="sb-top">
            <button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()" title="Buka/tutup sidebar">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/>
                </svg>
            </button>
            <div class="brand">
                <div class="brand-icon">
                    <svg fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <div class="brand-name">Gift & Hampers</div>
                    <div class="brand-sub">Sistem Pemesanan</div>
                </div>
            </div>
        </div>

        <!-- User card -->
        <div class="sb-user">
            <div class="avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role }}</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sb-nav">
            <p class="sec-label">Menu utama</p>

            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="ni-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span class="ni-label">Dashboard</span>
                <span class="nav-tooltip">Dashboard</span>
            </a>

            <a href="{{ route('products.index') }}"
               class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <svg class="ni-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="ni-label">Produk</span>
                <span class="nav-tooltip">Produk</span>
            </a>

            <a href="{{ route('orders.index') }}"
               class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                @php $pending = \App\Models\Order::where('status', 'Pending')->count(); @endphp
                <svg class="ni-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="ni-label">Pemesanan</span>
                @if($pending > 0)
                    <span class="ni-badge">{{ $pending }}</span>
                @endif
                <span class="nav-tooltip">Pemesanan{{ $pending > 0 ? " ($pending)" : '' }}</span>
            </a>

            <p class="sec-label" style="margin-top: 12px;">Laporan</p>

            <a href="{{ route('reports.index') }}"
               class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg class="ni-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="ni-label">Laporan</span>
                <span class="nav-tooltip">Laporan</span>
            </a>
        </nav>

        <!-- Logout -->
        <div class="sb-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">
                    <svg class="logout-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="logout-label">Keluar dari sistem</span>
                    <span class="nav-tooltip">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile toggle button -->
    <button id="mobileToggleBtn" onclick="openMobileSidebar()">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Main Content -->
    <main id="mainContent">
        <div style="padding: 32px;">

            @if(session('success'))
                <div class="alert alert-success">
                    <span class="alert-icon">✅</span>
                    <div>
                        <p class="alert-title">Berhasil!</p>
                        <p class="alert-msg">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <span class="alert-icon">❌</span>
                    <div>
                        <p class="alert-title">Error!</p>
                        <p class="alert-msg">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        let isCollapsed = false;

        function toggleSidebar() {
            isCollapsed = !isCollapsed;
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            sidebar.classList.toggle('collapsed', isCollapsed);
            main.classList.toggle('expanded', isCollapsed);
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        function openMobileSidebar() {
            document.getElementById('sidebar').classList.add('mobile-open');
            document.getElementById('overlay').style.display = 'block';
        }

        function closeMobileSidebar() {
            document.getElementById('sidebar').classList.remove('mobile-open');
            document.getElementById('overlay').style.display = 'none';
        }

        function initLayout() {
            const isMobile = window.innerWidth < 1024;
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            const mobileBtn = document.getElementById('mobileToggleBtn');

            if (isMobile) {
                sidebar.classList.remove('collapsed');
                main.classList.remove('expanded');
                main.style.marginLeft = '0';
                mobileBtn.style.display = 'flex';
                closeMobileSidebar();
            } else {
                mobileBtn.style.display = 'none';
                document.getElementById('overlay').style.display = 'none';
                sidebar.classList.remove('mobile-open');

                // Restore saved state
                const saved = localStorage.getItem('sidebarCollapsed');
                if (saved === 'true') {
                    isCollapsed = true;
                    sidebar.classList.add('collapsed');
                    main.classList.add('expanded');
                } else {
                    isCollapsed = false;
                    sidebar.classList.remove('collapsed');
                    main.classList.remove('expanded');
                }
            }
        }

        window.addEventListener('resize', initLayout);
        document.addEventListener('DOMContentLoaded', initLayout);
    </script>

    @stack('scripts')
</body>
</html>