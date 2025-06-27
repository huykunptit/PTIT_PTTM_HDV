@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Quản lý danh mục</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success mb-3">Thêm danh mục</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category['id'] }}</td>
                <td>{{ $category['name'] }}</td>
                <td>{{ $category['description'] ?? '' }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category['id']) }}" class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('admin.categories.destroy', $category['id']) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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