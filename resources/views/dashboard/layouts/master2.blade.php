<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta name="Description" content="albaytri">
    <meta name="Author" content="albaytri">
    <meta name="Keywords" content="albaytri" />
    @include('dashboard.layouts.head')
</head>

<body class="main-body bg-primary-transparent">

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


    <!-- Loader -->
    <div id="global-loader">
        <img src="{{ URL::asset('dashboard/assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->
    @yield('content')
    @include('dashboard.layouts.footer-scripts')
</body>

</html>
