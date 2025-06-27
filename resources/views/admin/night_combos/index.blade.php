@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Quản lý Night Combo</h2>
    <a href="{{ route('admin.night_combos.create') }}" class="btn btn-success mb-3">Thêm Night Combo</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th>Danh mục</th>
                <th>Thời lượng</th>
                <th>Giờ bắt đầu</th>
                <th>Giờ kết thúc</th>
                <th>Ảnh</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($combos as $combo)
            <tr>
                <td>{{ $combo['id'] }}</td>
                <td>{{ $combo['name'] }}</td>
                <td>{{ $combo['description'] ?? '' }}</td>
                <td>{{ $combo['price'] ?? '' }}</td>
                <td>{{ $combo['stock'] ?? '' }}</td>
                <td>{{ $combo['category_id'] ?? '' }}</td>
                <td>{{ $combo['duration_hours'] ?? '' }}</td>
                <td>{{ $combo['start_hour'] ?? '' }}</td>
                <td>{{ $combo['end_hour'] ?? '' }}</td>
                <td>
                    @if(isset($combo['image_url']))
                        <img src="{{ $combo['image_url'] }}" alt="Ảnh" width="60">
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.night_combos.edit', $combo['id']) }}" class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('admin.night_combos.destroy', $combo['id']) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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