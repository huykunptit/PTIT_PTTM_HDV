# Cấu hình VNPay cho hệ thống thanh toán

## 1. Cấu hình môi trường (.env)

Thêm các biến môi trường sau vào file `.env`:

```env
# VNPay Configuration
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_TMN_CODE=your_tmn_code_here
VNPAY_HASH_SECRET=your_hash_secret_here
VNPAY_RETURN_URL=http://your-domain.com/payment/vnpay-return
```

## 2. Giải thích các tham số

- `VNPAY_URL`: URL của VNPay (sandbox cho môi trường test, production cho môi trường thật)
- `VNPAY_TMN_CODE`: Mã website tại VNPAY (Terminal ID)
- `VNPAY_HASH_SECRET`: Chuỗi bí mật để tạo chữ ký
- `VNPAY_RETURN_URL`: URL callback sau khi thanh toán xong

## 3. Môi trường Sandbox (Test)

Để test hệ thống, sử dụng:
- URL: `https://sandbox.vnpayment.vn/paymentv2/vpcpay.html`
- TMN Code: Lấy từ tài khoản sandbox VNPay
- Hash Secret: Lấy từ tài khoản sandbox VNPay

## 4. Môi trường Production

Khi triển khai thật, thay đổi:
- URL: `https://pay.vnpay.vn/vpcpay.html`
- TMN Code: Lấy từ tài khoản production VNPay
- Hash Secret: Lấy từ tài khoản production VNPay

## 5. Cách lấy thông tin VNPay

1. Đăng ký tài khoản merchant tại VNPay
2. Vào phần cấu hình website
3. Lấy Terminal ID (TMN Code)
4. Lấy Hash Secret
5. Cấu hình IPN URL (nếu cần)

## 6. Test thẻ

### Thẻ ATM nội địa (test):
- Ngân hàng: NCB
- Số thẻ: 9704198526191432198
- Tên chủ thẻ: NGUYEN VAN A
- Ngày phát hành: 07/15
- OTP: 123456

### Thẻ quốc tế (test):
- Ngân hàng: NCB
- Số thẻ: 4200000000000000
- Tên chủ thẻ: NGUYEN VAN A
- Ngày phát hành: 07/15
- CVV: 123

## 7. Lưu ý bảo mật

- Không commit file .env lên git
- Bảo mật Hash Secret
- Sử dụng HTTPS cho production
- Kiểm tra chữ ký trong callback
- Log đầy đủ các giao dịch

## 8. Troubleshooting

### Lỗi thường gặp:
1. **Chữ ký không hợp lệ**: Kiểm tra Hash Secret
2. **TMN Code không đúng**: Kiểm tra Terminal ID
3. **URL return không đúng**: Cấu hình đúng domain
4. **Lỗi kết nối**: Kiểm tra firewall và network

### Debug:
- Bật debug mode trong .env
- Kiểm tra log Laravel
- Sử dụng VNPay test tools 