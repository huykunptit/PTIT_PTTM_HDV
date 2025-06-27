@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Đơn hàng của tôi</h1>
            <p class="mt-2 text-gray-600">Theo dõi trạng thái đơn hàng của bạn</p>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded-lg shadow-md">
            @if(isset($orders) && count($orders) > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-shopping-bag text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Đơn hàng #{{ $order['id'] ?? 'N/A' }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            {{ count($order['items'] ?? []) }} sản phẩm
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($order['created_at'] ?? now())->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <p class="text-lg font-bold text-blue-600">
                                            {{ number_format($order['total_amount'] ?? 0, 0, ',', '.') }} VNĐ
                                        </p>
                                        @if(isset($order['discount_amount']) && $order['discount_amount'] > 0)
                                            <p class="text-sm text-green-600">
                                                Giảm: {{ number_format($order['discount_amount'], 0, ',', '.') }} VNĐ
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div class="text-right">
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
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('shop.order-details', $order['id'] ?? 0) }}" 
                                           class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                            <i class="fas fa-eye mr-1"></i>Chi tiết
                                        </a>
                                        
                                        @if(($order['status'] ?? '') === 'pending')
                                        <button onclick="cancelOrder({{ $order['id'] ?? 0 }})" 
                                                class="px-3 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-sm">
                                            <i class="fas fa-times mr-1"></i>Hủy
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Items Preview -->
                        @if(isset($order['items']) && count($order['items']) > 0)
                        <div class="mt-4 pl-16">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach(array_slice($order['items'], 0, 3) as $item)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-10 h-10 bg-gray-200 rounded-md flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $item['product']['name'] ?? 'Sản phẩm' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            SL: {{ $item['quantity'] ?? 0 }} x {{ number_format($item['price'] ?? 0, 0, ',', '.') }} VNĐ
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                                
                                @if(count($order['items']) > 3)
                                <div class="flex items-center justify-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm text-gray-500">
                                        +{{ count($order['items']) - 3 }} sản phẩm khác
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-shopping-bag text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có đơn hàng nào</h3>
                    <p class="text-gray-600 mb-6">Bạn chưa có đơn hàng nào. Hãy mua sắm để tạo đơn hàng đầu tiên!</p>
                    <a href="{{ route('shop.products') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Mua sắm ngay
                    </a>
                </div>
            @endif
        </div>
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
                window.location.reload();
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