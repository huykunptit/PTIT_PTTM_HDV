@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Danh sách máy theo khu vực</h2>
    <form method="GET" class="row g-3 mb-4">
        <div class="col-auto">
            <select name="area_id" class="form-select">
                <option value="">Tất cả khu vực</option>
                @foreach($areas as $a)
                    <option value="{{ $a['id'] }}" {{ $selectedArea == $a['id'] ? 'selected' : '' }}>{{ $a['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="available" {{ $selectedStatus == 'available' ? 'selected' : '' }}>Chưa dùng</option>
                <option value="in_use" {{ $selectedStatus == 'in_use' ? 'selected' : '' }}>Đang dùng</option>
                <option value="maintenance" {{ $selectedStatus == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </form>
    @foreach($areaMachines as $group)
        <div class="mb-4">
            <h4>{{ $group['area']['name'] }}</h4>
            <div class="row">
                @php
                    $machineList = isset($group['machines']['machines']) && is_array($group['machines']['machines']) ? $group['machines']['machines'] : [];
                @endphp
                @forelse($machineList as $m)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $m['code'] }}</h5>
                                <p class="card-text mb-1"><strong>IP:</strong> {{ $m['ip_address'] }}</p>
                                <p class="card-text mb-2"><strong>Mã máy:</strong> {{ $m['code'] }}</p>
                                @php
                                    $statusMap = [
                                        'available' => ['text' => 'Chưa dùng', 'class' => 'secondary'],
                                        'in_use' => ['text' => 'Đang dùng', 'class' => 'success'],
                                        'maintenance' => ['text' => 'Bảo trì', 'class' => 'warning'],
                                    ];
                                    $status = $statusMap[$m['status']] ?? ['text' => $m['status'], 'class' => 'dark'];
                                @endphp
                                <button class="btn btn-{{ $status['class'] }} w-100" disabled>{{ $status['text'] }}</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted">Không có máy nào trong khu vực này.</div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection 