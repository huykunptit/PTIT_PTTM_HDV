@extends('layouts.app')

@section('title', 'Báo cáo thống kê')
@section('breadcrumb')
<li class="breadcrumb-item active">Báo cáo</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-transparent">
            <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Báo cáo thống kê</h4>
        </div>
        <div class="card-body">
            <h5>Doanh thu tháng 6/2024</h5>
            <ul>
                <li>Tổng doanh thu: <span class="fw-bold text-success">12,000,000 VNĐ</span></li>
                <li>Số đơn hàng: <span class="fw-bold">120</span></li>
                <li>Số khách hàng mới: <span class="fw-bold">15</span></li>
            </ul>
            <hr>
            <h5>Sản phẩm bán chạy</h5>
            <ul>
                <li>Chuột Gaming - 50 chiếc</li>
                <li>Bàn phím cơ - 30 chiếc</li>
                <li>Nước ngọt - 40 lon</li>
            </ul>
        </div>
    </div>
</div>
@endsection 