<div class="left-sidenav">
    <div class="brand border">
        <a href="{{ route('app.home.index') }}" class="logo">
            <span>
                <img src="{{ Vite::asset('resources/assets/images/logo.png') }}" alt="logo-small" class="logo-dark mb-3" height="30xp">
            </span>
            <span class="text-uppercase font-22 fw-bold text-blue-2">{{ config('app.name') }}</span>
        </a>
    </div>

    <div class="menu-content h-100" data-simplebar>
        <ul class="metismenu left-sidenav-menu x-navbar">
            <li class="menu-label mt-0">Main</li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('app.home.index') }}"><i data-feather="home" class="align-self-center menu-icon"></i><span>Dashboard</span></a>
            </li>

            <hr class="hr-dashed hr-menu">
            <li class="menu-label my-2">Management</li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('app.user.index') }}"><i data-feather="user" class="align-self-center menu-icon"></i><span>User Account</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i data-feather="credit-card" class="align-self-center menu-icon"></i><span>Paygate</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i data-feather="layout" class="align-self-center menu-icon"></i><span>Store</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i data-feather="users" class="align-self-center menu-icon"></i><span>Customer</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i data-feather="trending-down" class="align-self-center menu-icon"></i><span>Dispute</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i data-feather="truck" class="align-self-center menu-icon"></i><span>Order Tracking</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i data-feather="mail" class="align-self-center menu-icon"></i><span>Mail Box</span></a>
            </li>

            <hr class="hr-dashed hr-menu">
            <li class="menu-label my-2">Help Center</li>
            <li>
                <a href="#"><i data-feather="help-circle" class="align-self-center menu-icon"></i><span>FAQ</span></a>
                <a href="mailto:m397.dev@gmail.com"><i data-feather="alert-triangle" class="align-self-center menu-icon"></i><span>Bug Report</span></a>
            </li>
        </ul>
    </div>
</div>
