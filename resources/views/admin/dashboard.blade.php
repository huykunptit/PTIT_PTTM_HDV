@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.payment.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-credit-card me-1"></i>Payment Management
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-success btn-sm">
                <i class="fas fa-users me-1"></i>User Management
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalUsers">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Total System Balance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalBalance">
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
                                Today's Transactions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="todayTransactions">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                            <a href="{{ route('admin.payment.index') }}" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-credit-card fa-2x mb-2"></i>
                                <span>Payment Management</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <span>User Management</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-box fa-2x mb-2"></i>
                                <span>Product Management</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-gift fa-2x mb-2"></i>
                                <span>Promotion Management</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Recent Transactions
                    </h6>
                    <a href="{{ route('admin.payment.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="recentTransactionsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Loading...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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

    // Auto refresh every 60 seconds
    setInterval(loadDashboardStats, 60000);
    setInterval(loadRecentTransactions, 60000);
});

function loadDashboardStats() {
    // Load total users
    fetch('{{ route("admin.users.index") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.users) {
            document.getElementById('totalUsers').textContent = data.users.length;
        }
    })
    .catch(error => {
        console.error('Error loading users:', error);
        document.getElementById('totalUsers').textContent = 'Error';
    });

    // Load payment statistics
    fetch('{{ route("admin.payment.transactions") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.stats) {
            const stats = data.stats;
            document.getElementById('totalBalance').textContent = new Intl.NumberFormat('vi-VN').format(stats.total_balance || 0) + ' VNĐ';
            document.getElementById('totalTransactions').textContent = stats.total_transactions || 0;
            document.getElementById('todayTransactions').textContent = stats.today_transactions || 0;
        }
    })
    .catch(error => {
        console.error('Error loading payment stats:', error);
        document.getElementById('totalBalance').textContent = 'Error';
        document.getElementById('totalTransactions').textContent = 'Error';
        document.getElementById('todayTransactions').textContent = 'Error';
    });
}

function loadRecentTransactions() {
    fetch('{{ route("admin.payment.transactions") }}?limit=5', {
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
                    <td>${transaction.id || 'N/A'}</td>
                    <td>${transaction.user?.username || 'N/A'}</td>
                    <td>
                        <span class="badge ${transaction.type === 'deposit' ? 'bg-success' : 'bg-danger'}">
                            ${transaction.type === 'deposit' ? 'Deposit' : 'Withdraw'}
                        </span>
                    </td>
                    <td class="fw-bold ${transaction.amount > 0 ? 'text-success' : 'text-danger'}">
                        ${new Intl.NumberFormat('vi-VN').format(transaction.amount || 0)} VNĐ
                    </td>
                    <td>${transaction.description || 'N/A'}</td>
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
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">No recent transactions</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error loading recent transactions:', error);
        document.querySelector('#recentTransactionsTable tbody').innerHTML = 
            '<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>';
    });
}
</script>
@endsection 