@extends('layouts.app')

@section('title', 'Quản lý máy')
@section('breadcrumb')
<li class="breadcrumb-item active">Máy</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-desktop me-2"></i>Danh sách máy</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#machineModal" onclick="openAddMachine()">
                <i class="fas fa-plus me-1"></i>Thêm máy
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="machinesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã máy</th>
                            <th>IP</th>
                            <th>Khu vực</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($machines as $m)
                        <tr data-id="{{ $m['id'] }}">
                            <td>{{ $m['id'] }}</td>
                            <td>{{ $m['code'] }}</td>
                            <td>{{ $m['ip_address'] }}</td>
                            <td>{{ $areas && isset($m['area_id']) ? collect($areas)->firstWhere('id', $m['area_id'])['name'] ?? '' : '' }}</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'available' => 'success',
                                        'in_use' => 'primary',
                                        'maintenance' => 'warning',
                                    ][$m['status']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ $m['status'] }}</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="openEditMachine({{ htmlspecialchars(json_encode($m), ENT_QUOTES, 'UTF-8') }})">Sửa</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteMachine({{ $m['id'] }})">Xóa</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa Máy -->
<div class="modal fade" id="machineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="machineForm" method="POST" action="{{ route('api.machine.machines.store') }}">
                @csrf
                <input type="hidden" name="id" id="machineId">
                <div class="modal-header">
                    <h5 class="modal-title" id="machineModalTitle">Thêm máy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mã máy</label>
                        <input type="text" class="form-control" name="code" id="machineCode" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">IP</label>
                        <input type="text" class="form-control" name="ip_address" id="machineIp" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Khu vực</label>
                        <select class="form-select" name="area_id" id="machineArea" required>
                            <option value="">Chọn khu vực</option>
                            @foreach($areas as $a)
                                <option value="{{ $a['id'] }}">{{ $a['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="status" id="machineStatus" required>
                            <option value="available">Sẵn sàng</option>
                            <option value="in_use">Đang sử dụng</option>
                            <option value="maintenance">Bảo trì</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const apiToken = '{{ session('token') }}';
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function openAddMachine() {
    document.getElementById('machineModalTitle').textContent = 'Thêm máy';
    const form = document.getElementById('machineForm');
    form.reset();
    document.getElementById('machineId').value = '';
    // Không dùng form submit, dùng fetch bên dưới
}

document.getElementById('machineForm').onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('machineId').value;
    const data = {
        code: document.getElementById('machineCode').value,
        ip_address: document.getElementById('machineIp').value,
        area_id: document.getElementById('machineArea').value,
        status: document.getElementById('machineStatus').value
    };
    const method = id ? 'PUT' : 'POST';
    const url = id
        ? '{{ env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') }}/api/machine/machines/' + id
        : '{{ env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') }}/api/machine/machines';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + apiToken
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.id || res.success) {
            location.reload();
        } else {
            alert(res.error || 'Có lỗi xảy ra');
        }
    })
    .catch(() => alert('Lỗi kết nối'));
};

function openEditMachine(machine) {
    document.getElementById('machineModalTitle').textContent = 'Sửa máy';
    const form = document.getElementById('machineForm');
    document.getElementById('machineId').value = machine.id;
    document.getElementById('machineCode').value = machine.code;
    document.getElementById('machineIp').value = machine.ip_address;
    document.getElementById('machineArea').value = machine.area_id;
    document.getElementById('machineStatus').value = machine.status;
    form.action = `/admin/machines/${machine.id}`;
    form.method = 'POST';
    // Thêm input _method nếu chưa có
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    } else {
        methodInput.value = 'PUT';
    }
    const modal = new bootstrap.Modal(document.getElementById('machineModal'));
    modal.show();
}

// Xóa máy: submit form ẩn
function deleteMachine(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa máy này?')) return;
    let form = document.getElementById('deleteMachineForm_' + id);
    if (!form) {
        form = document.createElement('form');
        form.id = 'deleteMachineForm_' + id;
        form.method = 'POST';
        form.action = `/admin/machines/${id}`;
        // Thêm input CSRF và _method
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
    }
    form.submit();
}
</script>
@endsection 