@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Sửa Máy</h2>
    <form action="{{ route('admin.machines.update', $machine['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Mã máy</label>
            <input type="text" name="code" class="form-control" value="{{ $machine['code'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">IP</label>
            <input type="text" name="ip_address" class="form-control" value="{{ $machine['ip_address'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Khu vực</label>
            <select name="area_id" class="form-select" required>
                <option value="">Chọn khu vực</option>
                @foreach($areas as $a)
                    <option value="{{ $a['id'] }}" {{ $machine['area_id'] == $a['id'] ? 'selected' : '' }}>{{ $a['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select" required>
                <option value="available" {{ $machine['status'] == 'available' ? 'selected' : '' }}>available</option>
                <option value="in_use" {{ $machine['status'] == 'in_use' ? 'selected' : '' }}>in_use</option>
                <option value="maintenance" {{ $machine['status'] == 'maintenance' ? 'selected' : '' }}>maintenance</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.admin.machines.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection 