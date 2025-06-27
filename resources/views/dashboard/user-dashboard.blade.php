@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Chào mừng, {{ $user['full_name'] ?? $user['username'] }}!</h2>
                            <p class="mb-0 opacity-75">Chúc bạn có một ngày chơi game vui vẻ</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="display-4">
                                <i class="fas fa-gamepad"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Thời gian chơi</h6>
                            <h4 class="mb-0">{{ $playtimeInfo['total_hours'] ?? 0 }} giờ</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-shopping-bag text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đơn hàng</h6>
                            <h4 class="mb-0">{{ count($recentOrders) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-wallet text-warning fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Số dư</h6>
                            <h4 class="mb-0">{{ number_format($accountInfo['balance'] ?? 0) }} VNĐ</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-star text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Điểm tích lũy</h6>
                            <h4 class="mb-0">{{ $accountInfo['points'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Thao tác nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('shop.products') }}" class="text-decoration-none">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-shopping-bag fa-3x text-primary mb-3"></i>
                                        <h6 class="card-title">Mua sản phẩm</h6>
                                        <p class="card-text small text-muted">Khám phá các sản phẩm hấp dẫn</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('shop.orders') }}" class="text-decoration-none">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-list-alt fa-3x text-success mb-3"></i>
                                        <h6 class="card-title">Đơn hàng</h6>
                                        <p class="card-text small text-muted">Xem lịch sử đơn hàng</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('shop.promotions') }}" class="text-decoration-none">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-percentage fa-3x text-warning mb-3"></i>
                                        <h6 class="card-title">Khuyến mãi</h6>
                                        <p class="card-text small text-muted">Xem các ưu đãi hiện tại</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('shop.profile') }}" class="text-decoration-none">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user fa-3x text-info mb-3"></i>
                                        <h6 class="card-title">Hồ sơ</h6>
                                        <p class="card-text small text-muted">Cập nhật thông tin cá nhân</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2 text-primary"></i>
                        Đơn hàng gần đây
                    </h5>
                    <a href="{{ route('shop.orders') }}" class="btn btn-outline-primary btn-sm">
                        Xem tất cả
                    </a>
                </div>
                <div class="card-body">
                    @if(count($recentOrders) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">#{{ $order['id'] ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($order['created_at'] ?? now())->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="fw-bold text-primary">
                                                {{ number_format($order['total_amount'] ?? 0) }} VNĐ
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $status = $order['status'] ?? 'pending';
                                                $statusClass = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'info',
                                                    'processing' => 'primary',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ][$status] ?? 'secondary';
                                                $statusText = [
                                                    'pending' => 'Chờ xác nhận',
                                                    'confirmed' => 'Đã xác nhận',
                                                    'processing' => 'Đang xử lý',
                                                    'completed' => 'Hoàn thành',
                                                    'cancelled' => 'Đã hủy'
                                                ][$status] ?? 'Không xác định';
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('shop.order-details', $order['id'] ?? 0) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Xem
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="text-muted mb-3">
                                <i class="fas fa-shopping-bag fa-3x"></i>
                            </div>
                            <h5 class="text-muted">Chưa có đơn hàng nào</h5>
                            <p class="text-muted">Bắt đầu mua sắm để xem đơn hàng ở đây</p>
                            <a href="{{ route('shop.products') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-comments me-2"></i>
                    Hỗ trợ trực tuyến
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="chat-container" style="height: 400px; overflow-y: auto;">
                    <div class="chat-messages" id="chatMessages">
                        <!-- Messages will be loaded here -->
                    </div>
                </div>
                <div class="chat-input mt-3">
                    <div class="input-group">
                        <input type="text" id="chatInput" class="form-control" placeholder="Nhập tin nhắn...">
                        <button class="btn btn-primary" onclick="sendMessage()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chat functionality
function openChat() {
    const modal = new bootstrap.Modal(document.getElementById('chatModal'));
    modal.show();
}

function sendMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    
    if (message) {
        // Add message to chat
        addMessageToChat('user', message);
        input.value = '';
        
        // Simulate response
        setTimeout(() => {
            addMessageToChat('support', 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất có thể!');
        }, 1000);
    }
}

function addMessageToChat(sender, message) {
    const messagesContainer = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `d-flex ${sender === 'user' ? 'justify-content-end' : 'justify-content-start'} mb-2`;
    
    messageDiv.innerHTML = `
        <div class="chat-bubble ${sender === 'user' ? 'bg-primary text-white' : 'bg-light'} rounded p-2" style="max-width: 70%;">
            ${message}
        </div>
    `;
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Add welcome message when chat opens
document.getElementById('chatModal').addEventListener('show.bs.modal', function () {
    const messagesContainer = document.getElementById('chatMessages');
    messagesContainer.innerHTML = `
        <div class="d-flex justify-content-start mb-2">
            <div class="chat-bubble bg-light rounded p-2">
                Xin chào! Tôi có thể giúp gì cho bạn?
            </div>
        </div>
    `;
});
</script>

<style>
.chat-bubble {
    word-wrap: break-word;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection 