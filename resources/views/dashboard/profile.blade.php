@extends('dashboard.layouts.master')

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Profile
    </li>
@endsection

@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-lg-4">
            <div class="card mg-b-20">
                <div class="card-body">
                    <div class="pl-0">
                        <div class="main-profile-overview">
                            <div class="main-img-user profile-user">
                                @if ($admin->getFirstMediaUrl('avatars') != null)
                                    <img alt="" src="{{ $admin->getFirstMediaUrl('avatars', 'avatar') }}">
                                    <a class="fas fa-camera profile-edit" href="JavaScript:void(0);" data-toggle="modal"
                                        data-target="#editModal"></a>
                                @else
                                    <img alt=""
                                        src="{{ URL::asset('dashboard/assets/img/faces/default_user.png') }}">
                                    <a class="fas fa-camera profile-edit" href="JavaScript:void(0);" data-toggle="modal"
                                        data-target="#editModal"></a>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between mg-b-20">
                                <div>
                                    <h5 class="main-profile-name">{{ $admin->name ?? __('general.not_found') }}</h5>
                                    <p class="main-profile-name-text">
                                        {{ $admin->getRoleNames()->first() ?? __('general.not_found') }}
                                    </p>
                                </div>
                            </div>
                        </div><!-- main-profile-overview -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="tabs-menu ">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs profile navtab-custom panel-tabs">
                            <li class="active">
                                <a href="#home" data-toggle="tab" aria-expanded="true"> <span class="visible-xs"><i
                                            class="las la-user-circle tx-16 mr-1"></i></span> <span
                                        class="hidden-xs">{{ __('admins.admin_about') }}</span> </a>
                            </li>
                            <li class="">
                                <a href="#settings" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i
                                            class="las la-cog tx-16 mr-1"></i></span> <span
                                        class="hidden-xs">{{ __('admins.settings') }}</span> </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content border-left border-bottom border-right border-top-0 p-4">
                        <div class="tab-pane active" id="home">
                            <div class="m-t-30">
                                <h4 class="tx-15 text-uppercase mt-3">{{ __('admins.name') }}
                                </h4>
                                <div class=" p-t-10">
                                    <p class="">{{ $admin->name ?? __('general.not_found') }}</p>
                                </div>
                                <hr>

                                <h4 class="tx-15 text-uppercase mt-3">{{ __('admins.email') }}</h4>
                                <div class=" p-t-10">
                                    <p class="">{{ $admin->email ?? __('general.not_found') }}</p>
                                </div>
                                <hr>
                                <h4 class="tx-15 text-uppercase mt-3">{{ __('admins.phone') }}</h4>
                                <div class=" p-t-10">
                                    <p class="">{{ $admin->phone ?? __('general.not_found') }}</p>
                                </div>
                                <hr>
                                @if ($admin->type == 'user')
                                    <h4 class="tx-15 text-uppercase mt-3">{{ __('admins.group') }}</h4>
                                    <div class=" p-t-10">
                                        <p class="">{{ $admin->group->name ?? __('general.not_found') }}</p>
                                    </div>
                                    <hr>
                                @endif
                                <h4 class="tx-15 text-uppercase mt-3">{{ __('admins.status') }}</h4>
                                <div class="p-t-10 text-left">
                                    @if ($admin->status == 'active')
                                        <label class="badge badge-success">
                                            {{ __('admins.enabled') }}
                                        </label>
                                    @else
                                        <label class="badge badge-danger">
                                            {{ __('admins.not_enabled') }}
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings">
                            <form action="{{ route('update_profile', $admin->id) }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="name">{{ __('admins.name') }}</label>
                                    <input type="text" name="name"
                                        value="{{ $admin->name ?? __('general.not_found') }}" id="name"
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="Email">{{ __('admins.email') }}</label>
                                    <input type="email" name="email"
                                        value="{{ $admin->email ?? __('general.not_found') }}" id="Email"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="Phone">{{ __('admins.phone') }}</label>
                                    <input type="number" name="phone"
                                        value="{{ $admin->phone ?? __('general.not_found') }}" id="Phone"
                                        class="form-control">
                                </div>
                                @if ($admin->type == 'user')
                                <div class="form-group">
                                    <label for="group">{{ __('admins.group') }}</label>
                                    <select name="group_id" class="form-control nice-select custom-select">
                                        <option disabled selected>{{ __('admins.group_select') }}</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ $admin->group_id == $group->id ? 'selected' : '' }}>{{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="Password">{{ __('admins.password') }}</label>
                                    <input type="password" name="password" placeholder="6 - 15 Characters" id="Password"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="RePassword">{{ __('admins.confirm-password') }}</label>
                                    <input type="password" name="password_confirmation" placeholder="6 - 15 Characters"
                                        id="RePassword" class="form-control">
                                </div>
                                <button class="btn btn-primary waves-effect waves-light w-md"
                                    type="submit">{{ __('admins.sure') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">{{ __('admins.edit_image') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('update_profile_photo', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="image">{{ __('admins.image') }}</label>
                            @if ($admin->getFirstMediaUrl('avatars') != null)
                                <input type="file" class="dropify" name="photo" data-height="100"
                                    data-default-file="{{ $admin->getFirstMediaUrl('avatars', 'avatar') }}" />
                            @else
                                <input type="file" class="dropify" name="photo" data-height="100" />
                            @endif
                        </div>
                        <button class="btn btn-primary waves-effect waves-light w-md"
                            type="submit">{{ __('admins.sure') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
