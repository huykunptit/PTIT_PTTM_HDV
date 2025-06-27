<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $gatewayUrl;
    private $vnpayUrl;
    private $vnpayTmnCode;
    private $vnpayHashSecret;
    private $vnpayReturnUrl;

    public function __construct()
    {
        $this->gatewayUrl = env('GATEWAY_URL', 'http://localhost:8000');
        $this->vnpayUrl = env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $this->vnpayTmnCode = env('VNPAY_TMN_CODE', '');
        $this->vnpayHashSecret = env('VNPAY_HASH_SECRET', '');
        $this->vnpayReturnUrl = env('VNPAY_RETURN_URL', url('/payment/vnpay-return'));
    }

    public function showPaymentForm()
    {
        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }

        // Lấy thông tin ví
        $balanceRes = Http::withToken($token)->get($this->gatewayUrl . '/api/wallet/statistics');
        $balance = $balanceRes->successful() ? $balanceRes->json('balance') : 0;

        return view('payment.index', compact('balance'));
    }

    public function createVNPayPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'order_info' => 'required|string|max:255',
        ]);

        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }

        $amount = $request->amount;
        $orderInfo = $request->order_info;
        $orderId = 'VNPAY_' . time() . '_' . rand(1000, 9999);

        // Tạo URL thanh toán VNPay
        $vnpayUrl = $this->createVNPayUrl($amount, $orderId, $orderInfo);

        // Lưu thông tin giao dịch vào session
        session([
            'vnpay_order_id' => $orderId,
            'vnpay_amount' => $amount,
            'vnpay_order_info' => $orderInfo
        ]);

        return redirect($vnpayUrl);
    }

    public function vnpayReturn(Request $request)
    {
        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }

        $vnpResponseCode = $request->vnp_ResponseCode;
        $vnpTxnRef = $request->vnp_TxnRef;
        $vnpAmount = $request->vnp_Amount;
        $vnpOrderInfo = $request->vnp_OrderInfo;
        $vnpSecureHash = $request->vnp_SecureHash;

        // Kiểm tra hash
        if (!$this->verifyVNPayHash($request->all())) {
            return redirect()->route('payment.index')->withErrors(['Chữ ký không hợp lệ!']);
        }

        if ($vnpResponseCode === '00') {
            // Thanh toán thành công
            $amount = $vnpAmount / 100; // VNPay trả về số tiền nhân 100

            // Gọi API để nạp tiền vào ví
            $depositRes = Http::withToken($token)->post($this->gatewayUrl . '/api/wallet/deposit', [
                'amount' => $amount,
                'payment_method' => 'vnpay',
                'transaction_id' => $vnpTxnRef,
                'order_info' => $vnpOrderInfo
            ]);
           
            if ($depositRes->successful()) {
                return redirect()->route('wallet')->with('success', 'Nạp tiền thành công! Số tiền: ' . number_format($amount) . ' VNĐ');
            } else {
                return redirect()->route('payment.index')->withErrors(['Có lỗi xảy ra khi nạp tiền: ' . ($depositRes->json('message') ?? 'Lỗi không xác định')]);
            }
        } else {
            // Thanh toán thất bại
            $errorMessage = $this->getVNPayErrorMessage($vnpResponseCode);
              
            return redirect()->route('payment.index')->withErrors(['Thanh toán thất bại: ' . $errorMessage]);
        }
    }

    private function createVNPayUrl($amount, $orderId, $orderInfo)
    {
        $vnpUrl = $this->vnpayUrl;
        $vnpTmnCode = $this->vnpayTmnCode;
        $vnpHashSecret = $this->vnpayHashSecret;
        $vnpReturnUrl = $this->vnpayReturnUrl;

        $vnpTxnRef = $orderId;
        $vnpOrderInfo = $orderInfo;
        $vnpOrderType = 'billpayment';
        $vnpAmount = $amount * 100; // VNPay yêu cầu số tiền nhân 100
        $vnpLocale = 'vn';
        $vnpCurrCode = 'VND';
        $vnpIpAddr = request()->ip();
        $vnpCreateDate = date('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnpTmnCode,
            "vnp_Amount" => $vnpAmount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnpCreateDate,
            "vnp_CurrCode" => $vnpCurrCode,
            "vnp_IpAddr" => $vnpIpAddr,
            "vnp_Locale" => $vnpLocale,
            "vnp_OrderInfo" => $vnpOrderInfo,
            "vnp_OrderType" => $vnpOrderType,
            "vnp_ReturnUrl" => $vnpReturnUrl,
            "vnp_TxnRef" => $vnpTxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnpUrl = $vnpUrl . "?" . $query;
        if (isset($vnpHashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnpHashSecret);
            $vnpUrl .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnpUrl;
    }

    private function verifyVNPayHash($data)
    {
        $vnpHashSecret = $this->vnpayHashSecret;
        $vnpSecureHash = $data['vnp_SecureHash'];

        unset($data['vnp_SecureHash']);
        unset($data['vnp_SecureHashType']);

        ksort($data);
        $hashData = "";
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnpHashSecret);
        return $secureHash === $vnpSecureHash;
    }

    private function getVNPayErrorMessage($responseCode)
    {
        $errorMessages = [
            '00' => 'Giao dịch thành công',
            '01' => 'Giao dịch chưa hoàn tất',
            '02' => 'Giao dịch bị lỗi',
            '04' => 'Giao dịch đảo (Khách hàng đã bị trừ tiền tại Ngân hàng nhưng GD chưa thành công ở VNPAY)',
            '05' => 'VNPAY đang xử lý',
            '06' => 'VNPAY đã gửi yêu cầu hoàn tiền sang Ngân hàng',
            '07' => 'Giao dịch bị nghi ngờ gian lận',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản bị khóa',
        ];

        return $errorMessages[$responseCode] ?? 'Mã lỗi không xác định: ' . $responseCode;
    }

    public function paymentHistory()
    {
        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }

        // Lấy lịch sử giao dịch
        $txRes = Http::withToken($token)->get($this->gatewayUrl . '/api/wallet/transactions/filter');
        $transactions = $txRes->successful() ? $txRes->json('transactions') : [];

        return view('payment.history', compact('transactions'));
    }
} 