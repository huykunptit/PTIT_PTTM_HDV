@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Thêm Area</h2>
    <form action="{{ route('admin.admin.areas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Giá mỗi giờ</label>
            <input type="number" name="price_per_hour" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Tạo mới</button>
        <a href="{{ route('admin.admin.areas.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection 