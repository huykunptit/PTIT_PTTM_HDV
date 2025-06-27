<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Net Management System') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .sidebar {
            min-height: 100vh;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: #6c757d;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.25rem 0.5rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #495057;
        }
        
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 0.5rem;
        }
        
        .main-content {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        .top-bar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .user-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 1rem 0.5rem;
        }
        
        .sidebar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 1rem;
            text-align: center;
        }
        
        .sidebar-section {
            padding: 0.5rem 0;
        }
        
        .sidebar-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.5rem 1rem;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 bg-white sidebar">
                <!-- Sidebar Header -->
                <div class="sidebar-header">
                    <h4 class="mb-0">
                        <i class="fas fa-gamepad me-2"></i>
                        Net Management
                    </h4>
                    <small class="opacity-75">Gaming Center</small>
                </div>

                <!-- User Info -->
                @if(session('user'))
                <div class="user-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ session('user')['full_name'] ?? session('user')['username'] }}</h6>
                            <small class="opacity-75">
                                @if(session('user')['role_id'] === 1)
                                    Administrator
                                @else
                                    Customer
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Navigation Menu -->
                <nav class="mt-3">
                    @if(session('user'))
                        @if(session('user')['role_id'] === 1)
                            <!-- Admin Menu -->
                            <div class="sidebar-section">
                                <h6 class="sidebar-section-title">Administration</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i>
                                            Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                            <i class="fas fa-users"></i>
                                            Users Management
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                            <i class="fas fa-list"></i>
                                            Category Management
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                                            <i class="fas fa-box"></i>
                                            Products Management
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}" href="{{ route('admin.admin.promotions.index') }}">
                                            <i class="fas fa-gift"></i>
                                            Promotion Manager
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.areas.*') ? 'active' : '' }}" href="{{ route('admin.admin.areas.index') }}">
                                            <i class="fas fa-map-marked-alt"></i>
                                            Manage Areas
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="sidebar-section">
                                <h6 class="sidebar-section-title">Business</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}" href="{{ route('admin.products') }}">
                                            <i class="fas fa-box"></i>
                                            Products
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}" href="{{ route('admin.orders') }}">
                                            <i class="fas fa-shopping-cart"></i>
                                            Orders
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.promotions') ? 'active' : '' }}" href="{{ route('admin.promotions') }}">
                                            <i class="fas fa-percentage"></i>
                                            Promotions
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="sidebar-section">
                                <h6 class="sidebar-section-title">System</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.machines.*') ? 'active' : '' }}" href="{{ route('admin.admin.machines.index') }}">
                                            <i class="fas fa-desktop"></i>
                                            Manage Machines
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.sessions') ? 'active' : '' }}" href="{{ route('admin.sessions') }}">
                                            <i class="fas fa-clock"></i>
                                            Game Sessions
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                                            <i class="fas fa-chart-bar"></i>
                                            Reports
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="sidebar-section">
                                <h6 class="sidebar-section-title">Payment Management</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.payment.*') ? 'active' : '' }}" href="{{ route('admin.payment.index') }}">
                                            <i class="fas fa-credit-card"></i>
                                            Payment Management
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.payment.deposit') ? 'active' : '' }}" href="{{ route('admin.payment.index') }}#deposit">
                                            <i class="fas fa-plus-circle"></i>
                                            Deposit for Users
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.payment.withdraw') ? 'active' : '' }}" href="{{ route('admin.payment.index') }}#withdraw">
                                            <i class="fas fa-minus-circle"></i>
                                            Withdraw from Users
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.payment.bulk-deposit') ? 'active' : '' }}" href="{{ route('admin.payment.index') }}#bulk">
                                            <i class="fas fa-users-cog"></i>
                                            Bulk Deposit
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        @else
                            <!-- Customer Menu -->
                            <div class="sidebar-section">
                                <h6 class="sidebar-section-title">Gaming</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i>
                                            Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('shop.index') ? 'active' : '' }}" href="{{ route('shop.index') }}">
                                            <i class="fas fa-home"></i>
                                            Home
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="sidebar-section">
                                <h6 class="sidebar-section-title">Shop</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('shop.products') ? 'active' : '' }}" href="{{ route('shop.products') }}">
                                            <i class="fas fa-shopping-bag"></i>
                                            Products
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('shop.promotions') ? 'active' : '' }}" href="{{ route('shop.promotions') }}">
                                            <i class="fas fa-percentage"></i>
                                            Promotions
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('shop.cart') ? 'active' : '' }}" href="{{ route('shop.cart') }}">
                                            <i class="fas fa-shopping-cart"></i>
                                            Cart
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('shop.orders') ? 'active' : '' }}" href="{{ route('shop.orders') }}">
                                            <i class="fas fa-list-alt"></i>
                                            My Orders
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="sidebar-section">
                                <h6 class="sidebar-section-title">Account</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('shop.profile') ? 'active' : '' }}" href="{{ route('shop.profile') }}">
                                            <i class="fas fa-user"></i>
                                            Profile
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('wallet') ? 'active' : '' }}" href="{{ route('wallet') }}">
                                            <i class="fas fa-wallet"></i>
                                            Wallet
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="sidebar-section">
                                <h6 class="sidebar-section-title">Payment</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('payment.index') ? 'active' : '' }}" href="{{ route('payment.index') }}">
                                            <i class="fas fa-credit-card"></i>
                                            Online Payment
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('payment.history') ? 'active' : '' }}" href="{{ route('payment.history') }}">
                                            <i class="fas fa-history"></i>
                                            Payment History
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('wallet') ? 'active' : '' }}" href="{{ route('wallet') }}">
                                            <i class="fas fa-exchange-alt"></i>
                                            Wallet Transactions
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endif

                        <!-- Logout Section -->
                        <div class="sidebar-section mt-auto">
                            <hr class="my-3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link text-danger" href="{{ route('logout.custom') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout.custom') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <!-- Guest Menu -->
                        <div class="sidebar-section">
                            <h6 class="sidebar-section-title">Authentication</h6>
                            <ul class="nav flex-column">
                                @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Login
                                    </a>
                                </li>
                                @endif

                                @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i>
                                        Register
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0 main-content">
                <!-- Top Bar -->
                <div class="top-bar py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        </div>
                        <div class="d-flex align-items-center">
                            @if(session('user'))
                                <!-- Wallet Quick Info for Users -->
                                @if(session('user')['role_id'] !== 1)
                                <div class="me-3">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" id="walletDropdown">
                                            <i class="fas fa-wallet me-1"></i>
                                            <span id="walletBalance">Loading...</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 250px;">
                                            <li class="dropdown-header">
                                                <i class="fas fa-wallet me-2"></i>Wallet Quick Actions
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('payment.index') }}">
                                                    <i class="fas fa-plus-circle me-2 text-success"></i>
                                                    Deposit Money
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('wallet') }}">
                                                    <i class="fas fa-exchange-alt me-2 text-info"></i>
                                                    View Wallet
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('payment.history') }}">
                                                    <i class="fas fa-history me-2 text-warning"></i>
                                                    Transaction History
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @endif

                                <!-- Admin Quick Actions -->
                                @if(session('user')['role_id'] === 1)
                                <div class="me-3">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-cog me-1"></i>
                                            Quick Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li class="dropdown-header">
                                                <i class="fas fa-tools me-2"></i>Admin Actions
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.payment.index') }}">
                                                    <i class="fas fa-credit-card me-2 text-primary"></i>
                                                    Payment Management
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                                    <i class="fas fa-users me-2 text-success"></i>
                                                    User Management
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.products.index') }}">
                                                    <i class="fas fa-box me-2 text-warning"></i>
                                                    Product Management
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @endif

                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-bell me-1"></i>
                                        Notifications
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">No new notifications</a></li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load wallet balance for users
            @if(session('user') && session('user')['role_id'] !== 1)
            loadWalletBalance();
            
            // Auto refresh wallet balance every 30 seconds
            setInterval(loadWalletBalance, 30000);
            @endif

            // Add active class to current nav item
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Handle hash links for admin payment sections
            const hash = window.location.hash;
            if (hash) {
                const targetElement = document.querySelector(hash);
                if (targetElement) {
                    setTimeout(() => {
                        targetElement.scrollIntoView({ behavior: 'smooth' });
                    }, 500);
                }
            }
        });

        @if(session('user') && session('user')['role_id'] !== 1)
        function loadWalletBalance() {
            const balanceElement = document.getElementById('walletBalance');
            if (!balanceElement) return;

            fetch('{{ route("wallet.balance") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.balance !== undefined) {
                    const balance = new Intl.NumberFormat('vi-VN').format(data.balance);
                    balanceElement.textContent = balance + ' VNĐ';
                    
                    // Update button color based on balance
                    const walletButton = document.getElementById('walletDropdown');
                    if (data.balance > 0) {
                        walletButton.className = 'btn btn-outline-success dropdown-toggle';
                    } else {
                        walletButton.className = 'btn btn-outline-warning dropdown-toggle';
                    }
                } else {
                    balanceElement.textContent = 'Error';
                }
            })
            .catch(error => {
                console.error('Error loading wallet balance:', error);
                balanceElement.textContent = 'Error';
            });
        }
        @endif

        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add tooltip functionality
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add confirmation for logout
        document.getElementById('logout-form')?.addEventListener('submit', function(e) {
            if (!confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
