@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Sửa sản phẩm</h2>
    <form action="{{ route('admin.products.update', $product['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="{{ $product['name'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" value="{{ $product['price'] ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category ID</label>
            <input type="number" name="category_id" class="form-control" value="{{ $product['category_id'] ?? '' }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
