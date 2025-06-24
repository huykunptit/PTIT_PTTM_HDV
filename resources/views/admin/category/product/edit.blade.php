@extends('layouts.app')
@section('title', 'Edit Night Combo')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h3><i class="fas fa-edit"></i> Edit Night Combo</h3>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('products.updateCombo', $data['id']) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $data['name']) }}">
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $data['description']) }}</textarea>
                </div>
                <div class="mb-3">
                    <label>Price</label>
                    <input name="price" type="number" class="form-control" value="{{ old('price', $data['price']) }}">
                </div>
                <div class="mb-3">
                    <label>Stock</label>
                    <input name="stock" type="number" class="form-control" value="{{ old('stock', $data['stock']) }}">
                </div>
                <div class="mb-3">
                    <label>Duration Hours</label>
                    <input name="duration_hours" type="number" class="form-control" value="{{ old('duration_hours', $data['duration_hours']) }}">
                </div>
                <div class="mb-3">
                    <label>Start Hour</label>
                    <input name="start_hour" type="number" class="form-control" value="{{ old('start_hour', $data['start_hour'] ?? 22) }}">
                </div>
                <div class="mb-3">
                    <label>End Hour</label>
                    <input name="end_hour" type="number" class="form-control" value="{{ old('end_hour', $data['end_hour'] ?? 6) }}">
                </div>
                <div class="mb-3">
                    <label>Is Active</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', $data['is_active']) ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !old('is_active', $data['is_active']) ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
                    <form method="POST" action="{{ route('products.destroyCombo', $data['id']) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
