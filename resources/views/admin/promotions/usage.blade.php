@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Usage Promotion #{{ $id }}</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Số lần dùng</th>
                <th>Thời gian dùng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usage as $item)
            <tr>
                <td>{{ $item['user'] ?? '' }}</td>
                <td>{{ $item['count'] ?? '' }}</td>
                <td>{{ $item['used_at'] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Quay lại danh sách Promotion</a>
</div>
@endsection 