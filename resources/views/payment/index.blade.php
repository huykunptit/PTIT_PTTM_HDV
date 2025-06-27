@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Thanh toán trực tuyến</h1>
            <p class="text-gray-600">Nạp tiền vào tài khoản của bạn thông qua VNPay</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Payment Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Thông tin thanh toán</h2>
                
                <form action="{{ route('payment.vnpay') }}" method="POST" id="paymentForm">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Số tiền nạp (VNĐ)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="amount" 
                                   name="amount" 
                                   min="1000" 
                                   step="1000"
                                   value="{{ old('amount', 50000) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Nhập số tiền (tối thiểu 1,000 VNĐ)"
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500">VNĐ</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Số tiền tối thiểu: 1,000 VNĐ</p>
                    </div>

                    <div class="mb-6">
                        <label for="order_info" class="block text-sm font-medium text-gray-700 mb-2">
                            Nội dung thanh toán
                        </label>
                        <input type="text" 
                               id="order_info" 
                               name="order_info" 
                               value="{{ old('order_info', 'Nạp tiền vào tài khoản') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Nhập nội dung thanh toán"
                               maxlength="255"
                               required>
                    </div>

                    <!-- Quick Amount Buttons -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Chọn nhanh số tiền
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" class="quick-amount-btn px-3 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50" data-amount="50000">50,000</button>
                            <button type="button" class="quick-amount-btn px-3 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50" data-amount="100000">100,000</button>
                            <button type="button" class="quick-amount-btn px-3 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50" data-amount="200000">200,000</button>
                            <button type="button" class="quick-amount-btn px-3 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50" data-amount="500000">500,000</button>
                            <button type="button" class="quick-amount-btn px-3 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50" data-amount="1000000">1,000,000</button>
                            <button type="button" class="quick-amount-btn px-3 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50" data-amount="2000000">2,000,000</button>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition duration-200">
                        <i class="fas fa-credit-card mr-2"></i>
                        Thanh toán qua VNPay
                    </button>
                </form>
            </div>

            <!-- Payment Info -->
            <div class="space-y-6">
                <!-- Current Balance -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Số dư hiện tại</h3>
                    <div class="text-3xl font-bold text-green-600 mb-2">
                        {{ number_format($balance) }} VNĐ
                    </div>
                    <p class="text-sm text-gray-600">Số dư trong tài khoản của bạn</p>
                </div>

                <!-- VNPay Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin VNPay</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-500 mr-3"></i>
                            <span class="text-sm text-gray-700">Bảo mật SSL 256-bit</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-credit-card text-blue-500 mr-3"></i>
                            <span class="text-sm text-gray-700">Hỗ trợ thẻ ATM, Visa, Mastercard</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-orange-500 mr-3"></i>
                            <span class="text-sm text-gray-700">Xử lý nhanh chóng 24/7</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-headset text-purple-500 mr-3"></i>
                            <span class="text-sm text-gray-700">Hỗ trợ khách hàng 24/7</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Steps -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quy trình thanh toán</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5">1</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Nhập thông tin thanh toán</p>
                                <p class="text-xs text-gray-600">Chọn số tiền và nội dung thanh toán</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5">2</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Chuyển đến VNPay</p>
                                <p class="text-xs text-gray-600">Hệ thống sẽ chuyển bạn đến trang thanh toán VNPay</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5">3</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Hoàn tất thanh toán</p>
                                <p class="text-xs text-gray-600">Nhập thông tin thẻ và xác nhận thanh toán</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5">4</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Nhận tiền</p>
                                <p class="text-xs text-gray-600">Tiền sẽ được nạp vào tài khoản ngay lập tức</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History Link -->
        <div class="mt-8 text-center">
            <a href="{{ route('payment.history') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                <i class="fas fa-history mr-2"></i>
                Xem lịch sử thanh toán
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick amount buttons
    const quickAmountBtns = document.querySelectorAll('.quick-amount-btn');
    const amountInput = document.getElementById('amount');

    quickAmountBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const amount = this.dataset.amount;
            amountInput.value = amount;
            
            // Remove active class from all buttons
            quickAmountBtns.forEach(b => b.classList.remove('bg-blue-100', 'border-blue-500', 'text-blue-700'));
            
            // Add active class to clicked button
            this.classList.add('bg-blue-100', 'border-blue-500', 'text-blue-700');
        });
    });

    // Form validation
    const form = document.getElementById('paymentForm');
    form.addEventListener('submit', function(e) {
        const amount = parseInt(amountInput.value);
        if (amount < 1000) {
            e.preventDefault();
            alert('Số tiền tối thiểu là 1,000 VNĐ');
            return false;
        }
    });

    // Format amount input
    amountInput.addEventListener('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        if (value < 1000) {
            this.setCustomValidity('Số tiền tối thiểu là 1,000 VNĐ');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endsection 