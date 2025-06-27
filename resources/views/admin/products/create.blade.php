@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Thêm sản phẩm</h2>
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category ID</label>
            <input type="number" name="category_id" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Tạo mới</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
