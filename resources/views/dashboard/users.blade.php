@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>User Management</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus"></i> Add User
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search users...">
                                <button class="btn btn-outline-secondary" type="button">Search</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select">
                                <option>All Roles</option>
                                <option>Admin</option>
                                <option>User</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['users']  as $user)
                                <tr>
                                    <td>{{ $user['id'] }}</td>
                                    <td>{{ $user['full_name'] ?? $user['username'] }}</td>
                                    <td>{{ $user['email'] }}</td>
                                    <td>{{ $user['phone'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user['role_id'] == 1 ? 'primary' : 'secondary' }}">
                                            {{ $user['role_id'] == 1 ? 'Admin' : 'User' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ ($user['status'] ?? 'active') == 'active' ? 'success' : 'warning' }}">
                                            {{ ($user['status'] ?? 'active') == 'active' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $user['created_at'] ?? '' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary edit-user-btn" 
                                            data-user='@json($user)'>Edit</button>
                                        
                                        <form method="POST" action="{{ route('api.users.delete', $user['id']) }}" 
                                              style="display: inline-block;" 
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($data['total']))
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            {{-- Example pagination, adjust as needed based on API response --}}
                            {{-- You may need to add more logic for real pagination --}}
                        </ul>
                    </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" id="phone">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" name="role_id" id="role" required>
                            <option value="">Select Role</option>
                            <option value="1">Admin</option>
                            <option value="2">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="mb-3">
                        <label for="edit_full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" id="edit_full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" id="edit_phone">
                    </div>
                    <div class="mb-3">
                        <label for="edit_role_id" class="form-label">Role</label>
                        <select class="form-select" name="role_id" id="edit_role_id" required>
                            <option value="1">Admin</option>
                            <option value="2">User</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit button click - chỉ để hiển thị modal và set action URL
    document.querySelectorAll('.edit-user-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const user = JSON.parse(this.getAttribute('data-user'));
            
            // Set form action URL
            document.getElementById('editUserForm').action = "{{ url('/api/account/users') }}/" + user.id + "/update";
            
            // Fill form data
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_full_name').value = user.full_name || user.username || '';
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_phone').value = user.phone || '';
            document.getElementById('edit_role_id').value = user.role_id;
            
            // Show modal
            var editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editModal.show();
        });
    });
});
</script>
@endsection