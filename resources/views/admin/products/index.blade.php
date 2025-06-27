@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Danh sách sản phẩm</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-success mb-3">Thêm sản phẩm</a>
    <table class="table">
        <thead><tr><th>ID</th><th>Tên</th><th>Giá</th><th>Danh mục</th><th>Thao tác</th></tr></thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p['id'] }}</td>
                <td>{{ $p['name'] }}</td>
                <td>{{ number_format($p['price']) }}₫</td>
                <td>{{ $p['category_id'] }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $p['id']) }}" class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('admin.products.destroy', $p['id']) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
