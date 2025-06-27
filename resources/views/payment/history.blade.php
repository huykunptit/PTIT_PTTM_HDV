@extends('layouts.app')

@section('title', 'Lịch sử thanh toán')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Lịch sử thanh toán</h1>
                    <p class="text-gray-600">Xem tất cả các giao dịch thanh toán của bạn</p>
                </div>
                <a href="{{ route('payment.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Thanh toán mới
                </a>
            </div>
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

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bộ lọc</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tất cả</option>
                        <option value="success">Thành công</option>
                        <option value="pending">Đang xử lý</option>
                        <option value="failed">Thất bại</option>
                    </select>
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Loại giao dịch</label>
                    <select id="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tất cả</option>
                        <option value="deposit">Nạp tiền</option>
                        <option value="payment">Thanh toán</option>
                        <option value="refund">Hoàn tiền</option>
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                    <input type="date" id="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                    <input type="date" id="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <button id="filterBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    Lọc
                </button>
                <button id="resetBtn" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                    <i class="fas fa-undo mr-2"></i>
                    Đặt lại
                </button>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Danh sách giao dịch</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mã giao dịch
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày giao dịch
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Loại
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số tiền
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nội dung
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody id="transactionsTable" class="bg-white divide-y divide-gray-200">
                        @if(count($transactions) > 0)
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $transaction['id'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ isset($transaction['created_at']) ? \Carbon\Carbon::parse($transaction['created_at'])->format('d/m/Y H:i:s') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if(($transaction['type'] ?? '') === 'deposit') bg-green-100 text-green-800
                                            @elseif(($transaction['type'] ?? '') === 'payment') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if(($transaction['type'] ?? '') === 'deposit')
                                                <i class="fas fa-arrow-down mr-1"></i>Nạp tiền
                                            @elseif(($transaction['type'] ?? '') === 'payment')
                                                <i class="fas fa-arrow-up mr-1"></i>Thanh toán
                                            @else
                                                <i class="fas fa-exchange-alt mr-1"></i>{{ $transaction['type'] ?? 'Khác' }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="font-medium @if(($transaction['type'] ?? '') === 'deposit') text-green-600 @else text-red-600 @endif">
                                            {{ ($transaction['type'] ?? '') === 'deposit' ? '+' : '-' }}{{ number_format($transaction['amount'] ?? 0) }} VNĐ
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                        {{ $transaction['description'] ?? $transaction['order_info'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if(($transaction['status'] ?? '') === 'success') bg-green-100 text-green-800
                                            @elseif(($transaction['status'] ?? '') === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif(($transaction['status'] ?? '') === 'failed') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if(($transaction['status'] ?? '') === 'success')
                                                <i class="fas fa-check-circle mr-1"></i>Thành công
                                            @elseif(($transaction['status'] ?? '') === 'pending')
                                                <i class="fas fa-clock mr-1"></i>Đang xử lý
                                            @elseif(($transaction['status'] ?? '') === 'failed')
                                                <i class="fas fa-times-circle mr-1"></i>Thất bại
                                            @else
                                                <i class="fas fa-question-circle mr-1"></i>{{ $transaction['status'] ?? 'Không xác định' }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900 view-details" data-transaction="{{ json_encode($transaction) }}">
                                            <i class="fas fa-eye mr-1"></i>Chi tiết
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-receipt text-4xl mb-4 text-gray-300"></i>
                                        <p class="text-lg font-medium mb-2">Chưa có giao dịch nào</p>
                                        <p class="text-sm">Bạn chưa thực hiện giao dịch thanh toán nào.</p>
                                        <a href="{{ route('payment.index') }}" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                                            Thực hiện thanh toán đầu tiên
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(count($transactions) > 0)
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Trước
                            </button>
                            <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Sau
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Hiển thị <span class="font-medium">1</span> đến <span class="font-medium">{{ count($transactions) }}</span> trong tổng số <span class="font-medium">{{ count($transactions) }}</span> giao dịch
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Chi tiết giao dịch</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="transactionDetails" class="space-y-3">
                <!-- Transaction details will be populated here -->
            </div>
            <div class="mt-6 flex justify-end">
                <button id="closeModalBtn" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterBtn = document.getElementById('filterBtn');
    const resetBtn = document.getElementById('resetBtn');
    const statusSelect = document.getElementById('status');
    const typeSelect = document.getElementById('type');
    const dateFromInput = document.getElementById('date_from');
    const dateToInput = document.getElementById('date_to');

    filterBtn.addEventListener('click', function() {
        const filters = {
            status: statusSelect.value,
            type: typeSelect.value,
            date_from: dateFromInput.value,
            date_to: dateToInput.value
        };
        
        // Here you would typically make an AJAX call to filter transactions
        console.log('Filtering with:', filters);
        // For now, we'll just show an alert
        alert('Chức năng lọc sẽ được cập nhật sau!');
    });

    resetBtn.addEventListener('click', function() {
        statusSelect.value = '';
        typeSelect.value = '';
        dateFromInput.value = '';
        dateToInput.value = '';
    });

    // Modal functionality
    const modal = document.getElementById('transactionModal');
    const closeModal = document.getElementById('closeModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const transactionDetails = document.getElementById('transactionDetails');

    document.querySelectorAll('.view-details').forEach(btn => {
        btn.addEventListener('click', function() {
            const transaction = JSON.parse(this.dataset.transaction);
            
            transactionDetails.innerHTML = `
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Mã giao dịch:</span>
                        <p class="text-gray-900">${transaction.id || 'N/A'}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Ngày giao dịch:</span>
                        <p class="text-gray-900">${transaction.created_at ? new Date(transaction.created_at).toLocaleString('vi-VN') : 'N/A'}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Loại giao dịch:</span>
                        <p class="text-gray-900">${transaction.type || 'N/A'}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Số tiền:</span>
                        <p class="text-gray-900 font-medium">${new Intl.NumberFormat('vi-VN').format(transaction.amount || 0)} VNĐ</p>
                    </div>
                    <div class="col-span-2">
                        <span class="font-medium text-gray-700">Nội dung:</span>
                        <p class="text-gray-900">${transaction.description || transaction.order_info || 'N/A'}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Trạng thái:</span>
                        <p class="text-gray-900">${transaction.status || 'N/A'}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Phương thức:</span>
                        <p class="text-gray-900">${transaction.payment_method || 'N/A'}</p>
                    </div>
                </div>
            `;
            
            modal.classList.remove('hidden');
        });
    });

    function closeModalFunction() {
        modal.classList.add('hidden');
    }

    closeModal.addEventListener('click', closeModalFunction);
    closeModalBtn.addEventListener('click', closeModalFunction);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModalFunction();
        }
    });
});
</script>
@endsection 