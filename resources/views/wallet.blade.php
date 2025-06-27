@extends('layouts.app')

@section('title', 'Ví của tôi')

@section('breadcrumb')
<li class="breadcrumb-item active">Ví</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header với link thanh toán -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="fas fa-wallet me-2"></i>Quản lý ví</h3>
                <a href="{{ route('payment.index') }}" class="btn btn-primary">
                    <i class="fas fa-credit-card me-2"></i>Thanh toán trực tuyến
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Số dư ví -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-3"><i class="fas fa-wallet me-2"></i>Số dư ví</h3>
                    <h1 class="display-4 text-primary mb-3">{{ number_format($balance ?? 0) }} VNĐ</h1>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('payment.index') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Nạp tiền
                        </a>
                        <a href="{{ route('payment.history') }}" class="btn btn-info">
                            <i class="fas fa-history me-2"></i>Lịch sử
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lịch sử giao dịch -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử giao dịch gần đây</h5>
                </div>
                <div class="card-body">
                    @if(isset($transactions) && count($transactions) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Loại</th>
                                        <th>Số tiền</th>
                                        <th>Mô tả</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $tx)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tx['created_at'])->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge {{ ($tx['type'] ?? '') === 'deposit' ? 'bg-success' : 'bg-danger' }}">
                                                @if(($tx['type'] ?? '') === 'deposit')
                                                    <i class="fas fa-arrow-down me-1"></i>Nạp tiền
                                                @elseif(($tx['type'] ?? '') === 'payment')
                                                    <i class="fas fa-arrow-up me-1"></i>Thanh toán
                                                @else
                                                    <i class="fas fa-exchange-alt me-1"></i>{{ ucfirst($tx['type'] ?? 'Khác') }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="fw-bold {{ ($tx['amount'] ?? 0) > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ ($tx['amount'] ?? 0) > 0 ? '+' : '' }}{{ number_format($tx['amount'] ?? 0) }} VNĐ
                                        </td>
                                        <td>{{ $tx['description'] ?? $tx['order_info'] ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ ($tx['status'] ?? '') === 'success' ? 'bg-success' : 'bg-warning' }}">
                                                @if(($tx['status'] ?? '') === 'success')
                                                    <i class="fas fa-check-circle me-1"></i>Thành công
                                                @elseif(($tx['status'] ?? '') === 'pending')
                                                    <i class="fas fa-clock me-1"></i>Đang xử lý
                                                @else
                                                    <i class="fas fa-question-circle me-1"></i>{{ $tx['status'] ?? 'Không xác định' }}
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('payment.history') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>Xem tất cả giao dịch
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="text-muted mb-3">
                                <i class="fas fa-receipt fa-3x"></i>
                            </div>
                            <h5 class="text-muted mb-3">Chưa có giao dịch nào</h5>
                            <p class="text-muted mb-4">Bạn chưa thực hiện giao dịch thanh toán nào.</p>
                            <a href="{{ route('payment.index') }}" class="btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i>Thực hiện thanh toán đầu tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Thông báo lỗi -->
    @if(isset($error) && $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Các chức năng cũ (chỉ hiển thị nếu có quyền) -->
    @if(!isset($error) || !$error)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-minus-circle me-2"></i>Thanh toán từ ví</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('/wallet/payment') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Số tiền cần thanh toán</label>
                            <input type="number" name="amount" class="form-control" required min="1000" step="1000">
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-minus me-2"></i>Thanh toán
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Nạp tiền vào ví</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('/wallet/deposit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Số tiền muốn nạp</label>
                            <input type="number" name="amount" class="form-control" required min="1000" step="1000">
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Nạp tiền
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 