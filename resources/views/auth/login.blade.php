@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Login to Net Management System</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                   id="username" name="username" value="{{ old('username') }}" required autofocus>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="machine_id" class="form-label">Select Machine</label>
                            <select class="form-control @error('machine_id') is-invalid @enderror" 
                                    id="machine_id" name="machine_id" required>
                                <option value="">Choose a machine...</option>
                                <option value="1" {{ old('machine_id') == '1' ? 'selected' : '' }}>Machine 1</option>
                                <option value="2" {{ old('machine_id') == '2' ? 'selected' : '' }}>Machine 2</option>
                                <option value="3" {{ old('machine_id') == '3' ? 'selected' : '' }}>Machine 3</option>
                                <option value="4" {{ old('machine_id') == '4' ? 'selected' : '' }}>Machine 4</option>
                                <option value="5" {{ old('machine_id') == '5' ? 'selected' : '' }}>Machine 5</option>
                            </select>
                            @error('machine_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                        <p><a href="{{ route('password.request') }}">Forgot your password?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
