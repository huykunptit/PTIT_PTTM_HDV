@extends('layouts.app')
@section('title', 'Add New Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus"></i> Add New Category
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-light btn-sm">
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

                    <form action="{{ route('categories.store') }}" method="POST" id="categoryForm" novalidate>
                        @csrf

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
                                           value="{{ old('name') }}" 
                                           placeholder="Enter category name"
                                           required 
                                           maxlength="255">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <span id="nameCounter">0</span>/255 characters
                                    </small>
                                </div>

                                {{-- Description --}}
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left"></i> Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Enter category description (optional)"
                                              maxlength="255">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <span id="descCounter">0</span>/255 characters
                                    </small>
                                </div>

                                {{-- Parent Category --}}
                                <div class="form-group mb-4">
                                    <label for="parent_id" class="form-label">
                                        <i class="fas fa-sitemap"></i> Parent Category
                                    </label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror" 
                                            id="parent_id" 
                                            name="parent_id">
                                        <option value="">-- No Parent (Root Category) --</option>
                                        {{-- Categories will be loaded via JavaScript --}}
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Select a parent category to create a subcategory
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                {{-- Category Preview Card --}}
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-eye"></i> Preview
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <strong>Name:</strong>
                                            <span id="previewName" class="text-muted">Enter name above</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Description:</strong>
                                            <span id="previewDesc" class="text-muted">No description</span>
                                        </div>
                                        <div>
                                            <strong>Parent:</strong>
                                            <span id="previewParent" class="text-muted">Root category</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Tips --}}
                                <div class="card bg-info text-white mt-3">
                                    <div class="card-body">
                                        <h6><i class="fas fa-lightbulb"></i> Tips</h6>
                                        <small>
                                            • Choose a clear, descriptive name<br>
                                            • Add a brief description to help users<br>
                                            • Use parent categories to organize hierarchically
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                                <i class="fas fa-plus"></i> Create Category
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-lg" id="resetBtn">
                                                <i class="fas fa-undo"></i> Reset
                                            </button>
                                        </div>
                                        <div>
                                            <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-lg">
                                                <i class="fas fa-times"></i> Cancel
                                            </a>
                                        </div>
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

{{-- Loading Modal --}}
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">Creating category...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .required::after {
        content: " *";
        color: #dc3545;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }
    
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .btn-lg {
        padding: 10px 20px;
        font-size: 16px;
    }
    
    #previewName, #previewDesc, #previewParent {
        display: block;
        margin-top: 5px;
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('categoryForm');
    const nameInput = document.getElementById('name');
    const descInput = document.getElementById('description');
    const parentSelect = document.getElementById('parent_id');
    const submitBtn = document.getElementById('submitBtn');
    const resetBtn = document.getElementById('resetBtn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    
    // Character counters
    function updateCharCounter(input, counterId) {
        const counter = document.getElementById(counterId);
        if (counter) {
            counter.textContent = input.value.length;
            
            const maxLength = input.getAttribute('maxlength');
            if (input.value.length > maxLength * 0.8) {
                counter.style.color = '#dc3545';
            } else if (input.value.length > maxLength * 0.6) {
                counter.style.color = '#ffc107';
            } else {
                counter.style.color = '#6c757d';
            }
        }
    }
    
    // Initialize character counters
    updateCharCounter(nameInput, 'nameCounter');
    updateCharCounter(descInput, 'descCounter');
    
    // Update counters on input
    nameInput.addEventListener('input', () => updateCharCounter(nameInput, 'nameCounter'));
    descInput.addEventListener('input', () => updateCharCounter(descInput, 'descCounter'));
    
    // Preview updates
    function updatePreview() {
        document.getElementById('previewName').textContent = nameInput.value || 'Enter name above';
        document.getElementById('previewDesc').textContent = descInput.value || 'No description';
        
        const selectedOption = parentSelect.options[parentSelect.selectedIndex];
        document.getElementById('previewParent').textContent = selectedOption.text === '-- No Parent (Root Category) --' 
            ? 'Root category' 
            : selectedOption.text;
    }
    
    // Update preview on input
    nameInput.addEventListener('input', updatePreview);
    descInput.addEventListener('input', updatePreview);
    parentSelect.addEventListener('change', updatePreview);
    
    // Load parent categories
    async function loadParentCategories() {
        try {
            const response = await fetch('{{ env("GATEWAY_URL") }}/api/category/categories');
            const data = await response.json();
            
            if (Array.isArray(data)) {
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    parentSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading parent categories:', error);
        }
    }
    
    // Reset form
    resetBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the form? All changes will be lost.')) {
            form.reset();
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            updateCharCounter(nameInput, 'nameCounter');
            updateCharCounter(descInput, 'descCounter');
            updatePreview();
        }
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        loadingModal.show();
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
    });
    
    // Initialize
    loadParentCategories();
    updatePreview();
});
</script>
@endpush