@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit"></i> Edit Category
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-dark btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    {{-- Display validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Loading spinner --}}
                    <div id="loadingSpinner" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading category data...</p>
                    </div>

                    <form action="{{ route('categories.update', $id) }}" method="POST" id="categoryForm" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                {{-- Category Name --}}
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label required">
                                        <i class="fas fa-tag"></i> Category Name
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', isset($category['name']) ? $category['name'] : '') }}" 
                                           placeholder="Enter category name"
                                           required 
                                           maxlength="255">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Maximum 255 characters</div>
                                </div>

                                {{-- Category Description --}}
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left"></i> Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Enter category description (optional)"
                                              maxlength="255">{{ old('description', isset($category['description']) ? $category['description'] : '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <span id="charCount">0</span>/255 characters
                                    </div>
                                </div>

                                {{-- Parent Category --}}
                                <div class="form-group mb-4">
                                    <label for="parent_id" class="form-label">
                                        <i class="fas fa-sitemap"></i> Parent Category
                                    </label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror" 
                                            id="parent_id" 
                                            name="parent_id">
                                        <option value="">-- Select Parent Category (Optional) --</option>
                                        {{-- Options will be loaded via JavaScript --}}
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave empty if this is a root category</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                {{-- Category Info Card --}}
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-info-circle"></i> Category Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <strong>Category ID:</strong> 
                                            <span class="badge bg-primary">{{ $id }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Status:</strong> 
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Created:</strong> 
                                            <span id="createdDate">{{ isset($category['created_at']) ? date('M d, Y', strtotime($category['created_at'])) : 'N/A' }}</span>
                                        </div>
                                        <div class="mb-0">
                                            <strong>Last Updated:</strong> 
                                            <span id="updatedDate">{{ isset($category['updated_at']) ? date('M d, Y', strtotime($category['updated_at'])) : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Actions --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-tools"></i> Quick Actions
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-outline-info btn-sm" id="previewBtn">
                                                <i class="fas fa-eye"></i> Preview Changes
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="resetBtn">
                                                <i class="fas fa-undo"></i> Reset Form
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-outline-danger" id="deleteBtn">
                                            <i class="fas fa-trash"></i> Delete Category
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{ route('categories.index') }}" class="btn btn-secondary me-2">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-warning" id="updateBtn">
                                            <i class="fas fa-save"></i> Update Category
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p>You are about to delete this category. This action cannot be undone!</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <small>All subcategories and related data will also be affected.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-eye"></i> Category Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-tag"></i> Category Name:</h6>
                        <p id="previewName" class="text-muted">-</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-sitemap"></i> Parent Category:</h6>
                        <p id="previewParent" class="text-muted">-</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="fas fa-align-left"></i> Description:</h6>
                        <p id="previewDescription" class="text-muted">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .required::after {
        content: " *";
        color: red;
    }
    
    .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    .form-select:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .badge {
        font-size: 0.85em;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('categoryForm');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const parentSelect = document.getElementById('parent_id');
    const charCountSpan = document.getElementById('charCount');
    const updateBtn = document.getElementById('updateBtn');
    const resetBtn = document.getElementById('resetBtn');
    const previewBtn = document.getElementById('previewBtn');
    const deleteBtn = document.getElementById('deleteBtn');
    
    let originalData = {};
    let categoriesData = [];

    // Load categories for parent selection
    async function loadCategories() {
        try {
            const response = await fetch('{{ route("api.category.list") }}');
            const data = await response.json();
            
            if (response.ok && Array.isArray(data)) {
                categoriesData = data;
                populateParentSelect();
            }
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    // Populate parent category select
    function populateParentSelect() {
        // Clear existing options except the first one
        while (parentSelect.children.length > 1) {
            parentSelect.removeChild(parentSelect.lastChild);
        }

        categoriesData.forEach(category => {
            // Don't include current category as parent option
            if (category.id != {{ $id }}) {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                
                // Set selected if this was the original parent
                if (category.id == {{ old('parent_id', isset($category['parent_id']) ? $category['parent_id'] : 'null') }}) {
                    option.selected = true;
                }
                
                parentSelect.appendChild(option);
            }
        });
    }

    // Character count for description
    function updateCharCount() {
        const count = descriptionInput.value.length;
        charCountSpan.textContent = count;
        
        if (count > 230) {
            charCountSpan.style.color = '#dc3545';
        } else if (count > 200) {
            charCountSpan.style.color = '#ffc107';
        } else {
            charCountSpan.style.color = '#6c757d';
        }
    }

    // Store original form data
    function storeOriginalData() {
        originalData = {
            name: nameInput.value,
            description: descriptionInput.value,
            parent_id: parentSelect.value
        };
    }

    // Reset form to original data
    function resetForm() {
        nameInput.value = originalData.name;
        descriptionInput.value = originalData.description;
        parentSelect.value = originalData.parent_id;
        updateCharCount();
        
        // Remove validation classes
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
    }

    // Preview changes
    function previewChanges() {
        document.getElementById('previewName').textContent = nameInput.value || '-';
        document.getElementById('previewDescription').textContent = descriptionInput.value || 'No description';
        
        const selectedParent = parentSelect.selectedOptions[0];
        document.getElementById('previewParent').textContent = selectedParent && selectedParent.value 
            ? selectedParent.textContent 
            : 'Root Category';
        
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        previewModal.show();
    }

    // Delete category
    function deleteCategory() {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
        
        document.getElementById('confirmDelete').addEventListener('click', function() {
            // Create and submit delete form
            const deleteForm = document.createElement('form');
            deleteForm.method = 'POST';
            deleteForm.action = '{{ route("categories.destroy", $id) }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            deleteForm.appendChild(csrfToken);
            deleteForm.appendChild(methodField);
            document.body.appendChild(deleteForm);
            deleteForm.submit();
        });
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        updateBtn.disabled = true;
    });

    // Event listeners
    descriptionInput.addEventListener('input', updateCharCount);
    resetBtn.addEventListener('click', resetForm);
    previewBtn.addEventListener('click', previewChanges);
    deleteBtn.addEventListener('click', deleteCategory);

    // Initialize
    loadCategories();
    updateCharCount();
    storeOriginalData();

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate name
        if (!nameInput.value.trim()) {
            nameInput.classList.add('is-invalid');
            isValid = false;
        } else {
            nameInput.classList.remove('is-invalid');
        }
        
        // Validate description length
        if (descriptionInput.value.length > 255) {
            descriptionInput.classList.add('is-invalid');
            isValid = false;
        } else {
            descriptionInput.classList.remove('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            updateBtn.innerHTML = '<i class="fas fa-save"></i> Update Category';
            updateBtn.disabled = false;
        }
    });
});
</script>
@endpush