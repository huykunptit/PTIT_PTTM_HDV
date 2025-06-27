@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Edit User</h3>
    <form method="POST" action="{{ route('admin.users.update', $user['id']) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="full_name" value="{{ $user['full_name'] ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ $user['email'] ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="tel" class="form-control" name="phone" value="{{ $user['phone'] ?? '' }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="role_id" required>
                <option value="1" {{ ($user['role_id'] ?? 2) == 1 ? 'selected' : '' }}>Admin</option>
                <option value="2" {{ ($user['role_id'] ?? 2) == 2 ? 'selected' : '' }}>User</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection 