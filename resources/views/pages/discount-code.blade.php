@extends('layouts.app')

@section('title', 'Discount Code')

@push('styles')
<style>
    body {
        background: #eee;
        font-family: "Manrope", serif;
    }

    .card {
        width: 350px;
        padding: 10px;
        border-radius: 20px;
        background: orange;
        border: none;
        color: #fff;
        height: 350px;
        display: flex;
        flex-direction: column;
        position: relative;
        align-items: center;
        justify-content: center;
    }

    .container {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card h1 {
        font-size: 48px;
        margin-bottom: 0px;
        margin-top: 0;
    }

    .card i {
        font-size: 26px;
    }

    .coupon-code {
        margin-top: 24px;
        border-radius: 8px;
        padding: 4px 10px;
        border: 1px dashed #ffffff;
    }

    .card span {
        font-size: 28px;
    }

    .image {
        position: absolute;
        opacity: .1;
        left: 0;
        top: 0;
    }

    .image2 {
        position: absolute;
        bottom: 0;
        right: 0;
        opacity: .1;
    }

    .btn {
        text-decoration: none;
        background-color: white;
        font-size: 16px;
        color: #000;
        padding: 12px 30px;
        border-radius: 10px;
        margin-top: 15px;
    }

    .btn:hover {
        background-color: #000;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-center container">
    <div class="d-flex card text-center">
        <div class="image">
            <img src="https://i.imgur.com/DC94rZe.png" width="150" alt="Decoration">
        </div>
        <div class="image2">
            <img src="https://i.imgur.com/DC94rZe.png" width="150" alt="Decoration">
        </div>
        <h1>30% OFF</h1>
        <span class="d-block">On Premium</span>
        <span class="d-block">Product</span>
        <div class="coupon-code mt-4">
            <small>Use Coupon code: upgrade30</small>
        </div>
        <a href="#" class="btn">Get Now</a>
    </div>
</div>
@endsection 