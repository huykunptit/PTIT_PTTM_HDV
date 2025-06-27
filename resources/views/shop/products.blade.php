@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('breadcrumb')
<li class="breadcrumb-item active">Sản phẩm</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Sản phẩm</h1>
                    <p class="text-muted mb-0">Khám phá các sản phẩm hấp dẫn</p>
                </div>
                <div class="text-end">
                    <p class="text-muted mb-0">Tìm thấy <span id="product-count" class="fw-semibold">0</span> sản phẩm</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="search-input" 
                                       class="form-control" 
                                       placeholder="Tìm kiếm sản phẩm...">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="col-md-2">
                            <select id="category-filter" class="form-select">
                                <option value="">Tất cả danh mục</option>
                                <!-- Categories will be loaded from API -->
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="col-md-2">
                            <select id="sort-select" class="form-select">
                                <option value="name">Tên A-Z</option>
                                <option value="price-low">Giá thấp-cao</option>
                                <option value="price-high">Giá cao-thấp</option>
                                <option value="newest">Mới nhất</option>
                                <option value="category">Theo danh mục</option>
                            </select>
                        </div>

                        <!-- View Toggle & Cart -->
                        <div class="col-md-2">
                            <div class="d-flex gap-2">
                                <div class="btn-group" role="group">
                                    <button id="grid-view" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-th"></i>
                                    </button>
                                    <button id="list-view" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-list"></i>
                                    </button>
                                </div>
                                
                                <button id="cart-toggle" 
                                        class="btn btn-primary btn-sm position-relative"
                                        onclick="toggleCart()">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                                        0
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        <div class="col-12">
            <div id="products-container" class="row g-4">
                <!-- Products will be loaded here -->
            </div>

            <!-- Loading State -->
            <div id="loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Đang tải sản phẩm...</p>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="text-center py-5" style="display: none;">
                <div class="text-muted mb-4">
                    <i class="fas fa-box-open fa-4x"></i>
                </div>
                <h3 class="h5 text-muted mb-2">Không tìm thấy sản phẩm</h3>
                <p class="text-muted">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
            </div>
        </div>
    </div>
</div>

<!-- Cart Sidebar -->
<div id="cart-sidebar" class="offcanvas offcanvas-end" tabindex="-1" style="width: 400px;">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title">
            <i class="fas fa-shopping-cart me-2"></i>
            Giỏ hàng (<span id="cart-item-count">0</span>)
        </h5>
        <button type="button" class="btn-close btn-close-white" onclick="toggleCart()"></button>
    </div>
    
    <div class="offcanvas-body p-0">
        <!-- Cart Items -->
        <div id="cart-items" class="p-3" style="max-height: 400px; overflow-y: auto;">
            <!-- Cart items will be loaded here -->
        </div>

        <!-- Cart Summary -->
        <div id="cart-summary" class="border-top p-3" style="display: none;">
            <!-- Selection Options -->
            <div class="mb-4">
                <h6 class="fw-semibold text-muted mb-3">Tùy chọn</h6>
                
                <!-- Delivery Option -->
                <div class="mb-3">
                    <label class="form-label small fw-medium text-muted">Phương thức nhận hàng:</label>
                    <div class="mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery" id="delivery-pickup" value="pickup" checked>
                            <label class="form-check-label small" for="delivery-pickup">
                                <i class="fas fa-store me-1"></i>Nhận tại quầy
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery" id="delivery-delivery" value="delivery">
                            <label class="form-check-label small" for="delivery-delivery">
                                <i class="fas fa-truck me-1"></i>Giao đến bàn
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-3">
                    <label class="form-label small fw-medium text-muted">Phương thức thanh toán:</label>
                    <div class="mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="payment-cash" value="cash" checked>
                            <label class="form-check-label small" for="payment-cash">
                                <i class="fas fa-money-bill me-1"></i>Tiền mặt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="payment-card" value="card">
                            <label class="form-check-label small" for="payment-card">
                                <i class="fas fa-credit-card me-1"></i>Thẻ tín dụng
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="payment-wallet" value="wallet">
                            <label class="form-check-label small" for="payment-wallet">
                                <i class="fas fa-wallet me-1"></i>Ví điện tử
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Special Instructions -->
                <div class="mb-3">
                    <label for="special-instructions" class="form-label small fw-medium text-muted">Ghi chú:</label>
                    <textarea id="special-instructions" 
                              class="form-control form-control-sm" 
                              rows="2" 
                              placeholder="Ghi chú đặc biệt cho đơn hàng..."></textarea>
                </div>
            </div>

            <!-- Promotion Code -->
            <div class="mb-4">
                <label class="form-label small fw-medium text-muted">Mã khuyến mãi:</label>
                <div class="input-group input-group-sm">
                    <input type="text" id="promotion-code" 
                           class="form-control" 
                           placeholder="Nhập mã khuyến mãi">
                    <button onclick="applyPromotionCode()" 
                            class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
                <div id="promotion-message" class="mt-1 small" style="display: none;"></div>
            </div>

            <!-- Applied Promotion -->
            <div id="applied-promotion" class="mb-4" style="display: none;">
                <div class="alert alert-success alert-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 small fw-medium" id="promotion-description"></p>
                            <p class="mb-0 small opacity-75" id="promotion-code-display"></p>
                        </div>
                        <button onclick="removePromotion()" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Total -->
            <div class="card bg-light mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Tạm tính:</span>
                        <span id="cart-subtotal" class="fw-medium">0 VNĐ</span>
                    </div>
                    
                    <div id="discount-row" class="d-flex justify-content-between mb-2" style="display: none;">
                        <span class="small text-muted">Giảm giá:</span>
                        <span id="cart-discount" class="fw-medium text-success">-0 VNĐ</span>
                    </div>
                    
                    <hr class="my-2">
                    
                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold">Tổng cộng:</span>
                        <span id="cart-total" class="fw-bold text-primary fs-5">0 VNĐ</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2">
                <button onclick="proceedToCheckout()" 
                        class="btn btn-primary btn-lg">
                    <i class="fas fa-credit-card me-2"></i>
                    Thanh toán
                </button>
                <div class="row g-2">
                    <div class="col-6">
                        <button onclick="clearCart()" 
                                class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-1"></i>Xóa giỏ
                        </button>
                    </div>
                    <div class="col-6">
                        <button onclick="saveForLater()" 
                                class="btn btn-outline-secondary w-100">
                            <i class="fas fa-bookmark me-1"></i>Lưu sau
                        </button>
                    </div>
                </div>
                <button onclick="debugCart()" 
                        class="btn btn-outline-info btn-sm">
                    <i class="fas fa-bug me-1"></i>Debug Cart
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Toast -->
<div id="notification" class="position-fixed top-0 end-0 p-3" style="z-index: 9999; display: none;">
    <div class="toast show" role="alert">
        <div class="toast-header">
            <i class="fas fa-info-circle me-2"></i>
            <strong class="me-auto" id="notification-title">Thông báo</strong>
            <button type="button" class="btn-close" onclick="hideNotification()"></button>
        </div>
        <div class="toast-body" id="notification-message">
            <!-- Notification message -->
        </div>
    </div>
</div>

