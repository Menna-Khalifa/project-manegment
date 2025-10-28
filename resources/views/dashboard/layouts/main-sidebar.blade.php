<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ route('dashboard') }}"><img
                src="{{ URL::asset('dashboard/assets/img/brand/desktop-logo.png') }}" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active" href="{{ route('dashboard') }}"><img
                src="{{ URL::asset('dashboard/assets/img/brand/desktop-white.png') }}" class="main-logo dark-theme"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ route('dashboard') }}"><img
                src="{{ URL::asset('dashboard/assets/img/brand/toggle-logo.png') }}" class="logo-icon"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href="{{ route('dashboard') }}"><img
                src="{{ URL::asset('dashboard/assets/img/brand/toggle-white.png') }}" class="logo-icon dark-theme"
                alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    @php
                        $avatarUrl = Auth::user()->getFirstMediaUrl('avatars', 'avatar');
                    @endphp
                    @if ($avatarUrl != null && !empty($avatarUrl) && $avatarUrl != '')
                        <img alt="user-img" class="avatar avatar-xl brround" src="{{ $avatarUrl }}"><span
                            class="avatar-status profile-status bg-green"></span>
                    @else
                        <img alt="user-img" class="avatar avatar-xl brround"
                            src="{{ asset('dashboard/assets/img/faces/default_user.png') }}"><span
                            class="avatar-status profile-status bg-green"></span>
                    @endif
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::user()->name }}</h4>
                    <span class="mb-0 text-muted">{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <!-- الرئيسية -->
            <li class="side-item side-item-category">{{ __('layouts/main-sidebar.main') }}</li>
            <li class="slide">
                <a class="side-menu__item d-flex justify-content-between align-items-baseline"
                    href="{{ route('dashboard') }}">
                    <i class="fas fa-home side-menu__icon"
                        style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                    <span class="side-menu__label">{{ __('layouts/main-sidebar.main') }}</span>
                </a>
            </li>

            <!-- إدارة المشاريع -->
            @if (auth()->user()->can('projects_list') ||
                    auth()->user()->can('project_items_list') ||
                    auth()->user()->can('project_teams_list') ||
                    auth()->user()->can('project_equipments_list'))
                <li class="side-item side-item-category">Projects Management</li>

                @can('projects_list')
                    <li class="slide">
                        <a class="side-menu__item d-flex justify-content-between align-items-baseline"
                            href="{{ route('projects.index') }}">
                            <i class="fas fa-project-diagram side-menu__icon"
                                style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                            <span class="side-menu__label">Projects</span>
                        </a>
                    </li>
                @endcan

                @can('project_items_list')
                    <li class="slide">
                        <a class="side-menu__item d-flex justify-content-between align-items-baseline"
                            href="{{ route('project-items.index') }}">
                            <i class="fas fa-box side-menu__icon"
                                style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                            <span class="side-menu__label">Project Items</span>
                        </a>
                    </li>
                @endcan

                @can('project_teams_list')
                    <li class="slide">
                        <a class="side-menu__item d-flex justify-content-between align-items-baseline"
                            href="{{ route('project-teams.index') }}">
                            <i class="fas fa-users side-menu__icon"
                                style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                            <span class="side-menu__label">Project Teams</span>
                        </a>
                    </li>
                @endcan

                @can('project_equipments_list')
                    <li class="slide">
                        <a class="side-menu__item d-flex justify-content-between align-items-baseline"
                            href="{{ route('project-equipments.index') }}">
                            <i class="fas fa-tools side-menu__icon"
                                style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                            <span class="side-menu__label">Project Equipments</span>
                        </a>
                    </li>
                @endcan
            @endif

            <!-- إدارة الأقسام والعناصر -->
            @if (auth()->user()->can('sections_list') || auth()->user()->can('section_items_list') ||
                    auth()->user()->can('equipments_list'))
                <li class="side-item side-item-category mb-4">Sections & Equipments Management</li>

                @can('sections_list')
                    <li class="slide">
                        <a class="side-menu__item d-flex justify-content-between align-items-baseline"
                            href="{{ route('sections.index') }}">
                            <i class="fas fa-list side-menu__icon"
                                style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                            <span class="side-menu__label">Sections</span>
                        </a>
                    </li>
                @endcan

                @can('section_items_list')
                    <li class="slide">
                        <a class="side-menu__item d-flex justify-content-between align-items-baseline"
                            href="{{ route('section_items.index') }}">
                            <i class="fas fa-box side-menu__icon"
                                style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                            <span class="side-menu__label">Section Items</span>
                        </a>
                    </li>
                @endcan

                <!-- إدارة المعدات -->
                @can('equipments_list')
                    <li class="slide">
                        <a class="side-menu__item d-flex justify-content-between align-items-baseline"
                            href="{{ route('equipments.index') }}">
                            <i class="fas fa-tools side-menu__icon"
                                style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                            <span class="side-menu__label">Equipments</span>
                        </a>
                    </li>
                @endcan
            @endif

            <!-- الفواتير -->
            @can('invoices_list')
                <li class="side-item side-item-category">Billing and Finance</li>
                <li class="slide">
                    <a class="side-menu__item d-flex justify-content-between align-items-baseline"
                        href="{{ route('invoices.index') }}">
                        <i class="fas fa-file-invoice side-menu__icon"
                            style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                        <span class="side-menu__label">Invoices</span>
                    </a>
                </li>
            @endcan

            <!-- إدارة المستخدمين -->
            @if (auth()->user()->can('users_list'))
                <li class="side-item side-item-category">{{ __('layouts/main-sidebar.users') }}</li>
                <li class="slide">
                    <a class="side-menu__item d-flex justify-content-between align-items-baseline" data-toggle="slide"
                        href="{{ url('/' . ($page = '#')) }}">
                        <i class="fas fa-users side-menu__icon"
                            style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                        <span class="side-menu__label">{{ __('layouts/main-sidebar.users') }}</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        @can('users_list')
                        <li><a class="slide-item" href="{{ route('user.index') }}">{{ __('users.users_list') }}</a></li>
                        @endcan
                    </ul>
                </li>
            @endif

            <!-- إدارة الصلاحيات -->
            @if (auth()->user()->can('admins_list') || auth()->user()->can('groups_list') || auth()->user()->can('roles_list'))
                <li class="side-item side-item-category">{{ __('layouts/main-sidebar.admins_and_permissions') }}</li>
                <li class="slide">
                    <a class="side-menu__item d-flex justify-content-between align-items-baseline" data-toggle="slide"
                        href="{{ url('/' . ($page = '#')) }}">
                        <i class="fas fa-user-shield side-menu__icon"
                            style="font-size: 17px !important;margin:0 0.5rem !important;"></i>
                        <span class="side-menu__label">{{ __('layouts/main-sidebar.admins_and_permissions') }}</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        @can('admins_list')
                            <li><a class="slide-item"
                                    href="{{ route('admin.index') }}">{{ __('layouts/main-sidebar.admins_list') }}</a>
                            </li>
                        @endcan
                        @can('groups_list')
                            <li><a class="slide-item"
                                    href="{{ route('groups.index') }}">{{ __('layouts/main-sidebar.groups_list') }}</a>
                            </li>
                        @endcan
                        @can('roles_list')
                            <li><a class="slide-item"
                                    href="{{ route('roles.index') }}">{{ __('layouts/main-sidebar.roles_and_permissions') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

        </ul>
    </div>
</aside>
<!-- main-sidebar -->
