@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt me-2"></i>Welcome, {{ session('user')['full_name'] ?? session('user')['username'] }}!
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('payment.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-credit-card me-1"></i>Deposit Money
            </a>
            <a href="{{ route('wallet') }}" class="btn btn-success btn-sm">
                <i class="fas fa-wallet me-1"></i>View Wallet
            </a>
        </div>
    </div>

    <!-- User Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Wallet Balance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="walletBalance">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalOrders">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Transactions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalTransactions">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Active Promotions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activePromotions">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gift fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('payment.index') }}" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-credit-card fa-2x mb-2"></i>
                                <span>Deposit Money</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('shop.products') }}" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-shopping-bag fa-2x mb-2"></i>
                                <span>Shop Products</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('shop.orders') }}" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-list-alt fa-2x mb-2"></i>
                                <span>My Orders</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('payment.history') }}" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <span>Payment History</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Transactions -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Recent Transactions
                    </h6>
                    <a href="{{ route('payment.history') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="recentTransactionsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Loading...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Recent Orders
                    </h6>
                    <a href="{{ route('shop.orders') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div id="recentOrdersList">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard statistics
    loadDashboardStats();
    loadRecentTransactions();
    loadRecentOrders();

    // Auto refresh every 60 seconds
    setInterval(loadDashboardStats, 60000);
    setInterval(loadRecentTransactions, 60000);
    setInterval(loadRecentOrders, 60000);
});

function loadDashboardStats() {
    // Load wallet balance
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
            document.getElementById('walletBalance').textContent = balance + ' VNĐ';
        } else {
            document.getElementById('walletBalance').textContent = 'Error';
        }
    })
    .catch(error => {
        console.error('Error loading wallet balance:', error);
        document.getElementById('walletBalance').textContent = 'Error';
    });

    // Load other stats (placeholder for now)
    document.getElementById('totalOrders').textContent = '0';
    document.getElementById('totalTransactions').textContent = '0';
    document.getElementById('activePromotions').textContent = '0';
}

function loadRecentTransactions() {
    fetch('{{ route("wallet.transactions.filter") }}?limit=5', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.querySelector('#recentTransactionsTable tbody');
        tbody.innerHTML = '';

        if (data.transactions && data.transactions.length > 0) {
            data.transactions.forEach(transaction => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <span class="badge ${transaction.type === 'deposit' ? 'bg-success' : 'bg-danger'}">
                            ${transaction.type === 'deposit' ? 'Deposit' : 'Payment'}
                        </span>
                    </td>
                    <td class="fw-bold ${transaction.amount > 0 ? 'text-success' : 'text-danger'}">
                        ${new Intl.NumberFormat('vi-VN').format(transaction.amount || 0)} VNĐ
                    </td>
                    <td>${transaction.description || transaction.order_info || 'N/A'}</td>
                    <td>${transaction.created_at ? new Date(transaction.created_at).toLocaleString('vi-VN') : 'N/A'}</td>
                    <td>
                        <span class="badge ${transaction.status === 'success' ? 'bg-success' : 'bg-warning'}">
                            ${transaction.status === 'success' ? 'Success' : 'Pending'}
                        </span>
                    </td>
                `;
                tbody.appendChild(row);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">No recent transactions</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error loading recent transactions:', error);
        document.querySelector('#recentTransactionsTable tbody').innerHTML = 
            '<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>';
    });
}

function loadRecentOrders() {
    // Placeholder for orders loading
    const ordersList = document.getElementById('recentOrdersList');
    ordersList.innerHTML = `
        <div class="text-center text-muted">
            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
            <p>No recent orders</p>
            <a href="{{ route('shop.products') }}" class="btn btn-sm btn-primary">
                Start Shopping
            </a>
        </div>
    `;
}
</script>
@endsection 