<div class="left-sidenav">
    <div class="brand border">
        <a href="{{ route('app.home.index') }}" class="logo">
            <span>
{{--                <img src="{{ Vite::asset('resources/assets/images/logo.png') }}" alt="logo-small" class="logo-dark mb-3" height="30xp">--}}
            </span>
            <span class="text-uppercase font-22 fw-bold text-info">{{ config('app.name') }}</span>
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
            <li>
                <a class="nav-link" href="javascript: void(0);">
                    <i data-feather="users" class="align-self-center menu-icon"></i>
                    <span style="margin-left: 2.5px;">User Account</span>
                </a>
                <ul class="nav-second-level mm-collapse" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="{{ route('app.user.index') }}"><i class="ti-control-record"></i>Manage User</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('app.user.create') }}"><i class="ti-control-record"></i>Add New User</a></li>
                </ul>
            </li>
            <li>
                <a class="nav-link" href="javascript: void(0);">
                    <i data-feather="credit-card" class="align-self-center menu-icon"></i>
                    <span style="margin-left: 2.5px;">Paygate</span>
                </a>
                <ul class="nav-second-level mm-collapse" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="{{ route('app.paygate.index') }}"><i class="ti-control-record"></i>Manage Paygate</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('app.paygate.create') }}"><i class="ti-control-record"></i>Add New Paygate</a></li>
                </ul>
            </li>
            <li>
                <a class="nav-link" href="javascript: void(0);">
                    <i data-feather="layout" class="align-self-center menu-icon"></i>
                    <span style="margin-left: 2.5px;">Online Store</span>
                </a>
                <ul class="nav-second-level mm-collapse" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="{{ route('app.store.index') }}"><i class="ti-control-record"></i>Manage Store</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('app.store.create') }}"><i class="ti-control-record"></i>Add New Store</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('app.dispute.index') }}">
                    <i data-feather="trending-down" class="align-self-center menu-icon"></i>
                    <span>Dispute</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('app.order.index') }}">
                    <i data-feather="shopping-cart" class="align-self-center menu-icon"></i>
                    <span>Store Order</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('app.tracking.index') }}">
                    <i data-feather="truck" class="align-self-center menu-icon"></i>
                    <span>Delivery Tracking</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('app.mail-box.index') }}">
                    <i data-feather="mail" class="align-self-center menu-icon"></i>
                    <span>Mail Box</span>
                </a>
            </li>

            <hr class="hr-dashed hr-menu">
            <li class="menu-label my-2">Help Center</li>
            <li>
                <a href="{{ route('app.help-center.faq') }}"><i data-feather="help-circle" class="align-self-center menu-icon"></i><span>FAQ</span></a>
                <a href="mailto:m397.dev@gmail.com"><i data-feather="alert-triangle" class="align-self-center menu-icon"></i><span>Bug Report</span></a>
            </li>

            <hr class="hr-dashed hr-menu">
            <li class="menu-label my-2">{{ Auth::user()->username ?? 'Guest' }} ({{ \App\Models\User::ROLES[Auth::user()->role ?? 'default_role'] ?? 'Unknown Role' }})
            </li>
            <li>
                <a href="#"><i data-feather="user" class="align-self-center menu-icon"></i><span>Profile</span></a>
                <a href="#"><i data-feather="key" class="align-self-center menu-icon"></i><span>Change Password</span></a>
                <a href="{{ route('app.security.logout') }}" onclick="event.preventDefault();document.getElementById('formLogout').submit();"><i data-feather="power" class="align-self-center menu-icon"></i><span>Logout</span></a>
                <form id="formLogout" method="post" action="{{ route('app.security.logout') }}">
                    @csrf
                </form>
            </li>

            <hr class="hr-dashed hr-menu">
            <li class="menu-label my-2">Version {{ config('app.version') }}</li>
        </ul>
    </div>
</div>
