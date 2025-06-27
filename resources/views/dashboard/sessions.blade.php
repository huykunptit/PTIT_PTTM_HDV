@extends('layouts.app')

@section('title', 'Quản lý phiên chơi')
@section('breadcrumb')
<li class="breadcrumb-item active">Phiên chơi</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-clock me-2"></i>Danh sách phiên chơi</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người chơi</th>
                            <th>Máy</th>
                            <th>Bắt đầu</th>
                            <th>Kết thúc</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>501</td>
                            <td>Nguyễn Văn A</td>
                            <td>PC-01</td>
                            <td>2024-06-01 09:00</td>
                            <td>2024-06-01 11:00</td>
                            <td><span class="badge bg-success">Hoàn thành</span></td>
                        </tr>
                        <tr>
                            <td>502</td>
                            <td>Trần Thị B</td>
                            <td>PC-02</td>
                            <td>2024-06-02 13:00</td>
                            <td>-</td>
                            <td><span class="badge bg-warning">Đang chơi</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 