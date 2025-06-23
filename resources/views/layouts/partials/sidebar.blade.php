<!-- Sidebar Start -->
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
        <img src="{{ asset('assets/images/logos/logo.svg') }}" alt="" />
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-6"></i>
      </div>
    </div>
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar>
      <ul id="sidebarnav">
        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Home</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
            <i class="ti ti-atom"></i>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>

        <!-- Dashboard -->
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="#" aria-expanded="false">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex">
                <i class="ti ti-aperture"></i>
              </span>
              <span class="hide-menu">Analytical</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="#" aria-expanded="false">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex">
                <i class="ti ti-shopping-cart"></i>
              </span>
              <span class="hide-menu">eCommerce</span>
            </div>
          </a>
        </li>

        <li>
          <span class="sidebar-divider lg"></span>
        </li>
        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Quản lý</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex">
                <i class="ti ti-basket"></i>
              </span>
              <span class="hide-menu">Danh mục</span>
            </div>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between" href="#">
                <div class="d-flex align-items-center gap-3">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Danh sách danh mục</span>
                </div>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between" href="#">
                <div class="d-flex align-items-center gap-3">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Details</span>
                </div>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between" href="#">
                <div class="d-flex align-items-center gap-3">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">List</span>
                </div>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between" href="#">
                <div class="d-flex align-items-center gap-3">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Checkout</span>
                </div>
              </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link justify-content-between"  
                  href="#">
                  <div class="d-flex align-items-center gap-3">
                    <div class="round-16 d-flex align-items-center justify-content-center">
                      <i class="ti ti-circle"></i>
                    </div>
                    <span class="hide-menu">Add Product</span>
                  </div>
                  
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link justify-content-between"  
                  href="#">
                  <div class="d-flex align-items-center gap-3">
                    <div class="round-16 d-flex align-items-center justify-content-center">
                      <i class="ti ti-circle"></i>
                    </div>
                    <span class="hide-menu">Edit Product</span>
                  </div>
                </a>
            </li>
          </ul>
        </li>

        <li>
          <span class="sidebar-divider lg"></span>
        </li>
        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">UI</span>
        </li>
        {{-- <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('ui.buttons') }}" aria-expanded="false">
            <i class="ti ti-layers-subtract"></i>
            <span class="hide-menu">Buttons</span>
          </a>
        </li> --}}
        {{-- <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('ui.alerts') }}" aria-expanded="false">
            <i class="ti ti-alert-circle"></i>
            <span class="hide-menu">Alerts</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('ui.cards') }}" aria-expanded="false">
            <i class="ti ti-cards"></i>
            <span class="hide-menu">Card</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('ui.forms') }}" aria-expanded="false">
            <i class="ti ti-file-text"></i>
            <span class="hide-menu">Forms</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('ui.typography') }}" aria-expanded="false">
            <i class="ti ti-typography"></i>
            <span class="hide-menu">Typography</span>
          </a>
        </li> --}}

        <li>
          <span class="sidebar-divider lg"></span>
        </li>
        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Auth</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('login') }}" aria-expanded="false">
            <i class="ti ti-login"></i>
            <span class="hide-menu">Login</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('register') }}" aria-expanded="false">
            <i class="ti ti-user-plus"></i>
            <span class="hide-menu">Register</span>
          </a>
        </li>

        <li>
          <span class="sidebar-divider lg"></span>
        </li>
        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Extra</span>
        </li>
        {{-- <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('sample-page') }}" aria-expanded="false">
            <i class="ti ti-file"></i>
            <span class="hide-menu">Sample Page</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('icon-tabler') }}" aria-expanded="false">
            <i class="ti ti-archive"></i>
            <span class="hide-menu">Tabler Icon</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('docs') }}" aria-expanded="false">
            <i class="ti ti-file-text"></i>
            <span class="hide-menu">Documentation</span>
          </a>
        </li> --}}
      </ul>
    </nav>
  </div>
</aside>