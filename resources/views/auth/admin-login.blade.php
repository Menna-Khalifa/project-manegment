@extends('dashboard.layouts.master2')

@section('title', 'Log In')
@section('css')
    <!-- Sidemenu-respoansive-tabs css -->
    <link href="{{ URL::asset('dashboard/assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}"
        rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- The image half -->
            <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent" style="padding: 0px !important;">
                <div class="row wd-102p text-center">
                    <div class="col-md-12 col-lg-12 col-xl-12 wd-100p" style="padding: 0px !important;">
                        <img src="{{ URL::asset('dashboard/assets/img/media/5.png') }}"
                            class="ht-xl-100p wd-md-100p wd-xl-100p" alt="logo">
                    </div>
                </div>
            </div>
            <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
                <div class="login d-flex align-items-center py-2">
                    <!-- Demo content-->
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin">
                                    <div class="mb-5 mt-3 d-flex"> <a href="{{ route('dashboard') }}"><img
                                                src="{{ URL::asset('dashboard/assets/img/brand/desktop-logo.png') }}"
                                                class="sign-favicon w-50" alt="logo"></a>
                                    </div>
                                    <div class="card-sigin">
                                        <div class="main-signup-header">
                                            <h2>Welcome back!</h2>
                                            <h5 class="font-weight-semibold mb-4">Please sign in to Projects Manegment.</h5>
                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input class="form-control" placeholder="Enter your email"
                                                        type="email" name="email" required>
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Password</label> <input class="form-control"
                                                        placeholder="Enter your password" type="password" name="password"
                                                        required>
                                                    @error('password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <button class="btn btn-main-primary btn-block" type="submit">Sign
                                                    In</button>
                                            </form>
                                            {{-- <div class="main-signin-footer mt-5">
                                                <p><a href="#">Forgot password?</a></p>
                                                <p>Don't have an account? <a href="{{ route('register') }}">Create
                                                        an Account</a></p>
                                            </div> --}}
                                        </div>
                                    </div>

                                    <div class="d-flex"> <a href="{{ route('dashboard') }}"><img
                                                src="{{ URL::asset('dashboard/assets/img/media/6.jpg') }}"
                                                class="sign-favicon ht-150" alt="logo"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End -->
                </div>
            </div><!-- End -->
        </div>
    </div>
@endsection
@section('js')
@endsection
