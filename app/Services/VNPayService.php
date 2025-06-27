<?php

namespace App\Services;

class VNPayService
{
    /**
     * Tạo URL thanh toán VNPay
     */
    public static function createPaymentUrl($orderId, $amount, $orderInfo, $returnUrl, $extraData = [])
    {
       
        $vnp_TmnCode = config('vnpay.tmn_code', env('VNPAY_TMN_CODE'));
        $vnp_HashSecret = config('vnpay.hash_secret', env('VNPAY_HASH_SECRET'));
        $vnp_Url = config('vnpay.url', env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'));
        $vnp_Returnurl = $returnUrl;

        $vnp_TxnRef = $orderId;
        $vnp_OrderInfo = $orderInfo;
        $vnp_OrderType = 'other';
        $vnp_Amount = $amount * 100; // VNPay yêu cầu số tiền * 100
        $vnp_Locale = 'vn';
        $vnp_BankCode = $extraData['bank_code'] ?? '';
        $vnp_IpAddr = request()->ip();
        $vnp_CreateDate = date('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        if ($vnp_BankCode != "") {
            $inputData["vnp_BankCode"] = $vnp_BankCode;
        }
        // Thêm extraData nếu có
        if (!empty($extraData['invoice'])) {
            $inputData['vnp_Invoice'] = $extraData['invoice'];
        }
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
        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        return $vnp_Url;
    }

    /**
     * Xác thực callback/return từ VNPay
     */
    public static function validateReturn($inputData)
    {
        $vnp_HashSecret = config('vnpay.hash_secret', env('VNPAY_HASH_SECRET'));
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        ksort($inputData);
        $hashData = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        return $secureHash === $vnp_SecureHash;
    }
} 