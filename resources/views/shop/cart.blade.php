@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Giỏ hàng</h1>
            <p class="mt-2 text-gray-600">Quản lý sản phẩm trong giỏ hàng của bạn</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Sản phẩm trong giỏ hàng</h2>
                    </div>
                    
                    <div id="cart-items-container">
                        <!-- Cart items will be loaded here -->
                        <div class="p-6 text-center">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-shopping-cart text-4xl"></i>
                            </div>
                            <p class="text-gray-600">Đang tải giỏ hàng...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Tổng đơn hàng</h2>
                    </div>
                    
                    <div class="p-6">
                        <!-- Promotion Code -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mã khuyến mãi</label>
                            <div class="flex space-x-2">
                                <input type="text" id="promotion-code" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Nhập mã khuyến mãi">
                                <button onclick="applyPromotionCode()" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    Áp dụng
                                </button>
                            </div>
                            <div id="promotion-message" class="mt-2 text-sm hidden"></div>
                        </div>

                        <!-- Applied Promotion -->
                        <div id="applied-promotion" class="mb-4 hidden">
                            <div class="bg-green-50 border border-green-200 rounded-md p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-800" id="promotion-description"></p>
                                        <p class="text-xs text-green-600" id="promotion-code-display"></p>
                                    </div>
                                    <button onclick="removePromotion()" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span id="subtotal" class="font-medium">0 VNĐ</span>
                            </div>
                            
                            <div id="discount-row" class="flex justify-between text-sm hidden">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span id="discount-amount" class="font-medium text-green-600">-0 VNĐ</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span class="font-medium">0 VNĐ</span>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="flex justify-between text-lg font-bold">
                                <span>Tổng cộng:</span>
                                <span id="total-amount" class="text-blue-600">0 VNĐ</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 space-y-3">
                            <a href="{{ route('shop.checkout') }}" 
                               class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition-colors text-center block">
                                Tiến hành thanh toán
                            </a>
                            <a href="{{ route('shop.products') }}" 
                               class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-md hover:bg-gray-200 transition-colors text-center block">
                                Tiếp tục mua sắm
                            </a>
                            <button onclick="clearCart()" 
                                    class="w-full bg-red-100 text-red-700 py-3 px-4 rounded-md hover:bg-red-200 transition-colors">
                                Xóa giỏ hàng
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="bg-white rounded-lg shadow-md mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Thông tin giao hàng</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Máy:</span>
                                <span class="font-medium">PC-001</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Khu vực:</span>
                                <span class="font-medium">Gaming Zone A</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Thời gian giao:</span>
                                <span class="font-medium">5-10 phút</span>
                            </div>
                        </div>
                    </div>
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
let cart = { items: [], total_amount: 0, total_items: 0 };

// Load cart on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    loadAppliedPromotion();
});

