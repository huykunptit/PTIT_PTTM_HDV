# Hệ thống Quản lý Thanh toán và Ví

## Tổng quan

Hệ thống này bao gồm các module chính:
- **Wallet**: Quản lý ví tiền của người dùng
- **Payment**: Thanh toán trực tuyến qua VNPay
- **Admin Payment**: Quản lý thanh toán cho admin

## Các tính năng chính

### 1. Module Wallet (Cho người dùng)
- Xem số dư ví
- Lịch sử giao dịch
- Nạp tiền vào ví
- Thanh toán từ ví
- Tự động chuyển hướng đến trang thanh toán khi không có quyền

### 2. Module Payment (Cho người dùng)
- Thanh toán trực tuyến qua VNPay
- Chọn số tiền nhanh
- Lịch sử thanh toán
- Xử lý callback từ VNPay

### 3. Module Admin Payment (Cho admin)
- Nạp tiền cho người dùng
- Rút tiền từ người dùng
- Nạp tiền hàng loạt
- Xem thống kê tổng quan
- Xuất dữ liệu giao dịch

## Cấu hình

### 1. Biến môi trường (.env)
```env
# Gateway Configuration
GATEWAY_URL=https://your-gateway-url.com

# VNPay Configuration
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_TMN_CODE=your_tmn_code_here
VNPAY_HASH_SECRET=your_hash_secret_here
VNPAY_RETURN_URL=http://your-domain.com/payment/vnpay-return
```

### 2. Routes
```php
// User routes
Route::get('/wallet', [WalletController::class, 'statistics'])->name('wallet');
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/', [PaymentController::class, 'showPaymentForm'])->name('index');
    Route::post('/vnpay', [PaymentController::class, 'createVNPayPayment'])->name('vnpay');
    Route::get('/vnpay-return', [PaymentController::class, 'vnpayReturn'])->name('vnpay-return');
    Route::get('/history', [PaymentController::class, 'paymentHistory'])->name('history');
});

// Admin routes
Route::prefix('admin/payment')->name('admin.payment.')->group(function () {
    Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
    Route::post('/deposit', [AdminPaymentController::class, 'depositForUser'])->name('deposit');
    Route::post('/withdraw', [AdminPaymentController::class, 'withdrawFromUser'])->name('withdraw');
    Route::post('/bulk-deposit', [AdminPaymentController::class, 'bulkDeposit'])->name('bulk-deposit');
    Route::get('/export', [AdminPaymentController::class, 'exportTransactions'])->name('export');
});
```

## Sử dụng

### Cho người dùng thường

1. **Truy cập ví**:
   - Đăng nhập vào hệ thống
   - Vào menu "Ví" hoặc truy cập `/wallet`
   - Xem số dư và lịch sử giao dịch

2. **Thanh toán trực tuyến**:
   - Từ trang ví, click "Thanh toán trực tuyến"
   - Hoặc truy cập `/payment`
   - Chọn số tiền và nội dung thanh toán
   - Click "Thanh toán qua VNPay"
   - Hoàn tất thanh toán trên trang VNPay

3. **Xem lịch sử**:
   - Truy cập `/payment/history`
   - Xem tất cả giao dịch với bộ lọc

### Cho admin

1. **Truy cập quản lý thanh toán**:
   - Đăng nhập với tài khoản admin
   - Vào menu "Quản lý thanh toán" hoặc truy cập `/admin/payment`

2. **Nạp tiền cho user**:
   - Chọn người dùng từ dropdown
   - Nhập số tiền và mô tả
   - Click "Nạp tiền"

3. **Rút tiền từ user**:
   - Chọn người dùng từ dropdown
   - Nhập số tiền và mô tả
   - Click "Rút tiền"

4. **Nạp tiền hàng loạt**:
   - Chọn nhiều người dùng bằng checkbox
   - Nhập số tiền cho mỗi người
   - Click "Nạp tiền hàng loạt"

5. **Xuất dữ liệu**:
   - Click "Xuất dữ liệu" để tải file CSV

## Xử lý lỗi

### Lỗi 401/403 khi truy cập ví
- Hệ thống tự động chuyển hướng đến trang thanh toán
- Hiển thị thông báo: "Bạn không có quyền truy cập ví. Vui lòng sử dụng chức năng thanh toán."

### Lỗi đăng nhập với thông báo nạp tiền
- Khi API trả về "Vui lòng nạp tiền vào ví để chơi game."
- Hệ thống tự động chuyển hướng đến trang thanh toán
- Hiển thị thông báo lỗi từ API

### Lỗi VNPay
- Kiểm tra cấu hình VNPay trong .env
- Đảm bảo URL return đúng
- Kiểm tra chữ ký hash

## Bảo mật

1. **Token Authentication**: Tất cả API calls đều sử dụng token
2. **Admin Middleware**: Kiểm tra quyền admin cho các chức năng quản lý
3. **VNPay Hash Verification**: Kiểm tra chữ ký từ VNPay
4. **Input Validation**: Validate tất cả input từ user
5. **Error Handling**: Xử lý lỗi an toàn, không expose thông tin nhạy cảm

## API Endpoints

### Wallet API
- `GET /api/wallet/statistics` - Lấy thống kê ví
- `GET /api/wallet/transactions/filter` - Lọc giao dịch
- `POST /api/wallet/deposit` - Nạp tiền
- `POST /api/wallet/payment` - Thanh toán

### Admin Wallet API
- `GET /api/wallet/admin/statistics` - Thống kê tổng quan
- `POST /api/wallet/admin/deposit` - Admin nạp tiền cho user
- `POST /api/wallet/admin/withdraw` - Admin rút tiền từ user
- `POST /api/wallet/admin/bulk-deposit` - Nạp tiền hàng loạt
- `GET /api/wallet/admin/transactions` - Lấy giao dịch admin
- `GET /api/wallet/admin/transactions/export` - Xuất dữ liệu

## Troubleshooting

### Lỗi thường gặp

1. **"Token không hợp lệ"**
   - Kiểm tra session
   - Đăng nhập lại

2. **"Không có quyền truy cập"**
   - Kiểm tra role_id của user
   - Đảm bảo user có quyền admin

3. **"Chữ ký không hợp lệ"**
   - Kiểm tra VNPAY_HASH_SECRET
   - Đảm bảo URL return đúng

4. **"Connection error"**
   - Kiểm tra GATEWAY_URL
   - Kiểm tra network connection

### Debug

1. Bật debug mode trong .env
2. Kiểm tra log Laravel: `storage/logs/laravel.log`
3. Kiểm tra response từ API Gateway
4. Sử dụng browser developer tools để debug frontend

## Cập nhật và bảo trì

1. **Cập nhật VNPay config** khi chuyển từ sandbox sang production
2. **Backup dữ liệu** trước khi cập nhật
3. **Test kỹ** các chức năng thanh toán
4. **Monitor logs** để phát hiện lỗi sớm
5. **Cập nhật security patches** thường xuyên 

$response = \Illuminate\Support\Facades\Http::withToken($token)
    ->post(env('GATEWAY_URL', 'http://localhost:8000') . '/api/payments', $payload);

if ($response->successful() && isset($response->json()['payment_url'])) {
    return redirect()->away($response->json()['payment_url']);
} 