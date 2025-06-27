@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron bg-light p-4 rounded">
                <h1 class="display-4">Welcome to Net Shop!</h1>
                <p class="lead">Order food and drinks while you game. Fast delivery to your machine.</p>
                <hr class="my-4">
                <p>Browse our menu and add items to your cart.</p>
                <a class="btn btn-primary btn-lg" href="{{ route('shop.products') }}" role="button">View Menu</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Featured Products</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <img src="https://via.placeholder.com/200x150" class="card-img-top" alt="Product">
                                <div class="card-body">
                                    <h5 class="card-title">Coca Cola</h5>
                                    <p class="card-text">Refreshing soft drink</p>
                                    <p class="card-text"><strong>$2.00</strong></p>
                                    <button class="btn btn-primary btn-sm">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <img src="https://via.placeholder.com/200x150" class="card-img-top" alt="Product">
                                <div class="card-body">
                                    <h5 class="card-title">Pizza Slice</h5>
                                    <p class="card-text">Delicious pizza slice</p>
                                    <p class="card-text"><strong>$5.00</strong></p>
                                    <button class="btn btn-primary btn-sm">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <img src="https://via.placeholder.com/200x150" class="card-img-top" alt="Product">
                                <div class="card-body">
                                    <h5 class="card-title">French Fries</h5>
                                    <p class="card-text">Crispy french fries</p>
                                    <p class="card-text"><strong>$3.50</strong></p>
                                    <button class="btn btn-primary btn-sm">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('shop.products') }}" class="btn btn-primary">Browse All Products</a>
                        <a href="{{ route('shop.cart') }}" class="btn btn-success">View Cart</a>
                        <a href="{{ route('shop.orders') }}" class="btn btn-info">My Orders</a>
                        <a href="{{ route('shop.profile') }}" class="btn btn-secondary">My Profile</a>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Your Cart</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">No items in cart</p>
                    <a href="{{ route('shop.cart') }}" class="btn btn-outline-primary btn-sm">View Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 