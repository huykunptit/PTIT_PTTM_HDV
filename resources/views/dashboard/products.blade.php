@extends('layouts.app')

@section('title', 'Quản lý sản phẩm')
@section('breadcrumb')
<li class="breadcrumb-item active">Sản phẩm</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-box me-2"></i>Danh sách sản phẩm</h4>
            <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Thêm sản phẩm</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Danh mục</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Chuột Gaming</td>
                            <td>250,000 VNĐ</td>
                            <td>50</td>
                            <td>Phụ kiện</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">Sửa</a>
                                <a href="#" class="btn btn-sm btn-outline-danger">Xóa</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Bàn phím cơ</td>
                            <td>800,000 VNĐ</td>
                            <td>20</td>
                            <td>Phụ kiện</td>
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