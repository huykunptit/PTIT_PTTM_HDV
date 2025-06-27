@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Quản lý Area</h2>
    <a href="{{ route('admin.admin.areas.create') }}" class="btn btn-success mb-3">Thêm Area</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($areas as $area)
            <tr>
                <td>{{ $area['id'] }}</td>
                <td>{{ $area['name'] }}</td>
                <td>{{ $area['description'] ?? '' }}</td>
                <td>
                    <a href="{{ route('admin.admin.areas.edit', $area['id']) }}" class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('admin.admin.areas.destroy', $area['id']) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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