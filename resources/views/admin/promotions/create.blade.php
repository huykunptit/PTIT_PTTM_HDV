@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Thêm Promotion</h2>
    <form action="{{ route('admin.promotions.store') }}" method="POST">
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
            <label class="form-label">Phần trăm giảm (%)</label>
            <input type="number" name="discount_percent" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày bắt đầu</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày kết thúc</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kích hoạt?</label>
            <input type="checkbox" name="is_active" value="1">
        </div>
        <button type="submit" class="btn btn-success">Tạo mới</button>
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection 