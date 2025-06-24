@extends('layouts.app')
@section('title', 'Create Product')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-gradient bg-primary text-white rounded-top-4">
                    <h3 class="mb-0"><i class="fas fa-plus me-2"></i>Create Product</h3>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('products.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name</label>
                            <input name="name" class="form-control rounded-pill" required value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Price</label>
                                <input name="price" type="number" class="form-control rounded-pill" required value="{{ old('price') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Stock</label>
                                <input name="stock" type="number" class="form-control rounded-pill" required value="{{ old('stock') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Duration Hours</label>
                                <input name="duration_hours" type="number" class="form-control rounded-pill" required value="{{ old('duration_hours') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Start Hour</label>
                                <input name="start_hour" type="number" class="form-control rounded-pill" value="{{ old('start_hour', 22) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">End Hour</label>
                                <input name="end_hour" type="number" class="form-control rounded-pill" value="{{ old('end_hour', 6) }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-select rounded-pill" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category['id'] }}"
                                        {{ old('category_id') == $category['id'] ? 'selected' : '' }}>
                                        {{ $category['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                                <i class="fas fa-save me-2"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
