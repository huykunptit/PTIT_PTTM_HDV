@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('breadcrumb')
<li class="breadcrumb-item active">Hồ sơ</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h4 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin cá nhân</h4>
                </div>
                <div class="card-body">
                    @if($profile)
                        <form method="POST" action="#" id="profileForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Họ tên</label>
                                <input type="text" class="form-control" name="full_name" value="{{ $profile['full_name'] ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" name="bod" value="{{ $profile['bod'] ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $profile['email'] ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone" value="{{ $profile['phone'] ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address" value="{{ $profile['address'] ?? '' }}">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Cập nhật</button>
                        </form>
                    @else
                        <div class="alert alert-warning">Không thể tải thông tin cá nhân.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 