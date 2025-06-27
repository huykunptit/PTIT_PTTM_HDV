@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Sửa Night Combo</h2>
    <form action="{{ route('admin.night-combos.update', $combo['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên Night Combo</label>
            <input type="text" name="name" class="form-control" value="{{ $combo['name'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" value="{{ $combo['price'] ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category ID</label>
            <input type="number" name="category_id" class="form-control" value="{{ $combo['category_id'] ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thời lượng (giờ)</label>
            <input type="number" name="duration_hours" class="form-control" value="{{ $combo['duration_hours'] ?? 0 }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Giờ bắt đầu</label>
            <input type="number" name="start_hour" class="form-control" value="{{ $combo['start_hour'] ?? 22 }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Giờ kết thúc</label>
            <input type="number" name="end_hour" class="form-control" value="{{ $combo['end_hour'] ?? 6 }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Metadata (JSON)</label>
            <textarea name="metadata" class="form-control">{{ $combo['metadata'] ?? '{}' }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.night-combos.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
