@extends('layouts.app')

@section('title', 'Register - Cyber Game')

@section('content')
<div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 ">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Đây là danh sách tất cả các thiết bị đang đăng nhập vào tài khoản của bạn.
                    </div>

                    <div id="sessionsContainer">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Đang tải...</span>
                            </div>
                            <p class="mt-2">Đang tải danh sách phiên đăng nhập...</p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 