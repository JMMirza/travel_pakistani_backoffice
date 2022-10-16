<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/img/logo.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/img/logo.png') }}" alt=""
                    style="max-height: 100%; height: auto; width: 180px;">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/img/logo.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/img/logo.png') }}" alt=""
                    style="max-height: 100%; height: auto; width: 180px;">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="" role="button">
                        <i class="ri-home-smile-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('landmarks.index') }}" role="button">
                        <i class="ri-home-smile-line"></i> <span data-key="t-dashboards">Landmarks</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('inquiries.index') }}" role="button">
                        <i class="ri-home-smile-line"></i> <span data-key="t-dashboards">Inquiry</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('templates.index') }}" role="button">
                        <i class="ri-home-smile-line"></i> <span data-key="t-dashboards">Templates</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('itinerary-templates.index') }}" role="button">
                        <i class="ri-home-smile-line"></i> <span data-key="t-dashboards">Itinerary Templates</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('operators.index') }}" role="button">
                        <i class="ri-home-smile-line"></i> <span data-key="t-dashboards">View Operators</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('hotels.index') }}" role="button">
                        <i class="ri-home-smile-line"></i> <span data-key="t-dashboards">Hotels</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarRolePermission" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarRolePermission">
                        <i class="ri-dashboard-2-line"></i>
                        <span data-key="t-dashboards">Roles & Permissions</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarRolePermission">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('staffs.index') }}" class="nav-link" data-key="t-analytics">
                                    Staffs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('roles-permission-assignment-list') }}" class="nav-link"
                                    data-key="t-analytics"> Roles Assignment </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}" class="nav-link" data-key="t-analytics"> Roles
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('permissions.index') }}" class="nav-link" data-key="t-analytics">
                                    Permissions </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
