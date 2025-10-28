@extends('dashboard.layouts.master')

@section('title')
    {{ __('roles.roles_and_permissions') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('admin.index') }}">{{ __('layouts/main-sidebar.admins_and_permissions') }}</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ __('roles.roles_and_permissions') }}
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-right">
                                @can('add_role')
                                    <a class="btn btn-primary" href="{{ route('roles.create') }}">
                                        <i class="text-white fas fa-plus"></i>&nbsp;&nbsp;
                                        {{ __('roles.add_role') }}</a>
                                @endcan
                            </div>
                        </div>
                        <br>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table key-buttons text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>{{ __('roles.name') }}</th>
                                    @if (auth()->user()->can('add_role') ||
                                            auth()->user()->can('show_role') ||
                                            auth()->user()->can('edit_role') ||
                                            auth()->user()->can('delete_role'))
                                        <th>{{ __('roles.processes') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->name }}</td>
                                        @if (auth()->user()->can('add_role') ||
                                                auth()->user()->can('show_role') ||
                                                auth()->user()->can('edit_role') ||
                                                auth()->user()->can('delete_role'))
                                            <td>
                                                @can('show_role')
                                                    <a class="btn btn-success btn-sm"
                                                        href="{{ route('roles.show', $role->id) }}">
                                                        <i class="text-white fas fa-eye"></i>&nbsp;&nbsp;
                                                        {{ __('roles.show') }}</a>
                                                @endcan

                                                @can('edit_role')
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ route('roles.edit', $role->id) }}">
                                                        <i class="text-white fas fa-edit"></i>&nbsp;&nbsp;
                                                        {{ __('roles.edit') }}</a>
                                                @endcan

                                                @can('delete_role')
                                                    <a class="btn btn-danger btn-sm modal-effect" data-effect="effect-scale"
                                                        data-toggle="modal" href="#modaldemo8-{{ $role->id }}"
                                                        title="{{ __('roles.delete') }}"><i
                                                            class="text-white fas fa-trash-alt"></i>&nbsp;&nbsp;{{ __('roles.delete') }}</a>
                                                @endcan

                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal" id="modaldemo8-{{ $role->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">{{ __('roles.delete') }}</h6><button
                                                        aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('roles.delete', $role->id) }}" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="modal-body">
                                                        <p>{{ __('roles.are_you_sure_delete_role') }}</p><br>
                                                        <input type="hidden" name="user_id" value="{{ $role->id }}">
                                                        <input class="form-control" name="username"
                                                            value="{{ $role->name }}" type="text" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ __('roles.cancel') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ __('roles.sure') }}</button>
                                                    </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
