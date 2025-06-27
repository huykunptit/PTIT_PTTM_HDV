@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Thêm người dùng mới</h2>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="bod" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select" required>
                <option value="1">Admin</option>
                <option value="2">User</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Tạo mới</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection 