@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Thêm danh mục mới</h2>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên danh mục</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Tạo mới</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection 