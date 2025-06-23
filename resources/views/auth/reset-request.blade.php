@extends('layouts.app')

@section('title', 'Register - Flexy Free Bootstrap Admin Template')

@section('content')
<div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 ">
                <div class="card mb-0">
                    <div class="card-body">
                         <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Nhập địa chỉ email của bạn để nhận liên kết đặt lại mật khẩu.
                    </div>

                    <form id="resetRequestForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Địa chỉ Email
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   required
                                   placeholder="Nhập địa chỉ email của bạn">
                            <div class="form-text">
                                Chúng tôi sẽ gửi liên kết đặt lại mật khẩu đến email này.
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-paper-plane"></i> Gửi liên kết đặt lại
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-arrow-left"></i> Quay lại đăng nhập
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-user-plus"></i> Đăng ký tài khoản
                                </a>
                            </div>
                        </div>
                    </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 