@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Quản lý người dùng</h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-3">Thêm người dùng</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Ngày sinh</th>
                <th>Địa chỉ</th>
                <th>Role</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @if(is_array($users) && count($users) > 0)
                @foreach($users as $user)
                <tr>
                    <td>{{ $user['id'] }}</td>
                    <td>{{ $user['full_name'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>{{ $user['phone'] }}</td>
                    <td>{{ $user['bod'] }}</td>
                    <td>{{ $user['address'] }}</td>
                    <td>{{ $user['role_id'] == 1 ? 'Admin' : 'User' }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user['id']) }}" class="btn btn-sm btn-primary">Sửa</a>
                        <form action="{{ route('admin.users.destroy', $user['id']) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">Không có người dùng nào</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection 