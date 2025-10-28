<!-- main-header opened -->
<div class="main-header sticky side-header nav nav-item">
    <div class="container-fluid">
        <div class="main-header-left ">
            <div class="responsive-logo">
                <a href="{{ route('dashboard') }}"><img
                        src="{{ URL::asset('dashboard/assets/img/brand/desktop-logo.png') }}" class="logo-1" alt="logo"></a>
                <a href="{{ route('dashboard') }}"><img
                        src="{{ URL::asset('dashboard/assets/img/brand/toggle-logo.png') }}" class="dark-logo-1"
                        alt="logo"></a>
                <a href="{{ route('dashboard') }}"><img
                        src="{{ URL::asset('dashboard/assets/img/brand/desktop-white.png') }}" class="logo-2" alt="logo"></a>
                <a href="{{ route('dashboard') }}"><img
                        src="{{ URL::asset('dashboard/assets/img/brand/toggle-white.png') }}" class="dark-logo-2"
                        alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>
        </div>
        <div class="main-header-right">
            <div class="nav nav-item  navbar-nav-right ml-auto">
                {{-- <div class="dropdown nav-item main-header-notification">
                    @php
                        $admin = auth()->user();
                        // التحقق من صلاحيات المستخدم
                        if (!$admin->hasRole('admin')) {
                            $notifications = Illuminate\Notifications\DatabaseNotification::where(
                                'notifiable_id',
                                $admin->id,
                            )
                                ->orderBy('created_at', 'desc')
                                ->take(10)
                                ->get();
                        } else {
                            $notifications = Illuminate\Notifications\DatabaseNotification::orderBy(
                                'created_at',
                                'desc',
                            )
                                ->take(10)
                                ->get();
                        }
                    @endphp
                    <a class="new nav-link" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-bell">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        @if ($notifications->where('read_at', null)->count() > 0)
                            <span class="pulse"></span>
                        @endif
                    </a>
                    <div class="dropdown-menu">
                        <div class="menu-header-content bg-primary text-right">
                            <div class="d-flex">
                                <h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">
                                    {{ __('notifications.title') }}</h6>
                                @if ($notifications->where('read_at', null)->count() > 0)
                                    <a href="{{ route('notifications.markAllAsRead') }}"
                                        class="badge badge-pill badge-warning mr-auto my-auto float-left">
                                        {{ __('notifications.mark_all_read') }}
                                    </a>
                                @endif
                            </div>
                            <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12">
                                {{ __('notifications.you_have') }}
                                {{ $notifications->where('read_at', null)->count() }}
                                {{ __('notifications.unread_notifications') }}
                            </p>
                        </div>
                        <div class="main-message-list chat-scroll">
                            @forelse($notifications as $notification)
                                <a class="d-flex p-2 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}"
                                    href="{{ route('notifications.show', $notification->id) }}">
                                    <div class="notifyimg bg-{{ $notification->data['type'] ?? 'primary' }} d-flex align-items-center justify-content-center"
                                        style="min-width: 45px; height: 45px;">
                                        <i
                                            class="{{ $notification->data['icon'] ?? 'la la-bell' }} text-white fs-5"></i>
                                    </div>
                                    <div class="mr-3 flex-grow-1" style="min-width: 0;">
                                        <h6 class="notification-label mb-1 font-weight-bold text-truncate">
                                            {{ $notification->data['title'] }}</h6>
                                        @if (isset($notification->data['body']))
                                            <p class="mb-1 tx-12 text-muted" style="line-height: 1.4;">
                                                {{ \Illuminate\Support\Str::limit($notification->data['body'], 50, '...') }}
                                            </p>
                                        @endif
                                        <div class="notification-subtext d-flex align-items-center tx-11">
                                            <i class="far fa-clock ml-1 fs-12"></i>
                                            <small class="text-muted" style="font-size: 90% !important;">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                            @if (!$notification->read_at)
                                                <span
                                                    class="badge badge-danger badge-pill mr-auto ml-2">{{ __('notifications.new') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mr-2 text-left d-flex align-items-center">
                                        <i class="las la-angle-left text-muted"></i>
                                    </div>
                                </a>
                            @empty
                                <div class="p-4 text-center">
                                    {{ __('notifications.no_notifications') }}
                                </div>
                            @endforelse
                        </div>
                        <div class="dropdown-footer">
                            <a href="{{ route('notifications.index') }}">{{ __('notifications.view_all') }}</a>
                        </div>
                    </div>
                </div>
                <div class="nav-item full-screen fullscreen-button">
                    <a class="new nav-link full-screen-link" href="#"><svg xmlns="http://www.w3.org/2000/svg"
                            class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-maximize">
                            <path
                                d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                            </path>
                        </svg></a>
                </div> --}}

                @php
                    $avatarUrl = Auth::user()->getFirstMediaUrl('avatars', 'avatar');
                @endphp

                <div class="dropdown main-profile-menu nav nav-item nav-link">
                    <a class="profile-user d-flex" href="">
                        @if ($avatarUrl != null && !empty($avatarUrl) && $avatarUrl != '')
                            <img alt="" src="{{ $avatarUrl }}">
                        @else
                            <img alt="" src="{{ asset('dashboard/assets/img/faces/default_user.png') }}">
                        @endif
                    </a>
                    <div class="dropdown-menu">
                        <div class="main-header-profile bg-primary p-3">
                            <div class="d-flex wd-100p">
                                <div class="main-img-user">
                                    @if ($avatarUrl != null && !empty($avatarUrl) && $avatarUrl != '')
                                        <img alt="" src="{{ $avatarUrl }}" class="">
                                    @else
                                        <img alt=""
                                            src="{{ asset('dashboard/assets/img/faces/default_user.png') }}"
                                            class="">
                                    @endif
                                </div>
                                <div class="mr-3 my-auto">
                                    <h6>{{ Auth::user()->name }}</h6><span>{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{ route('profile', Auth::user()->id) }}"><i
                                class="bx bx-user-circle"></i>{{ __('layouts/main-header.profile') }}</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"><i class="bx bx-log-out"></i>
                            {{ __('layouts/main-header.sign_out') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /main-header -->
