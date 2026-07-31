<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <script>
      if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js');
        }
    </script>

    @php
        $shopSetting = \Illuminate\Support\Facades\DB::table('shop_settings')->first();
        $shopName = $shopSetting->shop_name ?? 'NS POS';
        $shopLogo = $shopSetting->logo ?? null;

        $lowStockCount = \App\Models\Product::whereColumn('qty', '<=', 'alert_qty')->count();
        
        $upcomingCheques = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('cheques')) {
            $column = \Illuminate\Support\Facades\Schema::hasColumn('cheques', 'due_date') ? 'due_date' : 'cheque_date';
            $upcomingCheques = \Illuminate\Support\Facades\DB::table('cheques')
                ->where('status', 'pending')
                ->whereBetween($column, [now()->toDateString(), now()->addDays(3)->toDateString()])
                ->count();
        }
        
        $totalNotifications = $lowStockCount + $upcomingCheques;
    @endphp

    <title>@yield('title', 'POS') - {{ $shopName }}</title>
    
    @if(file_exists(public_path('favicon.ico')))
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.svg') }}" type="image/x-icon">
    @endif
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    
    <script>
        function setTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            if (document.body) {
                document.body.setAttribute('data-bs-theme', theme);
                document.body.classList.remove('dark', 'light');
                document.body.classList.add(theme);
            }
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
            }
            localStorage.setItem('theme', theme);
            localStorage.setItem('mazer-theme', theme);
            
            const iconSun = document.getElementById('theme-icon-sun');
            const iconMoon = document.getElementById('theme-icon-moon');
            if (iconSun && iconMoon) {
                if (theme === 'dark') {
                    iconSun.classList.remove('d-none');
                    iconMoon.classList.add('d-none');
                } else {
                    iconSun.classList.add('d-none');
                    iconMoon.classList.remove('d-none');
                }
            }
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || localStorage.getItem('theme') || 'light';
            const newTheme = (currentTheme === 'dark') ? 'light' : 'dark';
            setTheme(newTheme);
        }

        window.setTheme = setTheme;
        window.toggleTheme = toggleTheme;

        let initTheme = localStorage.getItem('theme') || 'light';
        setTheme(initTheme);

        document.addEventListener("DOMContentLoaded", function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);
        });
    </script>

    <style>
        body { font-family: 'Nunito', 'Inter', sans-serif; overflow-x: hidden; }
        
        /* Empire POS Custom Classes - Guaranteed visibility in Light & Dark Mode */
        .bg-primary-custom {
            background-color: #007CEF !important;
            color: #ffffff !important;
        }
        .text-primary-custom {
            color: #007CEF !important;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            color: #ffffff !important;
            border: 1px solid #334155 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-weight: 700 !important;
            transition: all 0.25s ease !important;
            text-decoration: none !important;
            border-radius: 0.5rem !important;
        }
        [data-bs-theme="dark"] .btn-primary-custom {
            background: linear-gradient(135deg, #2A2E39 0%, #161920 100%) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4) !important;
        }
        .btn-primary-custom:hover,
        [data-bs-theme="dark"] .btn-primary-custom:hover {
            background: linear-gradient(135deg, #383f4f 0%, #222733 100%) !important;
            border-color: rgba(255, 255, 255, 0.45) !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
        }
        .btn-outline-primary-custom,
        [data-bs-theme="dark"] .btn-outline-primary-custom {
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: #e2e8f0 !important;
            background-color: transparent !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-weight: 700 !important;
            transition: all 0.25s ease !important;
            text-decoration: none !important;
            border-radius: 0.5rem !important;
        }
        .btn-outline-primary-custom:hover,
        [data-bs-theme="dark"] .btn-outline-primary-custom:hover {
            background-color: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(255, 255, 255, 0.45) !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }
        
        /* ==========================================================================
           PREMIUM DARK MODE BUTTON POLISH - CRISP SHAPE, NO FLOATING TEXT
           ========================================================================== */
        [data-bs-theme="dark"] .btn-light,
        [data-bs-theme="dark"] .btn-outline-secondary,
        [data-bs-theme="dark"] .btn-outline-light {
            background-color: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.18) !important;
            color: #F8FAFC !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3) !important;
        }
        [data-bs-theme="dark"] .btn-light:hover,
        [data-bs-theme="dark"] .btn-outline-secondary:hover,
        [data-bs-theme="dark"] .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: #FFFFFF !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
        }
        [data-bs-theme="dark"] .btn {
            opacity: 1 !important;
            font-weight: 600 !important;
        }
        [data-bs-theme="dark"] .table a.btn,
        [data-bs-theme="dark"] .table button.btn {
            color: #ffffff !important;
            opacity: 1 !important;
        }

        /* ==========================================================================
           PREMIUM OBSIDIAN DARK MODE PALETTE (Ultra-Deep Carbon & Graphite Luxury)
           Zero Bluish/Purple Cast - Apple Pro / OLED Black Inspired
           ========================================================================== */
        [data-bs-theme="dark"] body,
        [data-bs-theme="dark"] #main,
        [data-bs-theme="dark"] .sidebar-wrapper,
        [data-bs-theme="dark"] .pos-wrapper,
        [data-bs-theme="dark"] .page-heading,
        [data-bs-theme="dark"] .bg-body {
            background-color: #090A0E !important;
            color: #F8FAFC !important;
        }

        /* Elevated Premium Graphite Surfaces */
        [data-bs-theme="dark"] .card,
        [data-bs-theme="dark"] .product-card,
        [data-bs-theme="dark"] .cart-area,
        [data-bs-theme="dark"] .header-top,
        [data-bs-theme="dark"] .dropdown-menu,
        [data-bs-theme="dark"] .modal-content,
        [data-bs-theme="dark"] .stat-card,
        [data-bs-theme="dark"] .table-light th {
            background-color: #111318 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #F8FAFC !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
        }

        /* Interactive Hover & Active Surface for Cards & Tables */
        .card {
            transition: transform 0.25s ease, box-shadow 0.25s ease !important;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }
        [data-bs-theme="dark"] .card:hover,
        [data-bs-theme="dark"] .product-card:hover {
            background-color: #171A21 !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.7) !important;
        }
        [data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: #171A21 !important;
            color: #F8FAFC !important;
        }

        /* Sleek Obsidian Form Inputs, Search Bars & Selects */
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select,
        [data-bs-theme="dark"] input,
        [data-bs-theme="dark"] textarea,
        [data-bs-theme="dark"] .input-group-text {
            background-color: #161920 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #F8FAFC !important;
        }
        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus,
        [data-bs-theme="dark"] input:focus,
        [data-bs-theme="dark"] textarea:focus {
            background-color: #1D212A !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
            color: #F8FAFC !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.15) !important;
        }

        /* Typography & Dividers in Premium Dark Mode */
        [data-bs-theme="dark"] .text-muted {
            color: #94A3B8 !important;
        }
        [data-bs-theme="dark"] .border-bottom,
        [data-bs-theme="dark"] .border-top,
        [data-bs-theme="dark"] .border {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        [data-bs-theme="dark"] .table {
            color: #F8FAFC !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        [data-bs-theme="dark"] .table td,
        [data-bs-theme="dark"] .table th {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        [data-bs-theme="dark"] .list-group-item {
            background-color: transparent !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #F8FAFC !important;
        }

        /* ==========================================================================
           EMPIRE HARDWARE POS - ABSOLUTE GLOBAL OVERRIDES FOR LIGHT & DARK MODES
           Guarantees Pill Buttons, Search Inputs, and Table Headers Never Reset
           ========================================================================== */
        /* 1. Universal Rounded Pills across ALL Themes */
        .rounded-pill,
        [data-bs-theme="dark"] .rounded-pill,
        [data-bs-theme="light"] .rounded-pill,
        [data-bs-theme="dark"] button.rounded-pill,
        [data-bs-theme="dark"] input.rounded-pill,
        [data-bs-theme="dark"] select.rounded-pill,
        [data-bs-theme="light"] button.rounded-pill,
        [data-bs-theme="light"] input.rounded-pill,
        [data-bs-theme="light"] select.rounded-pill {
            border-radius: 50rem !important;
        }

        /* 2. Custom Filter Pills (Cash, Expense, Cheque, Supplier, All) */
        .cash-filter-pill,
        .expense-filter-pill,
        .cheque-filter-pill,
        .supplier-filter-pill,
        .filter-pill,
        [data-bs-theme="dark"] .cash-filter-pill,
        [data-bs-theme="dark"] .expense-filter-pill,
        [data-bs-theme="dark"] .cheque-filter-pill,
        [data-bs-theme="dark"] .supplier-filter-pill,
        [data-bs-theme="dark"] .filter-pill,
        [data-bs-theme="light"] .cash-filter-pill,
        [data-bs-theme="light"] .expense-filter-pill,
        [data-bs-theme="light"] .cheque-filter-pill,
        [data-bs-theme="light"] .supplier-filter-pill,
        [data-bs-theme="light"] .filter-pill {
            border-radius: 50rem !important;
            padding: 0.4rem 1.1rem !important;
            font-weight: 600 !important;
            font-size: 0.82rem !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.4rem !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
        }

        /* 3. Search Input Custom - Absolute Icon Padding & Pill Radius in Both Modes */
        .search-input-custom,
        [data-bs-theme="dark"] .search-input-custom,
        [data-bs-theme="light"] .search-input-custom,
        [data-bs-theme="dark"] input.search-input-custom,
        [data-bs-theme="light"] input.search-input-custom {
            border-radius: 50rem !important;
            padding-left: 2.75rem !important;
            padding-right: 2rem !important;
            height: 42px !important;
            line-height: 1.5 !important;
        }

        /* 4. Sleek Table Headers for Both Modes */
        .table-light th,
        thead.table-light th,
        .table thead.table-light th {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            font-weight: 700 !important;
            border-bottom: 2px solid #e2e8f0 !important;
            font-size: 0.82rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
        }

        [data-bs-theme="dark"] .table-light th,
        [data-bs-theme="dark"] thead.table-light th,
        [data-bs-theme="dark"] .table thead.table-light th {
            background-color: #1a1e26 !important;
            color: #94a3b8 !important;
            font-weight: 700 !important;
            border-bottom: 2px solid rgba(255, 255, 255, 0.12) !important;
            font-size: 0.82rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
        }

        .header-top {
            background: #fff;
            padding: 0.8rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            border-radius: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        [data-bs-theme="dark"] .header-top {
            background: #111318 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }

        .sidebar-wrapper .sidebar-header .logo img {
            height: 100px !important;  
            width: auto !important;    
            max-height: 150px !important; 
            object-fit: contain !important;
        }

        .sidebar-header .logo {
            display: flex;
            justify-content: center; 
            align-items: center;
            width: 100%;
        }

        .sidebar-header .logo a {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            text-decoration: none;
        }
        
        .pc-show-btn { display: none !important; }

        @media (min-width: 1200px) {
            .sidebar-wrapper {
                transition: all 0.4s ease-in-out !important; 
                width: 300px;
                overflow-x: hidden !important;
            }
            
            #main {
                transition: margin-left 0.4s ease-in-out !important;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                margin-left: 300px;
            }

            .page-content {
                flex-grow: 1; 
            }

            footer {
                margin-top: auto !important;
            }
            
            body.sidebar-hidden .sidebar-wrapper {
                width: 80px !important; 
                left: 0 !important;
            }
            body.sidebar-hidden .sidebar-wrapper:hover {
                width: 300px !important;
            }
            body.sidebar-hidden #main {
                margin-left: 80px !important; 
            }

            body.sidebar-hidden .sidebar-wrapper .sidebar-link {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            body.sidebar-hidden .sidebar-wrapper .sidebar-link i {
                margin-right: 0 !important;
                font-size: 1.5rem !important;
            }
            body.sidebar-hidden .sidebar-wrapper .sidebar-link span,
            body.sidebar-hidden .sidebar-wrapper .sidebar-title,
            body.sidebar-hidden .sidebar-wrapper .logo .logo-text {
                display: none !important;
            }
            body.sidebar-hidden .sidebar-wrapper .logo img {
                height: 50px !important;
                width: auto !important;
            }
            
            body.sidebar-hidden .sidebar-wrapper:hover .sidebar-link {
                justify-content: flex-start !important;
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
            body.sidebar-hidden .sidebar-wrapper:hover .sidebar-link i {
                margin-right: 1rem !important;
                font-size: 1.1rem !important;
            }
            body.sidebar-hidden .sidebar-wrapper:hover .sidebar-link span,
            body.sidebar-hidden .sidebar-wrapper:hover .sidebar-title,
            body.sidebar-hidden .sidebar-wrapper:hover .logo .logo-text {
                display: block !important;
            }
            body.sidebar-hidden .sidebar-wrapper:hover .logo img {
                height: 100px !important;
            }
            
            body.sidebar-hidden .sidebar-wrapper .has-sub .submenu {
                display: none !important;
            }
            
            body.sidebar-hidden .sidebar-wrapper:hover .has-sub.active .submenu {
                display: block !important;
            }

            body.sidebar-hidden .pc-show-btn { display: block !important; }

            .pc-hide-btn {
                position: absolute;
                top: 25px;
                right: 15px;
                cursor: pointer;
                z-index: 10;
            }
            body.sidebar-hidden .sidebar-wrapper .pc-hide-btn {
                display: none !important;
            }
            body.sidebar-hidden .sidebar-wrapper:hover .pc-hide-btn {
                display: block !important;
            }
        }

        @media (max-width: 991px) {
            .sidebar-menu {
                padding-bottom: 120px !important; 
            }
            .sidebar-wrapper {
                height: 100vh;
                overflow-y: auto;
            }
        }

        .notification-dropdown {
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
        }
        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f4f4f4;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
        }
        [data-bs-theme="dark"] .notification-item {
            border-bottom: 1px solid #2d2d3f;
        }
    </style>
</head>

<body class="sidebar-hidden">
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="logo w-100 text-center mt-3">
                            <a href="{{ url('/home') }}" class="fs-5 fw-bold text-decoration-none">
                                @if(file_exists(public_path('favicon.ico')))
                                    <img src="{{ asset('favicon.ico') }}?v={{ time() }}" alt="" class="logo-img mx-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <i class="bi bi-cpu me-2 fs-1 mb-2 text-primary" style="display:none;"></i>
                                @elseif($shopLogo)
                                    <img src="{{ asset('storage/' . $shopLogo) }}" alt="" class="logo-img mx-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <i class="bi bi-cpu me-2 fs-1 mb-2 text-primary" style="display:none;"></i>
                                @else
                                    <i class="bi bi-cpu me-2 fs-1 d-block mb-2 text-primary"></i>
                                @endif
                                <span class="logo-text mt-2 d-block" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $shopName }}</span>
                            </a>
                        </div>
                        
                        <div class="sidebar-toggler x position-absolute top-0 end-0 mt-3 me-3 d-xl-none">
                            <a href="#" class="sidebar-hide d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                        
                        <a href="#" class="pc-hide-btn d-none d-xl-block text-secondary" onclick="toggleSidebarPC(event)">
                            <i class="bi bi-justify fs-3"></i>
                        </a>
                    </div>
                </div>
                
                <div class="sidebar-menu">
                    <ul class="menu">
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <li class="sidebar-item {{ request()->is('home*') || request()->is('dashboard*') ? 'active' : '' }}">
                            <a href="{{ route('home') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        @endif

                        <li class="sidebar-item {{ request()->is('pos*') ? 'active' : '' }}">
                            <a href="{{ route('pos.index') }}" class='sidebar-link'>
                                <i class="bi bi-cart-fill"></i>
                                <span>POS Terminal</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('cashier/daily-sales*') ? 'active' : '' }}">
                            <a href="{{ route('cashier.daily_sales') }}" class='sidebar-link'>
                                <i class="bi bi-clipboard2-data-fill"></i>
                                <span>Daily Sales Report</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('returns*') ? 'active' : '' }}">
                            <a href="{{ route('returns.index') }}" class='sidebar-link'>
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span>Returns & Warranty</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('quotations*') ? 'active' : '' }}">
                            <a href="{{ route('quotations.index') }}" class='sidebar-link'>
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <span>Quotations & Loyalty Bills</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('products*') || request()->is('categories*') ? 'active' : '' }}">
                            <a href="{{ route('products.index') }}" class='sidebar-link'>
                                <i class="bi bi-box-seam-fill"></i>
                                <span>Manage Items</span>
                            </a>
                        </li>

                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <li class="sidebar-item {{ request()->is('suppliers*') ? 'active' : '' }}">
                            <a href="{{ route('suppliers.index') }}" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>Suppliers</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('cheques*') ? 'active' : '' }}">
                            <a href="{{ route('cheques.index') }}" class='sidebar-link'>
                                <i class="bi bi-card-checklist"></i>
                                <span>Cheque Management</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('finance*') ? 'active' : '' }}">
                            <a href="{{ route('finance.index') }}" class='sidebar-link'>
                                <i class="bi bi-wallet2"></i>
                                <span>Bank & Expenses</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('cashbook*') ? 'active' : '' }}">
                            <a href="{{ route('cashbook.index') }}" class='sidebar-link'>
                                <i class="bi bi-cash-stack"></i>
                                <span>Cash in Hand</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('reports*') ? 'active' : '' }}">
                            <a href="{{ route('reports.index') }}" class='sidebar-link'>
                                <i class="bi bi-graph-up-arrow"></i>
                                <span>Business Reports</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('settings*') || request()->is('users*') || request()->is('branches*') || request()->is('monitoring*') || request()->is('system-update*') ? 'active' : '' }}">
                            <a href="{{ route('settings.index') }}" class='sidebar-link'>
                                <i class="bi bi-gear-fill"></i>
                                <span>Settings & Setup</span>
                            </a>
                        </li>
                        @endif

                        <li class="sidebar-item mt-2">
                            <a class="sidebar-link bg-danger text-white" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                                <i class="bi bi-box-arrow-left text-white"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div id="main">
            <header class="header-top">
                <div class="d-flex align-items-center">
                    <a href="#" class="burger-btn d-block d-xl-none me-3">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                    
                    <a href="#" class="pc-show-btn text-body me-3" onclick="toggleSidebarPC(event)">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                    
                    <h4 class="m-0 d-none d-md-block">@yield('header')</h4>
                </div>

                <div class="d-flex align-items-center gap-2 gap-md-4">
                    
                    <div class="dropdown">
                        <a href="#" class="text-body position-relative" data-bs-toggle="dropdown">
                            <i class="bi bi-bell fs-4"></i>
                            @if($totalNotifications > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    {{ $totalNotifications }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm notification-dropdown">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            
                            @if($lowStockCount > 0)
                            <li>
                                <a class="dropdown-item notification-item" href="{{ route('products.index') }}">
                                    <i class="bi bi-exclamation-triangle text-warning"></i>
                                    <div>
                                        <div class="fw-bold">Low Stock Warning</div>
                                        <small>{{ $lowStockCount }} items are running low.</small>
                                    </div>
                                </a>
                            </li>
                            @endif

                            @if($upcomingCheques > 0)
                            <li>
                                <a class="dropdown-item notification-item" href="{{ route('cheques.index') }}">
                                    <i class="bi bi-card-checklist text-info"></i>
                                    <div>
                                        <div class="fw-bold">Cheque Alert</div>
                                        <small>{{ $upcomingCheques }} cheques due soon.</small>
                                    </div>
                                </a>
                            </li>
                            @endif

                            @if($totalNotifications == 0)
                            <li><div class="dropdown-item text-center text-muted py-3">No new alerts</div></li>
                            @endif
                        </ul>
                    </div>

                    <!-- Empire POS Theme Toggle Button -->
                    <button id="theme-toggle" onclick="toggleTheme()" class="btn btn-outline-secondary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 40px; height: 40px;" title="Toggle Light/Dark Theme">
                        <svg id="theme-icon-sun" class="d-none" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg id="theme-icon-moon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    @auth
                    <div class="dropdown">
                        <a href="#" data-bs-toggle="dropdown" class="d-flex align-items-center text-decoration-none text-body">
                            <div class="user-menu d-flex">
                                <div class="user-name text-end me-3 d-none d-md-block">
                                    <h6 class="mb-0 text-gray-600">{{ Auth::user()->name }}</h6>
                                    <p class="mb-0 text-sm text-gray-600">{{ ucfirst(Auth::user()->role) }}</p>
                                </div>
                                <div class="user-img d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary">
                                        <span class="avatar-content text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><h6 class="dropdown-header">Hello, {{ Auth::user()->name }}!</h6></li>
                            @if(auth()->user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bi bi-gear me-2"></i> Settings</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form-dropdown').submit();">
                                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                                </a>
                                <form id="logout-form-dropdown" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </header>

            <div class="page-content">
                @yield('content')
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted mt-5">
                    <div class="float-start">
                        <p>{{ date('Y') }} &copy; {{ $shopName }}</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <script>
        function toggleSidebarPC(e) {
            e.preventDefault();
            document.body.classList.toggle('sidebar-hidden');
        }
    </script>

    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/static/js/components/sidebar.js') }}"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarLinks = document.querySelectorAll('.sidebar-item.has-sub > .sidebar-link');
        
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const parentItem = this.parentElement;
                const submenu = parentItem.querySelector('.submenu');
                
                if (submenu) {
                    if (submenu.style.display === 'block' || parentItem.classList.contains('active')) {
                        submenu.style.display = 'none';
                        parentItem.classList.remove('active');
                    } 
                    else {
                        submenu.style.display = 'block';
                        parentItem.classList.add('active');
                    }
                }
            });
        });

        // Automatically hide success and error alerts after 3.5 seconds across the entire app
        const globalAlerts = document.querySelectorAll('.alert-dismissible');
        if (globalAlerts.length > 0) {
            setTimeout(function() {
                globalAlerts.forEach(function(alertEl) {
                    if (alertEl && alertEl.style) {
                        alertEl.style.transition = 'opacity 0.6s ease, max-height 0.6s ease, margin 0.6s ease, padding 0.6s ease';
                        alertEl.style.opacity = '0';
                        alertEl.style.maxHeight = '0px';
                        alertEl.style.margin = '0px';
                        alertEl.style.paddingTop = '0px';
                        alertEl.style.paddingBottom = '0px';
                        alertEl.style.overflow = 'hidden';
                        setTimeout(() => {
                            if (alertEl && alertEl.remove) alertEl.remove();
                        }, 600);
                    }
                });
            }, 3500);
        }
    });
    </script>

    @yield('scripts')

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                let lat = position.coords.latitude;
                let lng = position.coords.longitude;

                let csrfToken = document.querySelector('meta[name="csrf-token"]');
                if(!csrfToken) return;

                fetch('{{ route('update.location') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng })
                })
                .catch(error => console.error('Error sending location:', error));
                
            }, function(error) {
                if(error.code == 1) {
                    console.warn("Location access denied.");
                }
            }, {
                enableHighAccuracy: true,
                timeout: 30000,
                maximumAge: 0
            });
        }
    });
    </script>

</body>
</html>
