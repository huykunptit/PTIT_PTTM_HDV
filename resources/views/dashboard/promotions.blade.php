@extends('layouts.app')

@section('title', 'Quản lý khuyến mãi')
@section('breadcrumb')
<li class="breadcrumb-item active">Khuyến mãi</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-percentage me-2"></i>Danh sách khuyến mãi</h4>
            <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Thêm khuyến mãi</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã</th>
                            <th>Mô tả</th>
                            <th>Giảm giá (%)</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>SUMMER20</td>
                            <td>Giảm 20% mùa hè</td>
                            <td>20</td>
                            <td>2024-06-01</td>
                            <td>2024-06-30</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">Sửa</a>
                                <a href="#" class="btn btn-sm btn-outline-danger">Xóa</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>FLASH30</td>
                            <td>Flash sale 30%</td>
                            <td>30</td>
                            <td>2024-06-10</td>
                            <td>2024-06-12</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">Sửa</a>
                                <a href="#" class="btn btn-sm btn-outline-danger">Xóa</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 