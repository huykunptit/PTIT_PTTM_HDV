@extends('layouts.app')

@section('title', 'Quản lý thanh toán - Admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-credit-card mr-2"></i>Quản lý thanh toán
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.payment.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-download mr-1"></i>Xuất dữ liệu
            </a>
        </div>
    </div>

    @if(!is_array($users) || count($users) === 0)
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">
            <i class="fas fa-exclamation-triangle me-2"></i>Không thể tải danh sách người dùng
        </h4>
        <p>Hệ thống không thể kết nối đến API để lấy danh sách người dùng. Vui lòng kiểm tra:</p>
        <ul>
            <li>Kết nối mạng</li>
            <li>Token xác thực</li>
            <li>API endpoint</li>
        </ul>
        <hr>
        <p class="mb-0">
            <a href="{{ route('test.api') }}" class="btn btn-sm btn-outline-warning" target="_blank">
                <i class="fas fa-bug me-1"></i>Kiểm tra API Response
            </a>
        </p>
    </div>
    @endif

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số dư hệ thống
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_balance'] ?? 0) }} VNĐ
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
                                Tổng số người dùng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_users'] ?? 0) }}
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tổng giao dịch
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_transactions'] ?? 0) }}
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
                                Giao dịch hôm nay
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['today_transactions'] ?? 0) }}
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

    <div class="row">
        <!-- Nạp tiền cho user -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle mr-2"></i>Nạp tiền cho người dùng
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment.deposit') }}" method="POST" id="depositForm">
                        @csrf
                        <input type="hidden" name="type" value="deposit">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Chọn người dùng</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="">-- Chọn người dùng --</option>
                                @if(is_array($users) && count($users) > 0)
                                    @foreach($users as $user)
                                        <option value="{{ $user['id'] }}"  data-fullname="{{ $user['full_name'] ?? '' }}">
                                             - {{ $user['full_name'] ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Không có người dùng nào</option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Số tiền (VNĐ)</label>
                            <input type="number" class="form-control" id="amount" name="amount" min="1000" step="1000" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <input type="text" class="form-control" id="description" name="description" maxlength="255" >
                        </div>

                        <div class="mb-3">
                            <label for="admin_note" class="form-label">Ghi chú admin (tùy chọn)</label>
                            <textarea class="form-control" id="admin_note" name="admin_note" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus mr-2"></i>Nạp tiền
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
@endif
        <!-- Rút tiền từ user -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-minus-circle mr-2"></i>Rút tiền từ người dùng
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment.withdraw') }}" method="POST" id="withdrawForm">
                        @csrf
                        <input type="hidden" name="type" value="withdraw">
                        <div class="mb-3">
                            <label for="withdraw_user_id" class="form-label">Chọn người dùng</label>
                            <select class="form-select" id="withdraw_user_id" name="user_id" required>
                                <option value="">-- Chọn người dùng --</option>
                                @if(is_array($users) && count($users) > 0)
                                    @foreach($users as $user)
                                        <option value="{{ $user['id'] }}" data-username="" data-fullname="{{ $user['full_name'] ?? '' }}">
                                             - {{ $user['full_name'] ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Không có người dùng nào</option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="withdraw_amount" class="form-label">Số tiền (VNĐ)</label>
                            <input type="number" class="form-control" id="withdraw_amount" name="amount" min="1000" step="1000" required>
                        </div>

                        <div class="mb-3">
                            <label for="withdraw_description" class="form-label">Mô tả</label>
                            <input type="text" class="form-control" id="withdraw_description" name="description" maxlength="255" required>
                        </div>

                        <div class="mb-3">
                            <label for="withdraw_admin_note" class="form-label">Ghi chú admin (tùy chọn)</label>
                            <textarea class="form-control" id="withdraw_admin_note" name="admin_note" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-minus mr-2"></i>Rút tiền
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Nạp tiền hàng loạt -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-users-cog mr-2"></i>Nạp tiền hàng loạt
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment.bulk-deposit') }}" method="POST" id="bulkDepositForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Chọn người dùng</label>
                                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                        @if(is_array($users) && count($users) > 0)
                                            @foreach($users as $user)
                                                <div class="form-check">
                                                    <input class="form-check-input bulk-user-checkbox" type="checkbox" name="user_ids[]" value="{{ $user['id'] }}" id="user_{{ $user['id'] }}">
                                                    <label class="form-check-label" for="user_{{ $user['id'] }}">
                                                         - {{ $user['full_name'] ?? 'N/A' }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-muted">Không có người dùng nào</div>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">Chọn tất cả</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">Bỏ chọn tất cả</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bulk_amount" class="form-label">Số tiền cho mỗi người dùng (VNĐ)</label>
                                    <input type="number" class="form-control" id="bulk_amount" name="amount" min="1000" step="1000" required>
                                </div>

                                <div class="mb-3">
                                    <label for="bulk_description" class="form-label">Mô tả</label>
                                    <input type="text" class="form-control" id="bulk_description" name="description" maxlength="255" required>
                                </div>

                                <div class="mb-3">
                                    <label for="bulk_admin_note" class="form-label">Ghi chú admin (tùy chọn)</label>
                                    <textarea class="form-control" id="bulk_admin_note" name="admin_note" rows="2"></textarea>
                                </div>

                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-users-cog mr-2"></i>Nạp tiền hàng loạt
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Nạp tiền qua VNPay cho user -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 bg-warning">
                <h6 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-credit-card mr-2"></i>Nạp tiền qua VNPay
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('payment.vnpay') }}" method="POST" id="vnpayDepositForm">
                    @csrf
                    <div class="mb-3">
                        <label for="vnpay_user_id" class="form-label">Chọn người dùng</label>
                        <select class="form-select" id="vnpay_user_id" name="user_id" required>
                            <option value="">-- Chọn người dùng --</option>
                            @foreach($users as $user)
                                <option value="{{ $user['id'] }}">{{ $user['full_name'] ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="vnpay_amount" class="form-label">Số tiền (VNĐ)</label>
                        <input type="number" class="form-control" id="vnpay_amount" name="amount" min="1000" step="1000" required>
                    </div>
                    <div class="mb-3">
                        <label for="vnpay_order_info" class="form-label">Nội dung thanh toán</label>
                        <input type="text" class="form-control" id="vnpay_order_info" name="order_info" maxlength="255" value="Nạp tiền cho user" required>
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="fas fa-credit-card mr-2"></i>Nạp qua VNPay
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bộ lọc và thống kê giao dịch -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <i class="fas fa-chart-bar mr-2"></i>Thống kê giao dịch ví
        </div>
        <div class="card-body">
            <form action="/admin/payment/wallet-statistics" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="stat_user_id" class="form-label">Người dùng</label>
                    <select class="form-select" id="stat_user_id" name="user_id">
                        <option value="">-- Tất cả --</option>
                        @if(is_array($users) && count($users) > 0)
                            @foreach($users as $user)
                                <option value="{{ $user['id'] }}" {{ request('user_id') == $user['id'] ? 'selected' : '' }}>{{ $user['full_name'] ?? 'N/A' }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="stat_type" class="form-label">Loại giao dịch</label>
                    <select class="form-select" id="stat_type" name="type">
                        <option value="">-- Tất cả --</option>
                        <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Nạp tiền</option>
                        <option value="withdraw" {{ request('type') == 'withdraw' ? 'selected' : '' }}>Rút tiền</option>
                        <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Hoàn tiền</option>
                        <option value="deduct" {{ request('type') == 'deduct' ? 'selected' : '' }}>Trừ tiền</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="stat_from" class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" id="stat_from" name="from" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <label for="stat_to" class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" id="stat_to" name="to" value="{{ request('to') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search mr-1"></i>Lọc thống kê
                    </button>
                </div>
            </form>
            <div id="walletStatisticsResult" class="mt-4">
                @if(isset($statistics) && is_array($statistics))
                    <div class="row">
                        @php
                            $types = ['deposit', 'withdraw', 'refund', 'deduct'];
                            $labels = [
                                'deposit' => 'Nạp tiền',
                                'withdraw' => 'Rút tiền',
                                'refund' => 'Hoàn tiền',
                                'deduct' => 'Trừ tiền'
                            ];
                        @endphp
                        @foreach($types as $type)
                            @php $stat = $statistics[$type] ?? ['count' => 0, 'total' => 0]; @endphp
                            <div class="col-md-3 mb-3">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ $labels[$type] }}</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stat['count'] }} giao dịch</div>
                                        <div class="h6 mb-0 text-gray-600">Tổng: {{ number_format($stat['total'] ?? 0) }} VNĐ</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class='alert alert-warning'>Không có dữ liệu thống kê.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bảng giao dịch -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>Lịch sử giao dịch
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="transactionsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Người dùng</th>
                                    <th>Loại</th>
                                    <th>Số tiền</th>
                                    <th>Mô tả</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dữ liệu sẽ được load bằng AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xem chi tiết ví user -->
<div class="modal fade" id="userWalletModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông tin ví người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userWalletContent">
                <!-- Nội dung sẽ được load bằng AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all / Deselect all functionality
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('.bulk-user-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('.bulk-user-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    });

    // Form validation
    document.getElementById('depositForm').addEventListener('submit', function(e) {
        const userId = document.getElementById('user_id').value;
        const amount = document.getElementById('amount').value;
        // const description = document.getElementById('description').value; // Bỏ kiểm tra này

        if (!userId || !amount) { // Không kiểm tra description
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin!');
            return false;
        }

        if (amount < 1000) {
            e.preventDefault();
            alert('Số tiền tối thiểu là 1,000 VNĐ!');
            return false;
        }
    });

    document.getElementById('withdrawForm').addEventListener('submit', function(e) {
        const userId = document.getElementById('withdraw_user_id').value;
        const amount = document.getElementById('withdraw_amount').value;
        // const description = document.getElementById('withdraw_description').value; // Bỏ kiểm tra này

        if (!userId || !amount) { // Không kiểm tra description
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin!');
            return false;
        }

        if (amount < 1000) {
            e.preventDefault();
            alert('Số tiền tối thiểu là 1,000 VNĐ!');
            return false;
        }

        if (!confirm('Bạn có chắc chắn muốn rút tiền từ người dùng này?')) {
            e.preventDefault();
            return false;
        }
    });

    document.getElementById('bulkDepositForm').addEventListener('submit', function(e) {
        const selectedUsers = document.querySelectorAll('.bulk-user-checkbox:checked');
        const amount = document.getElementById('bulk_amount').value;
        const description = document.getElementById('bulk_description').value;

        if (selectedUsers.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một người dùng!');
            return false;
        }

        if (!amount || !description) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin!');
            return false;
        }

        if (amount < 1000) {
            e.preventDefault();
            alert('Số tiền tối thiểu là 1,000 VNĐ!');
            return false;
        }

        if (!confirm(`Bạn có chắc chắn muốn nạp ${amount} VNĐ cho ${selectedUsers.length} người dùng?`)) {
            e.preventDefault();
            return false;
        }
    });

    // Load transactions table
    loadTransactions();

    function loadTransactions() {
        fetch('{{ route("admin.payment.transactions") }}')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#transactionsTable tbody');
                tbody.innerHTML = '';

                if (data.transactions && data.transactions.length > 0) {
                    data.transactions.forEach(transaction => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${transaction.id || 'N/A'}</td>
                            <td>${transaction.user?.username || 'N/A'}</td>
                            <td>
                                <span class="badge ${transaction.type === 'deposit' ? 'bg-success' : 'bg-danger'}">
                                    ${transaction.type === 'deposit' ? 'Nạp tiền' : 'Rút tiền'}
                                </span>
                            </td>
                            <td class="fw-bold ${transaction.amount > 0 ? 'text-success' : 'text-danger'}">
                                ${new Intl.NumberFormat('vi-VN').format(transaction.amount || 0)} VNĐ
                            </td>
                            <td>${transaction.description || 'N/A'}</td>
                            <td>${transaction.created_at ? new Date(transaction.created_at).toLocaleString('vi-VN') : 'N/A'}</td>
                            <td>
                                <span class="badge ${transaction.status === 'success' ? 'bg-success' : 'bg-warning'}">
                                    ${transaction.status === 'success' ? 'Thành công' : 'Đang xử lý'}
                                </span>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error loading transactions:', error);
                document.querySelector('#transactionsTable tbody').innerHTML = 
                    '<tr><td colspan="7" class="text-center text-danger">Lỗi khi tải dữ liệu</td></tr>';
            });
    }
});
</script>
@endsection 