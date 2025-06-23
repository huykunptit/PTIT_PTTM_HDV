@extends('layouts.app')

@section('title', 'Category Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Category Management</h3>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Category
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($data) && count($data) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>STT</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $category)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $category['name'] ?? 'N/A' }}</td>
                                    <td>{{ $category['description'] ?? 'No description' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('categories.show', $category['id']) }}" 
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('categories.edit', $category['id']) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category['id']) }}" 
                                                  method="POST" 
                                                  style="display:inline;" 
                                                  class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm btn-delete" 
                                                        data-category-id="{{ $category['id'] }}"
                                                        data-category-name="{{ $category['name'] }}"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <h5>No categories found</h5>
                        <p class="text-muted">Click "Create Category" to add your first category.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p>You are about to delete the category: <strong id="categoryName"></strong></p>
                    <p class="text-muted">This action cannot be undone!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Toast --}}
@if(session('toast'))
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div class="toast align-items-center text-bg-{{ session('toast.type') }} border-0" 
         role="alert" 
         aria-live="assertive" 
         aria-atomic="true"
         id="toastMessage">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-{{ session('toast.type') == 'success' ? 'check-circle' : 'exclamation-circle' }}"></i>
                {{ session('toast.message') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .btn-group .btn {
        margin-right: 2px;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    .table th {
        vertical-align: middle;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let formToSubmit = null;
    let deleteModal = null;

    // Initialize modal
    const deleteModalElement = document.getElementById('deleteModal');
    if (deleteModalElement) {
        deleteModal = new bootstrap.Modal(deleteModalElement);
    }

    // Handle delete button clicks
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Get the form and category info
            formToSubmit = btn.closest('form');
            const categoryName = btn.getAttribute('data-category-name');
            const categoryId = btn.getAttribute('data-category-id');
            
            // Update modal content
            const categoryNameElement = document.getElementById('categoryName');
            if (categoryNameElement) {
                categoryNameElement.textContent = categoryName || 'Unknown Category';
            }
            
            // Show modal
            if (deleteModal) {
                deleteModal.show();
            } else {
                console.error('Delete modal not initialized');
            }
        });
    });

    // Handle confirm delete
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (formToSubmit) {
                // Add loading state
                confirmDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                confirmDeleteBtn.disabled = true;
                
                // Submit form
                formToSubmit.submit();
            } else {
                console.error('No form to submit');
            }
        });
    }

    // Reset modal state when hidden
    if (deleteModalElement) {
        deleteModalElement.addEventListener('hidden.bs.modal', function() {
            formToSubmit = null;
            const confirmBtn = document.getElementById('confirmDelete');
            if (confirmBtn) {
                confirmBtn.innerHTML = '<i class="fas fa-trash"></i> Delete';
                confirmBtn.disabled = false;
            }
        });
    }

    // Show toast if exists
    @if(session('toast'))
    const toastElement = document.getElementById('toastMessage');
    if (toastElement) {
        const toast = new bootstrap.Toast(toastElement, {
            delay: 5000 // Auto hide after 5 seconds
        });
        toast.show();
    }
    @endif
});
</script>
@endpush