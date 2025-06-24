@extends('layouts.app')
@section('title', 'Night Combos')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h3><i class="fas fa-moon"></i> Night Combos</h3>
        </div>
        <div class="card-body">
            @if(isset($data) && count($data) > 0)
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $combo)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $combo['name'] }}</td>
                        <td>{{ $combo['price'] }}</td>
                        <td>{{ $combo['duration_hours'] }}</td>
                        <td>{{ $combo['start_hour'] }}</td>
                        <td>{{ $combo['end_hour'] }}</td>
                        <td>
                            <a href="{{ route('products.getCombo', $combo['id']) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('products.destroyCombo', $combo['id']) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center">No Night Combos found.</div>
            @endif
        </div>
    </div>
</div>
@endsection