// Load cart from API
async function loadCart() {
    try {
        const response = await fetch('/api/cart', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            const cartData = await response.json();
            cart = cartData;
            renderCart();
            updateOrderSummary();
        } else {
            console.error('Failed to load cart');
            showNotification('Không thể tải giỏ hàng', 'error');
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        showNotification('Lỗi kết nối', 'error');
    }
}

// Render cart items
function renderCart() {
    const container = document.getElementById('cart-items-container');
    
    if (!cart.items || cart.items.length === 0) {
        container.innerHTML = `
            <div class="p-12 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-shopping-cart text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Giỏ hàng trống</h3>
                <p class="text-gray-600 mb-6">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                <a href="{{ route('shop.products') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Mua sắm ngay
                </a>
            </div>
        `;
        return;
    }

    container.innerHTML = `
        <div class="divide-y divide-gray-200">
            ${cart.items.map(item => `
                <div class="p-6 flex items-center space-x-4">
                    <img src="${item.product?.image || 'https://via.placeholder.com/80x80'}" 
                         alt="${item.product?.name}" 
                         class="w-20 h-20 object-cover rounded-md">
                    
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900">${item.product?.name || 'Sản phẩm'}</h3>
                        <p class="text-sm text-gray-600">${item.product?.description || ''}</p>
                        <p class="text-lg font-semibold text-blue-600">${formatCurrency(item.price)}</p>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <button onclick="updateQuantity(${item.product_id}, ${item.quantity - 1})" 
                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <span class="w-12 text-center font-medium">${item.quantity}</span>
                        <button onclick="updateQuantity(${item.product_id}, ${item.quantity + 1})" 
                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-lg font-semibold text-gray-900">${formatCurrency(item.subtotal)}</p>
                        <button onclick="removeItem(${item.product_id})" 
                                class="text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-trash mr-1"></i>Xóa
                        </button>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

// Update order summary
function updateOrderSummary() {
    const subtotal = cart.total_amount || 0;
    const discountAmount = cart.discount_amount || 0;
    const finalAmount = cart.final_amount || subtotal;

    document.getElementById('subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('total-amount').textContent = formatCurrency(finalAmount);

    if (discountAmount > 0) {
        document.getElementById('discount-row').classList.remove('hidden');
        document.getElementById('discount-amount').textContent = `-${formatCurrency(discountAmount)}`;
    } else {
        document.getElementById('discount-row').classList.add('hidden');
    }
}

// Apply promotion code
async function applyPromotionCode() {
    const code = document.getElementById('promotion-code').value.trim();
    if (!code) {
        showNotification('Vui lòng nhập mã khuyến mãi', 'error');
        return;
    }

    try {
        const response = await fetch('/api/cart/apply-promotion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ promotion_code: code })
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message, 'success');
            document.getElementById('promotion-code').value = '';
            loadCart();
            loadAppliedPromotion();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Error applying promotion:', error);
        showNotification('Lỗi khi áp dụng khuyến mãi', 'error');
    }
}

// Load applied promotion
async function loadAppliedPromotion() {
    try {
        const response = await fetch('/api/cart/applied-promotion', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const result = await response.json();

        if (result.success && result.promotion) {
            document.getElementById('applied-promotion').classList.remove('hidden');
            document.getElementById('promotion-description').textContent = result.promotion.description;
            document.getElementById('promotion-code-display').textContent = `Mã: ${result.promotion.code}`;
        } else {
            document.getElementById('applied-promotion').classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading applied promotion:', error);
    }
}

// Remove promotion
async function removePromotion() {
    try {
        const response = await fetch('/api/cart/remove-promotion', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message, 'success');
            loadCart();
            loadAppliedPromotion();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Error removing promotion:', error);
        showNotification('Lỗi khi xóa khuyến mãi', 'error');
    }
}

// Update item quantity
async function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1) return;

    try {
        const response = await fetch('/api/cart/update', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: newQuantity
            })
        });

        if (response.ok) {
            loadCart();
        } else {
            showNotification('Lỗi khi cập nhật số lượng', 'error');
        }
    } catch (error) {
        console.error('Error updating quantity:', error);
        showNotification('Lỗi kết nối', 'error');
    }
}

// Remove item from cart
async function removeItem(productId) {
    try {
        const response = await fetch('/api/cart/remove', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ product_id: productId })
        });

        if (response.ok) {
            loadCart();
            showNotification('Đã xóa sản phẩm khỏi giỏ hàng', 'success');
        } else {
            showNotification('Lỗi khi xóa sản phẩm', 'error');
        }
    } catch (error) {
        console.error('Error removing item:', error);
        showNotification('Lỗi kết nối', 'error');
    }
}

// Clear cart
async function clearCart() {
    if (!confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) return;

    try {
        const response = await fetch('/api/cart/clear', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            loadCart();
            showNotification('Đã xóa toàn bộ giỏ hàng', 'success');
        } else {
            showNotification('Lỗi khi xóa giỏ hàng', 'error');
        }
    } catch (error) {
        console.error('Error clearing cart:', error);
        showNotification('Lỗi kết nối', 'error');
    }
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

// Show notification
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