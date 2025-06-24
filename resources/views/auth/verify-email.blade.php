@extends('layouts.app')

@section('title', 'Register - Cyber Game')

@section('content')
<div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 ">
                <div class="card mb-0">
                    <div class="card-body">
                        <form method="POST" action="{{ route('auth.email.verify') }}">
                            @csrf
                            <input type="text" name="token" placeholder="Token" required>
                            <button type="submit">Verify Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 