@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!--  Row 1 -->
<div class="row">
  <div class="col-lg-8">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center">
          <div>
            <h4 class="card-title">Sales Overview</h4>
            <p class="card-subtitle">
              Ample admin Vs Pixel admin
            </p>
          </div>
          <div class="ms-auto">
            <ul class="list-unstyled mb-0">
              <li class="list-inline-item text-primary">
                <span class="round-8 text-bg-primary rounded-circle me-1 d-inline-block"></span>
                Ample
              </li>
              <li class="list-inline-item text-info">
                <span class="round-8 text-bg-info rounded-circle me-1 d-inline-block"></span>
                Pixel Admin
              </li>
            </ul>
          </div>
        </div>
        <div id="sales-overview" class="mt-4 mx-n6"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card overflow-hidden">
      <div class="card-body pb-0">
        <div class="d-flex align-items-start">
          <div>
            <h4 class="card-title">Weekly Stats</h4>
            <p class="card-subtitle">Average sales</p>
          </div>
          <div class="ms-auto">
            <div class="dropdown">
              <a href="javascript:void(0)" class="text-muted" id="year1-dropdown" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="ti ti-dots fs-7"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="year1-dropdown">
                <li>
                  <a class="dropdown-item" href="javascript:void(0)">Action</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection