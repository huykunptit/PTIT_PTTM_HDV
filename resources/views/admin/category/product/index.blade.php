@extends('layouts.app')
@section('title', 'Product Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Product Management</h3>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Product
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($data) && count($data) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Duration</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $product)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['price'] }}</td>
                                    <td>{{ $product['stock'] }}</td>
                                    <td>{{ $product['duration_hours'] }}h</td>
                                    <td>{!! $product['is_active'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
                                    <td>
                                        <a href="{{ route('products.show', $product['id']) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('products.getCombo', $product['id']) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('products.destroyCombo', $product['id']) }}" method="POST" style="display:inline;" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-name="{{ $product['name'] }}"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center">No products found</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Are you sure you want to delete <strong id="productName"></strong>?</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@if(session('toast'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast text-bg-{{ session('toast.type') }} show">
        <div class="d-flex">
            <div class="toast-body">{{ session('toast.message') }}</div>
            <button class="btn-close" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
let formToSubmit;
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.onclick = () => {
        formToSubmit = btn.closest('form');
        document.getElementById('productName').innerText = btn.dataset.name;
        new bootstrap.Modal('#deleteModal').show();
    };
});
document.getElementById('confirmDelete').onclick = () => formToSubmit.submit();
</script>
@endpush
