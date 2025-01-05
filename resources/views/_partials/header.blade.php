<div class="topbar">
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-nav float-end mb-0">
            <li class="dropdown">
                <a class="dropdown-toggle-btn nav-link dropdown-toggle waves-effect waves-light nav-user" data-x-toggle="#user-dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <span class="ms-1 nav-user-name hidden-sm">
                        <span class="text-muted">Welcome,</span>
                        <span class="fw-bold">Tuan Minh</span>
                        <span>(Admin)</span>
                    </span>
                    <img src="{{ Vite::asset('resources/assets/images/users/user-5.jpg') }}" alt="profile-user" class="rounded-circle thumb-xs"/>
                </a>
                <div class="dropdown-menu" id="user-dropdown">
                    <a class="dropdown-item" href="#"><i data-feather="user" class="align-self-center icon-xs icon-dual me-1"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i data-feather="key" class="align-self-center icon-xs icon-dual me-1"></i> Change Password</a>
                    <div class="dropdown-divider mb-0"></div>
                    <a class="dropdown-item" href="#"><i data-feather="power" class="align-self-center icon-xs icon-dual me-1"></i> Logout</a>
                </div>
            </li>
        </ul>

        <ul class="list-unstyled topbar-nav mb-0">
            <li>
                <button class="nav-link button-menu-mobile">
                    <i data-feather="menu" class="align-self-center topbar-icon"></i>
                </button>
            </li>
        </ul>
    </nav>
</div>
