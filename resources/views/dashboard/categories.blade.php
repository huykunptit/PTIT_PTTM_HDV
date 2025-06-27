@extends('layouts.app')

@section('title', 'Quản lý danh mục')
@section('breadcrumb')
<li class="breadcrumb-item active">Danh mục</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách danh mục</h4>
            <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Thêm danh mục</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Phụ kiện</td>
                            <td>Đồ chơi, phụ kiện máy tính</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">Sửa</a>
                                <a href="#" class="btn btn-sm btn-outline-danger">Xóa</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Đồ uống</td>
                            <td>Các loại nước giải khát</td>
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