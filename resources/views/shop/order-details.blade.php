@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Chi tiết đơn hàng</h1>
                    <p class="mt-2 text-gray-600">Thông tin chi tiết về đơn hàng của bạn</p>
                </div>
                <a href="{{ route('shop.orders') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>

        @if(isset($order))
        <!-- Order Information -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Thông tin đơn hàng</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin cơ bản</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mã đơn hàng:</span>
                                <span class="font-medium">#{{ $order['id'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ngày đặt:</span>
                                <span class="font-medium">
                                    {{ \Carbon\Carbon::parse($order['created_at'] ?? now())->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Trạng thái:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if(($order['status'] ?? '') === 'completed') bg-green-100 text-green-800
                                    @elseif(($order['status'] ?? '') === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif(($order['status'] ?? '') === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @switch($order['status'] ?? '')
                                        @case('completed')
                                            <i class="fas fa-check-circle mr-1"></i>Hoàn thành
                                            @break
                                        @case('pending')
                                            <i class="fas fa-clock mr-1"></i>Đang xử lý
                                            @break
                                        @case('cancelled')
                                            <i class="fas fa-times-circle mr-1"></i>Đã hủy
                                            @break
                                        @default
                                            <i class="fas fa-question-circle mr-1"></i>Không xác định
                                    @endswitch
                                </span>
                            </div>
                            @if(isset($order['promotion_code']) && $order['promotion_code'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mã khuyến mãi:</span>
                                <span class="font-medium text-green-600">{{ $order['promotion_code'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin thanh toán</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-medium">{{ number_format($order['total_amount'] ?? 0, 0, ',', '.') }} VNĐ</span>
                            </div>
                            @if(isset($order['discount_amount']) && $order['discount_amount'] > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span class="font-medium text-green-600">-{{ number_format($order['discount_amount'], 0, ',', '.') }} VNĐ</span>
                            </div>
                            @endif
                            <hr class="my-2">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold">Tổng cộng:</span>
                                <span class="text-lg font-bold text-blue-600">
                                    {{ number_format(($order['total_amount'] ?? 0) - ($order['discount_amount'] ?? 0), 0, ',', '.') }} VNĐ
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Sản phẩm đã đặt</h2>
            </div>
            <div class="p-6">
                @if(isset($order['items']) && count($order['items']) > 0)
                    <div class="space-y-4">
                        @foreach($order['items'] as $item)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-box text-gray-400 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item['product']['name'] ?? 'Sản phẩm' }}</h4>
                                <p class="text-sm text-gray-600">{{ $item['product']['description'] ?? '' }}</p>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center space-x-4">
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">{{ $item['quantity'] ?? 0 }}</span> x 
                                        <span class="font-medium">{{ number_format($item['price'] ?? 0, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 0, ',', '.') }} VNĐ
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-box-open text-4xl"></i>
                        </div>
                        <p class="text-gray-600">Không có sản phẩm nào trong đơn hàng</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        @if(($order['status'] ?? '') === 'pending')
        <div class="mt-6 flex justify-center">
            <button onclick="cancelOrder({{ $order['id'] ?? 0 }})" 
                    class="px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Hủy đơn hàng
            </button>
        </div>
        @endif

        @else
        <!-- Order Not Found -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-exclamation-triangle text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Không tìm thấy đơn hàng</h3>
                <p class="text-gray-600 mb-6">Đơn hàng bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
                <a href="{{ route('shop.orders') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại danh sách đơn hàng
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Notification Toast -->
<div id="notification" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span id="notification-message"></span>
        </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        return;
    }

    fetch(`/shop/orders/${orderId}/cancel`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('Đã hủy đơn hàng thành công');
            setTimeout(() => {
                window.location.href = '{{ route("shop.orders") }}';
            }, 2000);
        } else {
            showNotification(result.message || 'Lỗi khi hủy đơn hàng', 'error');
        }
    })
    .catch(error => {
        console.error('Error canceling order:', error);
        showNotification('Lỗi kết nối', 'error');
    });
}

function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const messageElement = document.getElementById('notification-message');
    
    messageElement.textContent = message;
    
    // Update background color based on type
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    notification.querySelector('div').className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg`;
    
    notification.classList.remove('hidden');
    
    setTimeout(() => {
        notification.classList.add('hidden');
    }, 3000);
}
</script>
@endsection 