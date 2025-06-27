@extends('layouts.app')

@section('title', 'Quản lý đơn hàng')
@section('breadcrumb')
<li class="breadcrumb-item active">Đơn hàng</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Danh sách đơn hàng</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1001</td>
                            <td>Nguyễn Văn A</td>
                            <td>2024-06-01 10:00</td>
                            <td>500,000 VNĐ</td>
                            <td><span class="badge bg-success">Hoàn thành</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">Xem</a>
                                <a href="#" class="btn btn-sm btn-outline-danger">Hủy</a>
                            </td>
                        </tr>
                        <tr>
                            <td>1002</td>
                            <td>Trần Thị B</td>
                            <td>2024-06-02 14:30</td>
                            <td>300,000 VNĐ</td>
                            <td><span class="badge bg-warning">Đang xử lý</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">Xem</a>
                                <a href="#" class="btn btn-sm btn-outline-danger">Hủy</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 