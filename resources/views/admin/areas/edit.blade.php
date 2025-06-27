@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Sửa Area</h2>
    <form action="{{ route('admin.areas.update', $area['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên</label>
            <input type="text" name="name" class="form-control" value="{{ $area['name'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ $area['description'] ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Giá mỗi giờ</label>
            <input type="number" name="price_per_hour" class="form-control" value="{{ $area['price_per_hour'] }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.admin.areas.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection 