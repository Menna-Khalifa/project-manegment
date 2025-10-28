@extends('dashboard.layouts.master')

@section('title')
    {{ __('admins.add_admins') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('admin.index') }}">{{ __('layouts/main-sidebar.admins_and_permissions') }}</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ __('admins.add_admins') }}
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.index') }}">{{ __('admins.back') }}</a>
                        </div>
                    </div><br>
                    <form class="needs-validation" action="{{ route('admin.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="avatar">{{ __('admins.image') }}: <span class="tx-danger">*</span></label>
                                    <input type="file" class="dropify" id="photo" name="photo" data-height="100" />
                                    @error('photo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('admins.avatar_help_text') }}</small>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row mg-b-20">
                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>{{ __('admins.name') }}:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="{{ __('admins.name') }}" name="name"
                                        value="{{ old('name') }}" type="text">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>{{ __('admins.email') }}:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="{{ __('admins.email') }}" name="email"
                                        value="{{ old('email') }}" type="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mg-b-20">
                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>{{ __('admins.phone') }}:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="{{ __('admins.phone') }}" name="phone"
                                        value="{{ old('phone') }}" type="text">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>{{ __('admins.password') }}:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="{{ __('admins.password') }}" name="password"
                                        type="password">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mg-b-20">
                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>{{ __('admins.confirm-password') }}:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="confirm-password"
                                        name="confirm-password" type="password">
                                    @error('confirm-password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>{{ __('admins.type_role') }}:<span class="tx-danger">*</span></label>
                                    <select name="roles_name" class="form-control select2">
                                        <option disabled selected>{{ __('admins.type_role') }}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}"
                                                {{ old('roles_name') == $role ? 'selected' : '' }}>{{ $role }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('roles_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mg-b-20">
                            <input type="hidden" name="type" value="admin">

                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>{{ __('admins.status') }}:<span class="tx-danger">*</span></label>
                                    <select name="status" class="form-control nice-select custom-select">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                            {{ __('admins.enabled') }}</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            {{ __('admins.not_enabled') }}</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                            <button class="btn btn-main-primary pd-x-20" type="submit">{{ __('admins.sure') }}</button>
                        </div>
                    </form>
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
