@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Thêm Night Combo</h2>
    <form action="{{ route('admin.night-combos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên Night Combo</label>
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
        <div class="mb-3">
            <label class="form-label">Thời lượng (giờ)</label>
            <input type="number" name="duration_hours" class="form-control" value="0">
        </div>
        <div class="mb-3">
            <label class="form-label">Giờ bắt đầu</label>
            <input type="number" name="start_hour" class="form-control" value="22">
        </div>
        <div class="mb-3">
            <label class="form-label">Giờ kết thúc</label>
            <input type="number" name="end_hour" class="form-control" value="6">
        </div>
        <div class="mb-3">
            <label class="form-label">Metadata (JSON)</label>
            <textarea name="metadata" class="form-control">{}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Tạo mới</button>
        <a href="{{ route('admin.night-combos.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
