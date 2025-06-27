@extends('layouts.app')

@section('title', 'Khuyến mãi')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Khuyến mãi</h1>
            <p class="mt-2 text-gray-600">Khám phá các ưu đãi hấp dẫn dành cho bạn</p>
        </div>

        <!-- Promotions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($promotions as $promotion)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <!-- Promotion Badge -->
                <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-4 py-2">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">{{ $promotion['discount_percent'] }}% OFF</span>
                        <span class="text-sm">{{ $promotion['is_active'] ? 'Đang diễn ra' : 'Đã kết thúc' }}</span>
                    </div>
                </div>

                <!-- Promotion Content -->
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $promotion['name'] }}</h3>
                    <p class="text-gray-600 mb-4">{{ $promotion['description'] }}</p>
                    
                    <!-- Date Range -->
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>{{ \Carbon\Carbon::parse($promotion['start_date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($promotion['end_date'])->format('d/m/Y') }}</span>
                    </div>

                    <!-- Action Button -->
                    <button onclick="applyPromotion('{{ $promotion['id'] }}')" 
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        Áp dụng ngay
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-gift text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có khuyến mãi</h3>
                <p class="text-gray-600">Hiện tại chưa có khuyến mãi nào đang diễn ra.</p>
            </div>
            @endforelse
        </div>

        <!-- Promotion Codes Section -->
        <div class="mt-12 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Mã khuyến mãi</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- SUMMER20 -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-mono text-lg font-bold text-blue-600">SUMMER20</span>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">20% OFF</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Giảm 20% cho tất cả sản phẩm mùa hè</p>
                    <button onclick="copyPromotionCode('SUMMER20')" 
                            class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors duration-200 text-sm">
                        <i class="fas fa-copy mr-2"></i>Copy mã
                    </button>
                </div>

                <!-- FLASH30 -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-mono text-lg font-bold text-red-600">FLASH30</span>
                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">30% OFF</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Flash sale giảm 30% trong 24h</p>
                    <button onclick="copyPromotionCode('FLASH30')" 
                            class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors duration-200 text-sm">
                        <i class="fas fa-copy mr-2"></i>Copy mã
                    </button>
                </div>

                <!-- NEW15 -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-mono text-lg font-bold text-green-600">NEW15</span>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">15% OFF</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Giảm 15% cho sản phẩm mới</p>
                    <button onclick="copyPromotionCode('NEW15')" 
                            class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors duration-200 text-sm">
                        <i class="fas fa-copy mr-2"></i>Copy mã
                    </button>
                </div>
            </div>
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
// Copy promotion code to clipboard
function copyPromotionCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        showNotification('Đã copy mã khuyến mãi: ' + code);
    }, function(err) {
        showNotification('Không thể copy mã khuyến mãi');
    });
}

// Apply promotion
function applyPromotion(promotionId) {
    // This would typically apply the promotion to the current cart
    showNotification('Đã áp dụng khuyến mãi!');
}

// Show notification
function showNotification(message) {
    const notification = document.getElementById('notification');
    const messageElement = document.getElementById('notification-message');
    
    messageElement.textContent = message;
    notification.classList.remove('hidden');
    
    setTimeout(() => {
        notification.classList.add('hidden');
    }, 3000);
}
</script>
@endsection 