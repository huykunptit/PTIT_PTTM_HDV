@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Quản lý Máy</h2>
    <a href="{{ route('admin.admin.machines.create') }}" class="btn btn-success mb-3">Thêm Máy</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã máy</th>
                <th>IP</th>
                <th>Khu vực</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($machines as $m)
            <tr>
                <td>{{ $m['id'] }}</td>
                <td>{{ $m['code'] }}</td>
                <td>{{ $m['ip_address'] }}</td>
                <td>{{ $areas && isset($m['area_id']) ? collect($areas)->firstWhere('id', $m['area_id'])['name'] ?? '' : '' }}</td>
                <td>{{ $m['status'] }}</td>
                <td>
                    <a href="{{ route('admin.admin.machines.edit', $m['id']) }}" class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('admin.machines.destroy', $m['id']) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 