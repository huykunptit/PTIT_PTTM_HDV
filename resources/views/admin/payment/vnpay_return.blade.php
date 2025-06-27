@extends('layouts.app')
@section('title', 'Kết quả thanh toán VNPay')
@section('content')
<div class="container">
    <div class="alert alert-success">
        <h4>{{ $message ?? 'Quý khách vui lòng không tắt trình duyệt cho đến khi nhận được kết quả giao dịch trên website.' }}</h4>
        <p>Trường hợp đã thanh toán nhưng chưa nhận kết quả giao dịch, vui lòng bấm <a href="{{ route('admin.payment.vnpay_return') }}">Tại đây</a> để nhận kết quả. Xin cảm ơn!</p>
    </div>
</div>
@endsection 