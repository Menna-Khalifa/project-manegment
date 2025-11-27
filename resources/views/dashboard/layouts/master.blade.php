<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta name="Description" content="albaytri">
    <meta name="Author" content="albaytri">
    <meta name="Keywords" content="albaytri" />
    @include('dashboard.layouts.head')
</head>

{{-- dark-theme --}}

<body class="main-body app sidebar-mini">
    <!-- Loader -->
    <div id="global-loader">
        <img src="{{ URL::asset('dashboard/assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->
    @include('dashboard.layouts.main-sidebar')
    <!-- main-content -->
    <div class="main-content app-content">
        @include('dashboard.layouts.main-header')
        <!-- container -->
        <div class="container-fluid">
            @if (request()->routeIs('dashboard') || request()->routeIs('dashboard_amer'))
                @yield('page-header')
            @else
                <div class="breadcrumb-header justify-content-between">
                    <div class="my-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-style2">
                                {{-- <li class="breadcrumb-item" style="font-size: 1rem !important;">
                                    <a href="{{ route('dashboard') }}">{{ __('layouts/main-sidebar.main') }}</a>
                                </li> --}}
                                @yield('page-header')
                            </ol>
                        </nav>
                    </div>
                </div>
            @endif

            @if (session()->has('notification'))
                <script>
                    window.onload = function() {
                        const notification = @json(session('notification'));
                        notif({
                            msg: notification.msg,
                            type: notification.type
                        });
                    }
                </script>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>خطا</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
            @include('dashboard.layouts.sidebar')
            @include('dashboard.layouts.models')
            @include('dashboard.layouts.footer')
            @include('dashboard.layouts.footer-scripts')
            @stack('scripts')
</body>

</html>
