@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Quản lý Promotion</h2>
    <a href="{{ route('admin.promotions.create') }}" class="btn btn-success mb-3">Thêm Promotion</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Phần trăm giảm</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($promotions as $promotion)
            <tr>
                <td>{{ $promotion['id'] }}</td>
                <td>{{ $promotion['name'] }}</td>
                <td>{{ $promotion['description'] ?? '' }}</td>
                <td>{{ $promotion['discount_percent'] ?? '' }}%</td>
                <td>{{ $promotion['start_date'] ?? '' }}</td>
                <td>{{ $promotion['end_date'] ?? '' }}</td>
                <td>{{ $promotion['is_active'] ? 'Đang hoạt động' : 'Ngừng' }}</td>
                <td>
                    <a href="{{ route('admin.promotions.edit', $promotion['id']) }}" class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('admin.promotions.destroy', $promotion['id']) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 