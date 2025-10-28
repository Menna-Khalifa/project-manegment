@extends('dashboard.layouts.master')

@section('title')
    {{ __('groups.edit_groups') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('groups.index') }}">{{ __('layouts/main-sidebar.admins_and_permissions') }}</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ __('groups.edit_groups') }}
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
                            <a class="btn btn-primary btn-sm" href="{{ route('groups.index') }}">{{ __('groups.back') }}</a>
                        </div>
                    </div><br>
                    <form class="needs-validation" action="{{ route('groups.update', $group->id) }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row mg-b-20">
                            <div class="col-md-6">
                                    <div class="form-group mg-b-0">
                                        <label>{{ __('groups.name') }}:<span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control"
                                            placeholder="{{ __('groups.name') }}"
                                            name="name" value="{{ $group->name }}"
                                            type="text">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                        </div>

                        <div class="row mg-b-20">
                            <div class="col-md-6">
                                    <div class="form-group mg-b-0">
                                        <label>{{ __('groups.description') }}:<span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control"
                                            placeholder="{{ __('groups.description') }}"
                                            name="description"
                                            value="{{ $group->description }}" type="text">
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                            <button class="btn btn-main-primary pd-x-20" type="submit">{{ __('groups.sure') }}</button>
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
