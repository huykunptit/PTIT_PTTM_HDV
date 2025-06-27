@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Sửa danh mục</h2>
    <form action="{{ route('admin.categories.update', $category['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên danh mục</label>
            <input type="text" name="name" class="form-control" value="{{ $category['name'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ $category['description'] ?? '' }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection 