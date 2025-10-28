@extends('dashboard.layouts.master')


@section('title')
    {{ __('roles.show_role') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('roles.index') }}">{{ __('layouts/main-sidebar.admins_and_permissions') }}</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ __('roles.show_role') }}
    </li>
    <!-- breadcrumb -->
@endsection


@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mg-b-20">
                <div class="card-body">
                    <div class="main-content-label mg-b-5 mb-5">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('roles.index') }}">{{ __('roles.back') }}</a>
                        </div>
                    </div>
                    <div class="main-content-label mg-b-5">
                        <div class="col-xs-7 col-sm-7 col-md-7">
                            <div class="form-group">
                                <p>{{ __('roles.name_role') }} :</p>
                                <input type="text" name="name" readonly value="{{ $role->name }}"
                                    placeholder="{{ __('roles.name_role') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- col -->
                        <div class="col-lg-4">
                            <ul id="treeview1">
                                <li><a href="#">{{ __('roles.permissions') }}</a>
                                    <ul>
                                </li>
                                @foreach ($groupedPermissionsArray as $teamId => $permissions)
                                    <!-- عنوان الفريق -->
                                    <h4 class="mt-4 mb-4 mr-4">{{ __('role_seeder.' . $teamId) }}</h4>

                                    <!-- الصلاحيات -->
                                    <li>
                                        <div class="d-flex flex-wrap mr-4" style="width:250% !important">
                                            @foreach ($permissions as $permissionId => $permissionName)
                                                <div class="custom-checkbox custom-control mb-3 ml-3"
                                                    style="min-width: 100px;">
                                                    <input type="checkbox" name="permission[]" data-checkboxes="mygroup"
                                                        class="custom-control-input permission-checkbox team-{{ $teamId }}"
                                                        id="{{ $permissionId }}" value="{{ $permissionId }}" disabled
                                                        checked>
                                                    <label for="{{ $permissionId }}"
                                                        class="custom-control-label mt-1 text-right"
                                                        style="width: 100%; display: flex; justify-content: flex-end;">
                                                        {{ __('role_seeder.' . $permissionName) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- /col -->
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
@endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/treeview/treeview.js') }}"></script>
@endsection
