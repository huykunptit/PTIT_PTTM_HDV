@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Thêm Máy</h2>
    <form action="{{ route('admin.machines.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Mã máy</label>
            <input type="text" name="code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">IP</label>
            <input type="text" name="ip_address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Khu vực</label>
            <select name="area_id" class="form-select" required>
                <option value="">Chọn khu vực</option>
                @foreach($areas as $a)
                    <option value="{{ $a['id'] }}">{{ $a['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select" required>
                <option value="available">available</option>
                <option value="in_use">in_use</option>
                <option value="maintenance">maintenance</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Tạo mới</button>
        <a href="{{ route('admin.admin.machines.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection 