@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Sửa thông tin người dùng</h2>
    <form action="{{ route('admin.users.update', $user['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="full_name" class="form-control" value="{{ $user['full_name'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user['email'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $user['phone'] }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="bod" class="form-control" value="{{ $user['bod'] }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ $user['address'] }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select" required>
                <option value="1" {{ $user['role_id'] == 1 ? 'selected' : '' }}>Admin</option>
                <option value="2" {{ $user['role_id'] == 2 ? 'selected' : '' }}>User</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu (để trống nếu không đổi)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection 