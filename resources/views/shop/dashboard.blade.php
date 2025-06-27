@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- User Information Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr(session('user')['full_name'], 0, 1) }}
                        </div>
                        <h5 class="mt-2">{{ session('user')['full_name'] }}</h5>
                        <p class="text-muted">{{ session('account')['username'] }}</p>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ number_format(session('playtime')['remaining_minutes']) }}</h4>
                                <small class="text-muted">Minutes Left</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ number_format(session('playtime')['total_minutes']) }}</h4>
                            <small class="text-muted">Total Minutes</small>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Status:</span>
                            <span class="badge {{ session('playtime')['is_active'] ? 'bg-success' : 'bg-danger' }}">
                                {{ session('playtime')['is_active'] ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <form action="{{ route('logout.custom') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart fa-3x text-primary mb-3"></i>
                            <h5>Order Products</h5>
                            <p class="text-muted">Browse and order food, drinks, and snacks</p>
                            <a href="{{ route('shop.products') }}" class="btn btn-primary">Go to Shop</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-comments fa-3x text-success mb-3"></i>
                            <h5>Chat Support</h5>
                            <p class="text-muted">Get help from our support team</p>
                            <button class="btn btn-success" onclick="openChat()">Start Chat</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No recent orders
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chat Support</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="chat-container" style="height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;">
                    <div class="text-center text-muted">
                        <p>Welcome to our support chat!</p>
                        <p>How can we help you today?</p>
                    </div>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" id="chatMessage" placeholder="Type your message...">
                    <button class="btn btn-primary" type="button" onclick="sendMessage()">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-placeholder {
    background: linear-gradient(45deg, #007bff, #0056b3);
}
</style>

<script>
function openChat() {
    var chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
    chatModal.show();
}

function sendMessage() {
    var message = document.getElementById('chatMessage').value;
    if (message.trim() !== '') {
        // Add message to chat container
        var chatContainer = document.querySelector('.chat-container');
        var messageDiv = document.createElement('div');
        messageDiv.className = 'mb-2';
        messageDiv.innerHTML = '<strong>You:</strong> ' + message;
        chatContainer.appendChild(messageDiv);
        
        // Clear input
        document.getElementById('chatMessage').value = '';
        
        // Scroll to bottom
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
        // Simulate response (in real app, this would be from server)
        setTimeout(function() {
            var responseDiv = document.createElement('div');
            responseDiv.className = 'mb-2 text-muted';
            responseDiv.innerHTML = '<strong>Support:</strong> Thank you for your message. We will get back to you soon.';
            chatContainer.appendChild(responseDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }, 1000);
    }
}

// Allow Enter key to send message
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('chatMessage').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});
</script>
@endsection 