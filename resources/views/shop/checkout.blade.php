@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">Thanh toán</h1>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Order Summary -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Thông tin đơn hàng</h2>
                        <div id="order-items" class="space-y-4">
                            <!-- Order items will be loaded here -->
                        </div>
                        
                        <div class="border-t pt-4 mt-6">
                            <div class="flex justify-between items-center text-lg font-semibold">
                                <span>Tổng cộng:</span>
                                <span id="order-total" class="text-blue-600">0 VNĐ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Checkout Form -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Thông tin thanh toán</h2>
                        <form id="checkout-form" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Họ và tên
                                </label>
                                <input
                                    type="text"
                                    id="full_name"
                                    value="{{ session('user')['full_name'] }}"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Số điện thoại
                                </label>
                                <input
                                    type="tel"
                                    id="phone"
                                    value="{{ session('user')['phone'] ?? '' }}"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Địa chỉ
                                </label>
                                <textarea
                                    id="address"
                                    rows="3"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required
                                >{{ session('user')['address'] ?? '' }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Ghi chú
                                </label>
                                <textarea
                                    id="notes"
                                    rows="2"
                                    placeholder="Ghi chú cho đơn hàng (không bắt buộc)"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Phương thức thanh toán
                                </label>
                                <select
                                    id="payment_method"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required
                                >
                                    <option value="cash">Tiền mặt</option>
                                    <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                                    <option value="momo">Ví MoMo</option>
                                    <option value="zalopay">Ví ZaloPay</option>
                                </select>
                            </div>

                            <button
                                type="submit"
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition-colors font-semibold"
                            >
                                <i class="fas fa-credit-card mr-2"></i>
                                Đặt hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = { items: [], total_amount: 0, total_items: 0 };

// Load cart on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
});

// Load cart from API
async function loadCart() {
    try {
        const response = await fetch('/api/cart', {
            headers: {
                'Authorization': 'Bearer {{ session("token") }}',
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            cart = await response.json();
            renderOrderSummary();
        } else {
            showNotification('Lỗi khi tải giỏ hàng', 'error');
            setTimeout(() => {
                window.location.href = '{{ route("shop.products") }}';
            }, 2000);
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        showNotification('Lỗi khi tải giỏ hàng', 'error');
    }
}

// Render order summary
function renderOrderSummary() {
    const orderItems = document.getElementById('order-items');
    const orderTotal = document.getElementById('order-total');

    if (cart.items && cart.items.length > 0) {
        orderItems.innerHTML = cart.items.map(item => `
            <div class="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg">
                <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-box text-gray-400 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">${item.product ? item.product.name : 'Product'}</h4>
                    <p class="text-sm text-gray-600">${formatCurrency(item.price)} x ${item.quantity}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold">${formatCurrency(item.subtotal)}</p>
                </div>
            </div>
        `).join('');
        
        orderTotal.textContent = formatCurrency(cart.total_amount);
    } else {
        orderItems.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-shopping-cart text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500">Giỏ hàng trống</p>
                <a href="{{ route('shop.products') }}" class="text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    Quay lại mua sắm
                </a>
            </div>
        `;
        orderTotal.textContent = '0 VNĐ';
    }
}

// Handle checkout form submission
document.getElementById('checkout-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (cart.items.length === 0) {
        showNotification('Giỏ hàng trống', 'error');
        return;
    }

    const formData = {
        full_name: document.getElementById('full_name').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        notes: document.getElementById('notes').value,
        payment_method: document.getElementById('payment_method').value,
        items: cart.items,
        total_amount: cart.total_amount
    };

    try {
        const response = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer {{ session("token") }}',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            const result = await response.json();
            showNotification('Đặt hàng thành công!', 'success');
            setTimeout(() => {
                window.location.href = '{{ route("shop.orders") }}';
            }, 2000);
        } else {
            const error = await response.json();
            showNotification(error.message || 'Lỗi khi đặt hàng', 'error');
        }
    } catch (error) {
        console.error('Error placing order:', error);
        showNotification('Lỗi khi đặt hàng', 'error');
    }
});

// Show notification
function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}
</script>
@endsection 