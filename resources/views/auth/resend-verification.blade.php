@extends('layouts.app')

@section('title', 'Register - Cyber Game')

@section('content')
<div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 ">
                <div class="card mb-0">
                    <div class="card-body">
                        <a href="{{ route('dashboard') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                            <img src="{{ asset('assets/images/logos/logo.webp') }}" alt="">
                        </a>
                        <p class="text-center">Your Social Campaigns</p>
                       <form method="POST" action="{{ route('auth.email.resend') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Resend Verification</button>
</form>
                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">Đã có tài khoản? 
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
                            </a>
                        </p>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 