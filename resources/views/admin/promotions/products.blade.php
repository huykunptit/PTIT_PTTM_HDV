@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Sản phẩm thuộc Promotion #{{ $id }}</h2>
    <form action="{{ route('admin.promotions.products.add', $id) }}" method="POST" class="mb-3">
        @csrf
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label">Thêm Product ID</label>
                <input type="number" name="product_id" class="form-control" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Thêm sản phẩm</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product['id'] }}</td>
                <td>{{ $product['name'] ?? '' }}</td>
                <td>
                    <form action="{{ route('admin.promotions.products.remove', [$id, $product['id']]) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xoá sản phẩm này khỏi promotion?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Quay lại danh sách Promotion</a>
</div>
@endsection 