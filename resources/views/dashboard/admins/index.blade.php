@extends('dashboard.layouts.master')

@section('title')
    {{ __('admins.admins_list') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('admin.index') }}">{{ __('layouts/main-sidebar.admins_and_permissions') }}</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ __('admins.admins_list') }}
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        @can('add_admin')
                            <a class="btn btn-primary" href="{{ route('admin.create') }}">
                                <i class="las la-user-plus"></i>
                                Add New Admin</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered border-primary">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('admins.image') }}</th>
                                    <th>{{ __('admins.name') }}</th>
                                    <th>{{ __('admins.email') }}</th>
                                    <th>{{ __('admins.phone') }}</th>
                                    <th>{{ __('admins.type_role') }}</th>
                                    <th>{{ __('admins.status') }}</th>
                                    @if (auth()->user()->can('show_admin') ||
                                            auth()->user()->can('edit_admin') ||
                                            auth()->user()->can('edit_status_admin') ||
                                            auth()->user()->can('assign_role_admin') ||
                                            auth()->user()->can('delete_admin'))
                                        <th>{{ __('admins.processes') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>



                                @foreach ($admins as $key => $admin)


                                    <tr>
                                        <td>
                                            {{ ($admins->currentPage() - 1) * $admins->perPage() + $loop->iteration }}
                                        </td>
                                        <td>
                                            @if ($admin->getFirstMediaUrl('avatars') != null)
                                                <div class="main-img-user avatar-lg">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="{{ $admin->getFirstMediaUrl('avatars', 'avatar') }}">
                                                </div>
                                            @else
                                                <div class="main-img-user avatar-lg">
                                                    <img alt="avatar" class="rounded-circle"
                                                        src="{{ asset('dashboard/assets/img/faces/default_user.png') }}">
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $admin->name ?? __('general.not_found') }}</td>
                                        <td>{{ $admin->email ?? __('general.not_found') }}</td>
                                        <td>{{ $admin->phone ?? __('general.not_found') }}</td>
                                        <td>
                                            @if ($admin->getRoleNames()->first() != null)
                                                <label
                                                    class="badge badge-success">{{ $admin->getRoleNames()->first() ?? __('general.not_found') }}</label>
                                            @else
                                                <label class="badge badge-danger">{{ __('general.not_found') }}</label>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($admin->status == 'active')
                                                <label class="badge badge-success">
                                                    {{ __('admins.enabled') }}
                                                </label>
                                            @else
                                                <label class="badge badge-danger">
                                                    {{ __('admins.not_enabled') }}
                                                </label>
                                            @endif
                                        </td>

                                        <!-- permission some -->
                                        @if (auth()->user()->can('show_admin') ||
                                                auth()->user()->can('edit_admin') ||
                                                auth()->user()->can('edit_status_admin') ||
                                                auth()->user()->can('assign_role_admin') ||
                                                auth()->user()->can('delete_admin'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">{{ __('admins.processes') }}&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('show_admin')
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.show', $admin->id) }}"><i
                                                                    class="text-info fas fa-eye"></i>&nbsp;&nbsp;{{ __('admins.show') }}</a>
                                                        @endcan

                                                        @can('edit_admin')
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.edit', $admin->id) }}"><i
                                                                    class="text-primary fas fa-edit"></i>&nbsp;&nbsp;{{ __('admins.edit') }}</a>
                                                        @endcan

                                                        @can('edit_status_admin')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal"
                                                                href="#modaldemo8-editStatus-{{ $admin->id }}"
                                                                title="{{ __('admins.edit_status') }}"><i
                                                                    class="text-success fas fa-toggle-off"></i>&nbsp;&nbsp;{{ __('admins.edit_status') }}</a>
                                                        @endcan

                                                        @can('assign_role_admin')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal"
                                                                href="#modaldemo8-assign_role-{{ $admin->id }}"
                                                                title="{{ __('admins.assign_role') }}"><i
                                                                    class="text-success fas fa-user-plus"></i>&nbsp;&nbsp;{{ __('admins.assign_role') }}</a>
                                                        @endcan

                                                        @can('delete_admin')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#modaldemo8-{{ $admin->id }}"
                                                                title="{{ __('admins.delete') }}"><i
                                                                    class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;{{ __('admins.delete') }}</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>


                                    <!-- Edit Assign Role Modal-->
                                    <div class="modal" id="modaldemo8-assign_role-{{ $admin->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">{{ __('admins.assign_role') }}</h6><button
                                                        aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('admin.assign_role', $admin->id) }}" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="modal-body">
                                                        <p>{{ __('admins.are_you_sure_assign_role') }}</p><br>
                                                        <input type="hidden" name="user_id" value="{{ $admin->id }}">
                                                        <select name="roles_name" class="form-control">
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role }}"
                                                                    {{ $admin->getRoleNames()->first() == $role ? 'selected' : '' }}>
                                                                    {{ $role }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ __('admins.cancel') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ __('admins.sure') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Status Modal-->
                                    <div class="modal" id="modaldemo8-editStatus-{{ $admin->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">{{ __('admins.edit_status') }}</h6><button
                                                        aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('admin.edit_status', $admin->id) }}"
                                                    method="post">
                                                    {{ csrf_field() }}
                                                    <div class="modal-body">
                                                        <p>{{ __('admins.are_you_sure_edit_status') }}</p><br>
                                                        <input type="hidden" name="user_id"
                                                            value="{{ $admin->id }}">
                                                        <select name="status" class="form-control">
                                                            <option value="active"
                                                                {{ $admin->status == 'active' ? 'selected' : '' }}>
                                                                {{ __('admins.enabled') }}</option>
                                                            <option value="inactive"
                                                                {{ $admin->status == 'inactive' ? 'selected' : '' }}>
                                                                {{ __('admins.not_enabled') }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ __('admins.cancel') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ __('admins.sure') }}</button>
                                                    </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal" id="modaldemo8-{{ $admin->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">{{ __('admins.delete_admin') }}</h6><button
                                                        aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('admin.delete', $admin->id) }}" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="modal-body">
                                                        <p>{{ __('admins.are_you_sure_delete_admin') }}</p><br>
                                                        <input type="hidden" name="user_id"
                                                            value="{{ $admin->id }}">
                                                        <input class="form-control" name="username"
                                                            value="{{ $admin->name }}" type="text" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ __('admins.cancel') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ __('admins.sure') }}</button>
                                                    </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $admins->appends(request()->query())->links('component.pagination', ['items' => $admins]) }}
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>

    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
