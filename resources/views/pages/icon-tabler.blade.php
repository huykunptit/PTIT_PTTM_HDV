@extends('layouts.app')

@section('title', 'Flexy Free Bootstrap Admin Template by WrapPixel')

@push('styles')
<link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
@endpush

@section('content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <div class="card w-100 h-100 position-relative overflow-hidden">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Icons</h5>
                <iframe src="https://tabler-icons.io/" frameborder="0" style="height: calc(100vh - 250px); width: 100%;"
                    data-simplebar=""></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
@endpush 