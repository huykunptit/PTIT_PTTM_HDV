@extends('layouts.app')
@section('title', 'Product Details')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h3><i class="fas fa-eye"></i> Product Details</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID</dt><dd class="col-sm-9">{{ $data['id'] }}</dd>
                <dt class="col-sm-3">Name</dt><dd class="col-sm-9">{{ $data['name'] }}</dd>
                <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $data['description'] }}</dd>
                <dt class="col-sm-3">Price</dt><dd class="col-sm-9">{{ $data['price'] }}</dd>
                <dt class="col-sm-3">Stock</dt><dd class="col-sm-9">{{ $data['stock'] }}</dd>
                <dt class="col-sm-3">Duration</dt><dd class="col-sm-9">{{ $data['duration_hours'] }}h</dd>
                <dt class="col-sm-3">Active</dt><dd class="col-sm-9">{!! $data['is_active'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</dd>
                <dt class="col-sm-3">Image</dt><dd class="col-sm-9">{{ $data['image'] }}</dd>
                <dt class="col-sm-3">Metadata</dt><dd class="col-sm-9"><pre>{{ json_encode($data['metadata'], JSON_PRETTY_PRINT) }}</pre></dd>
            </dl>
        </div>
    </div>
</div>
@endsection