<style>
.cart-item {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.product-card {
    transition: transform 0.2s ease-in-out;
}

.product-card:hover {
    transform: translateY(-2px);
}

/* Custom styles for better Bootstrap integration */
.offcanvas {
    border-left: 1px solid #dee2e6;
}

.card {
    border: 1px solid #e9ecef;
    transition: all 0.2s ease-in-out;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn {
    transition: all 0.2s ease-in-out;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.toast {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>

<script>
let products = [];
let categories = [];
let cart = { items: [], total_amount: 0, total_items: 0 };
let appliedPromotion = null;

// Load products on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    loadProducts();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    // Search
    document.getElementById('search-input').addEventListener('input', filterProducts);
    
    // Category filter
    document.getElementById('category-filter').addEventListener('change', filterProducts);
    
    // Sort
    document.getElementById('sort-select').addEventListener('change', filterProducts);
    
    // View toggle
    document.getElementById('grid-view').addEventListener('click', () => setView('grid'));
    document.getElementById('list-view').addEventListener('click', () => setView('list'));
}

// Load categories from API
async function loadCategories() {
    try {
        const response = await fetch('/api/categories', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            categories = await response.json();
            renderCategories();
        } else {
            console.error('Failed to load categories');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

// Render categories in filter dropdown
function renderCategories() {
    const categoryFilter = document.getElementById('category-filter');
    
    // Keep the "Tất cả danh mục" option
    categoryFilter.innerHTML = '<option value="">Tất cả danh mục</option>';
    
    // Add categories from API
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        categoryFilter.appendChild(option);
    });
}

// Load products from API
async function loadProducts() {
    try {
        const response = await fetch('/api/products', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            products = await response.json();
            console.log('Products loaded:', products.length);
            renderProducts(products);
        } else {
            console.error('Failed to load products');
            showNotification('Không thể tải sản phẩm', 'error');
        }
    } catch (error) {
        console.error('Error loading products:', error);
        showNotification('Lỗi kết nối', 'error');
    }
}

// Render products
function renderProducts(productsToRender) {
    const container = document.getElementById('products-container');
    const loading = document.getElementById('loading');
    const emptyState = document.getElementById('empty-state');
    const productCount = document.getElementById('product-count');

    loading.style.display = 'none';
    productCount.textContent = productsToRender.length;

    if (productsToRender.length === 0) {
        emptyState.style.display = 'block';
        container.innerHTML = '';
        return;
    }

    emptyState.style.display = 'none';
    container.innerHTML = productsToRender.map(product => {
        // Get category name
        const category = categories.find(c => c.id === product.category_id);
        const categoryName = category ? category.name : 'Không phân loại';
        
        return `
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card product-card h-100 shadow-sm">
                    <img src="${product.image || 'https://via.placeholder.com/300x200'}" 
                         alt="${product.name}" 
                         class="card-img-top" style="height: 200px; object-fit: cover;">
                    
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary text-white">
                                ${categoryName}
                            </span>
                            <small class="text-muted">Còn ${product.stock} sản phẩm</small>
                        </div>
                        
                        <h5 class="card-title mb-2">${product.name}</h5>
                        <p class="card-text text-muted small mb-3 flex-grow-1">${product.description || ''}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 text-primary mb-0">${formatCurrency(product.price)}</span>
                        </div>
                        
                        <button onclick="addToCart(${product.id})" 
                                class="btn btn-primary w-100">
                            <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Filter and sort products
function filterProducts() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const categoryFilter = document.getElementById('category-filter').value;
    const sortBy = document.getElementById('sort-select').value;

    let filteredProducts = products.filter(product => {
        const matchesSearch = product.name.toLowerCase().includes(searchTerm) || 
                            (product.description && product.description.toLowerCase().includes(searchTerm));
        const matchesCategory = !categoryFilter || product.category_id == categoryFilter;
        return matchesSearch && matchesCategory;
    });

    // Sort products
    filteredProducts.sort((a, b) => {
        switch (sortBy) {
            case 'name':
                return a.name.localeCompare(b.name);
            case 'price-low':
                return a.price - b.price;
            case 'price-high':
                return b.price - a.price;
            case 'newest':
                return new Date(b.created_at) - new Date(a.created_at);
            case 'category':
                // Sort by category name
                const categoryA = categories.find(c => c.id === a.category_id)?.name || '';
                const categoryB = categories.find(c => c.id === b.category_id)?.name || '';
                return categoryA.localeCompare(categoryB);
            default:
                return 0;
        }
    });

    renderProducts(filteredProducts);
}

// Set view mode
function setView(mode) {
    const container = document.getElementById('products-container');
    const gridBtn = document.getElementById('grid-view');
    const listBtn = document.getElementById('list-view');

    if (mode === 'grid') {
        container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
        gridBtn.className = 'p-2 bg-blue-100 text-blue-600 rounded-md';
        listBtn.className = 'p-2 text-gray-400 hover:text-gray-600 rounded-md';
    } else {
        container.className = 'space-y-4';
        gridBtn.className = 'p-2 text-gray-400 hover:text-gray-600 rounded-md';
        listBtn.className = 'p-2 bg-blue-100 text-blue-600 rounded-md';
    }
}

// Add to cart (local state only)
function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    const existingItem = cart.items.find(item => item.product_id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
        existingItem.subtotal = (existingItem.quantity || 0) * (existingItem.price || 0);
    } else {
        const newItem = {
            product_id: productId,
            product: product,
            quantity: 1,
            price: parseFloat(product.price) || 0,
            subtotal: parseFloat(product.price) || 0
        };
        cart.items.push(newItem);
    }

    updateCartTotals();
    updateCartUI();
    showNotification('Đã thêm vào giỏ hàng');
    
    console.log('Added to cart:', {
        product: product.name,
        quantity: existingItem ? existingItem.quantity : 1,
        cartTotal: cart.total_amount
    });
}

// Update cart totals
function updateCartTotals() {
    // Calculate total amount from items
    cart.total_amount = cart.items.reduce((sum, item) => {
        const itemTotal = (item.quantity || 0) * (item.price || 0);
        return sum + itemTotal;
    }, 0);
    
    // Calculate total items
    cart.total_items = cart.items.reduce((sum, item) => sum + (item.quantity || 0), 0);
    
    // Apply promotion if exists
    if (appliedPromotion && appliedPromotion.discount_percent) {
        const discountPercent = parseFloat(appliedPromotion.discount_percent) || 0;
        cart.discount_amount = (cart.total_amount * discountPercent) / 100;
        cart.final_amount = cart.total_amount - cart.discount_amount;
    } else {
        cart.discount_amount = 0;
        cart.final_amount = cart.total_amount;
    }
    
    // Ensure all values are numbers
    cart.total_amount = parseFloat(cart.total_amount) || 0;
    cart.total_items = parseInt(cart.total_items) || 0;
    cart.discount_amount = parseFloat(cart.discount_amount) || 0;
    cart.final_amount = parseFloat(cart.final_amount) || 0;
    
    console.log('Cart totals updated:', {
        total_amount: cart.total_amount,
        total_items: cart.total_items,
        discount_amount: cart.discount_amount,
        final_amount: cart.final_amount
    });
}

// Debug cart state
function debugCart() {
    console.log('=== CART DEBUG ===');
    console.log('Cart items:', cart.items);
    console.log('Cart totals:', {
        total_amount: cart.total_amount,
        total_items: cart.total_items,
        discount_amount: cart.discount_amount,
        final_amount: cart.final_amount
    });
    console.log('Applied promotion:', appliedPromotion);
    console.log('==================');
}

// Update cart UI
function updateCartUI() {
    const badge = document.getElementById('cart-badge');
    const itemCount = document.getElementById('cart-item-count');
    const summary = document.getElementById('cart-summary');
    
    if (cart.total_items > 0) {
        badge.style.display = 'flex';
        badge.textContent = cart.total_items;
        itemCount.textContent = cart.total_items;
        summary.style.display = 'block';
        renderCartItems();
        updateCartSummary();
    } else {
        badge.style.display = 'none';
        itemCount.textContent = '0';
        summary.style.display = 'none';
        document.getElementById('cart-items').innerHTML = `
            <div class="text-center py-5">
                <div class="text-muted mb-3">
                    <i class="fas fa-shopping-cart fa-3x"></i>
                </div>
                <h5 class="text-muted">Giỏ hàng trống</h5>
                <p class="text-muted small mb-3">Chưa có sản phẩm nào trong giỏ hàng</p>
                <button onclick="loadSavedCart()" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-download me-1"></i>Tải giỏ hàng đã lưu
                </button>
            </div>
        `;
    }
    
    // Debug cart state
    debugCart();
}

// Render cart items
function renderCartItems() {
    const container = document.getElementById('cart-items');
    
    container.innerHTML = cart.items.map(item => {
        const itemSubtotal = (item.quantity || 0) * (item.price || 0);
        
        return `
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <img src="${item.product.image || 'https://via.placeholder.com/60x60'}" 
                                 alt="${item.product.name}" 
                                 class="rounded" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        </div>
                        
                        <div class="col">
                            <h6 class="mb-1 text-truncate">${item.product.name}</h6>
                            <p class="mb-0 text-muted small">${formatCurrency(item.price)}</p>
                        </div>
                        
                        <div class="col-auto">
                            <div class="input-group input-group-sm" style="width: 100px;">
                                <button onclick="updateCartQuantity(${item.product_id}, ${item.quantity - 1})" 
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="text" 
                                       class="form-control form-control-sm text-center" 
                                       value="${item.quantity}" 
                                       readonly>
                                <button onclick="updateCartQuantity(${item.product_id}, ${item.quantity + 1})" 
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-auto text-end">
                            <div class="fw-bold text-primary">${formatCurrency(itemSubtotal)}</div>
                            <button onclick="removeFromCart(${item.product_id})" 
                                    class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Update cart quantity
function updateCartQuantity(productId, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(productId);
        return;
    }

    const item = cart.items.find(item => item.product_id === productId);
    if (item) {
        item.quantity = parseInt(newQuantity) || 0;
        item.subtotal = (item.quantity || 0) * (item.price || 0);
        updateCartTotals();
        updateCartUI();
        
        console.log('Updated quantity:', {
            product: item.product.name,
            quantity: item.quantity,
            subtotal: item.subtotal
        });
    }
}

// Remove from cart
function removeFromCart(productId) {
    cart.items = cart.items.filter(item => item.product_id !== productId);
    updateCartTotals();
    updateCartUI();
    showNotification('Đã xóa khỏi giỏ hàng');
}

// Update cart summary
function updateCartSummary() {
    const subtotalElement = document.getElementById('cart-subtotal');
    const totalElement = document.getElementById('cart-total');
    const discountRow = document.getElementById('discount-row');
    const discountElement = document.getElementById('cart-discount');
    
    // Calculate totals
    const subtotal = cart.total_amount || 0;
    const discount = cart.discount_amount || 0;
    const finalTotal = cart.final_amount || subtotal;
    
    // Update display
    subtotalElement.textContent = formatCurrency(subtotal);
    totalElement.textContent = formatCurrency(finalTotal);
    
    if (discount > 0) {
        discountRow.style.display = 'flex';
        discountElement.textContent = `-${formatCurrency(discount)}`;
    } else {
        discountRow.style.display = 'none';
    }
}

// Format currency
function formatCurrency(amount) {
    // Handle NaN, null, undefined
    if (!amount || isNaN(amount)) {
        return '0 VNĐ';
    }
    
    // Convert to number if it's a string
    const numAmount = typeof amount === 'string' ? parseFloat(amount) : amount;
    
    // Check if it's a valid number
    if (isNaN(numAmount)) {
        return '0 VNĐ';
    }
    
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(numAmount);
}

// Apply promotion code
function applyPromotionCode() {
    const code = document.getElementById('promotion-code').value.trim().toUpperCase();
    if (!code) {
        showNotification('Vui lòng nhập mã khuyến mãi', 'error');
        return;
    }

    const validPromotions = {
        'SUMMER20': { discount_percent: 20, description: 'Giảm 20% mùa hè' },
        'FLASH30': { discount_percent: 30, description: 'Flash sale 30%' },
        'NEW15': { discount_percent: 15, description: 'Giảm 15% sản phẩm mới' }
    };

    if (validPromotions[code]) {
        appliedPromotion = { code, ...validPromotions[code] };
        updateCartTotals();
        updateCartUI();
        showAppliedPromotion();
        showNotification('Áp dụng khuyến mãi thành công');
        document.getElementById('promotion-code').value = '';
    } else {
        showNotification('Mã khuyến mãi không hợp lệ', 'error');
    }
}

// Show applied promotion
function showAppliedPromotion() {
    document.getElementById('applied-promotion').style.display = 'block';
    document.getElementById('promotion-description').textContent = appliedPromotion.description;
    document.getElementById('promotion-code-display').textContent = `Mã: ${appliedPromotion.code}`;
}

// Remove promotion
function removePromotion() {
    appliedPromotion = null;
    updateCartTotals();
    updateCartUI();
    document.getElementById('applied-promotion').style.display = 'none';
    showNotification('Đã xóa khuyến mãi');
}

// Clear cart
function clearCart() {
    if (!confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) return;
    
    cart = { items: [], total_amount: 0, total_items: 0 };
    appliedPromotion = null;
    updateCartUI();
    showNotification('Đã xóa toàn bộ giỏ hàng');
}

// Toggle cart sidebar
function toggleCart() {
    const sidebar = document.getElementById('cart-sidebar');
    const bsOffcanvas = new bootstrap.Offcanvas(sidebar);
    
    if (sidebar.classList.contains('show')) {
        bsOffcanvas.hide();
    } else {
        bsOffcanvas.show();
    }
}

// Proceed to checkout (temporarily disabled)
function proceedToCheckout() {
    if (cart.items.length === 0) {
        showNotification('Giỏ hàng trống', 'error');
        return;
    }

    // Get selected options
    const deliveryMethod = document.querySelector('input[name="delivery"]:checked')?.value || 'pickup';
    const paymentMethod = document.querySelector('input[name="payment"]:checked')?.value || 'cash';
    const specialInstructions = document.getElementById('special-instructions').value;

    // Show order summary
    const orderSummary = {
        items: cart.items,
        total_amount: cart.final_amount,
        delivery_method: deliveryMethod,
        payment_method: paymentMethod,
        special_instructions: specialInstructions,
        promotion_code: appliedPromotion ? appliedPromotion.code : null,
        discount_amount: cart.discount_amount
    };

    console.log('Order Summary:', orderSummary);
    
    // For now, just show a success message
    showNotification('Tính năng thanh toán sẽ được phát triển sau!', 'info');
    
    // You can uncomment this later when ready to implement checkout
    /*
    fetch('/shop/orders', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(orderSummary)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('Đặt hàng thành công!');
            clearCart();
            toggleCart();
            setTimeout(() => {
                window.location.href = '/shop/orders';
            }, 2000);
        } else {
            showNotification(result.message || 'Lỗi khi đặt hàng', 'error');
        }
    })
    .catch(error => {
        console.error('Error creating order:', error);
        showNotification('Lỗi kết nối', 'error');
    });
    */
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const messageElement = document.getElementById('notification-message');
    const titleElement = document.getElementById('notification-title');
    
    messageElement.textContent = message;
    
    // Update icon and title based on type
    const iconElement = notification.querySelector('.toast-header i');
    switch (type) {
        case 'success':
            iconElement.className = 'fas fa-check-circle me-2 text-success';
            titleElement.textContent = 'Thành công';
            break;
        case 'error':
            iconElement.className = 'fas fa-exclamation-circle me-2 text-danger';
            titleElement.textContent = 'Lỗi';
            break;
        case 'info':
            iconElement.className = 'fas fa-info-circle me-2 text-info';
            titleElement.textContent = 'Thông tin';
            break;
        default:
            iconElement.className = 'fas fa-info-circle me-2';
            titleElement.textContent = 'Thông báo';
    }
    
    notification.style.display = 'block';
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        hideNotification();
    }, 5000);
}

// Hide notification
function hideNotification() {
    const notification = document.getElementById('notification');
    notification.style.display = 'none';
}

// Save cart for later
function saveForLater() {
    if (cart.items.length === 0) {
        showNotification('Giỏ hàng trống', 'error');
        return;
    }
    
    // Save to localStorage
    localStorage.setItem('savedCart', JSON.stringify(cart));
    showNotification('Đã lưu giỏ hàng để sau');
}

// Load saved cart
function loadSavedCart() {
    const savedCart = localStorage.getItem('savedCart');
    if (savedCart) {
        try {
            const parsedCart = JSON.parse(savedCart);
            cart = parsedCart;
            updateCartTotals();
            updateCartUI();
            showNotification('Đã tải giỏ hàng đã lưu');
        } catch (error) {
            console.error('Error loading saved cart:', error);
        }
    }
}
</script>
@endsection 