@extends('layouts.app')
@section('title', 'Active Night Combos')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h3><i class="fas fa-check-circle"></i> Active Night Combos</h3>
        </div>
        <div class="card-body">
            @if(isset($data) && count($data) > 0)
            <table class="table table-striped table-hover">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Start</th>
                        <th>End</th>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center">No active Night Combos available.</div>
            @endif
        </div>
    </div>
</div>
@endsection
