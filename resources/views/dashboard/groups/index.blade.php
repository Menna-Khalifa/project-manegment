@extends('dashboard.layouts.master')

@section('title')
    {{ __('groups.groups_list') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('groups.index') }}">{{ __('layouts/main-sidebar.admins_and_permissions') }}</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ __('groups.groups_list') }}
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
                        @can('add_group')
                            <a class="btn btn-primary" href="{{ route('groups.create') }}">
                                <i class="las la-user-plus"></i>
                                {{ __('groups.add_groups') }}</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table key-buttons text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>{{ __('groups.name') }}</th>
                                    <th>{{ __('groups.description') }}</th>
                                    @if (
                                            auth()->user()->can('edit_group') ||
                                            auth()->user()->can('delete_group'))
                                        <th>{{ __('groups.processes') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>


                                @foreach ($groups as $key => $group)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $group->name ?? __('general.not_found') }}</td>
                                        <td>{{ $group->description ?? __('general.not_found') }}</td>
                                        <!-- permission some -->
                                        @if (
                                                auth()->user()->can('edit_group') ||
                                                auth()->user()->can('delete_group'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">{{ __('groups.processes') }}&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('edit_group')
                                                            <a class="dropdown-item"
                                                                href="{{ route('groups.edit', $group->id) }}"><i
                                                                    class="text-primary fas fa-edit"></i>&nbsp;&nbsp;{{ __('groups.edit') }}</a>
                                                        @endcan

                                                        @can('delete_group')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#modaldemo8-{{ $group->id }}"
                                                                title="{{ __('groups.delete') }}"><i
                                                                    class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;{{ __('groups.delete') }}</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal" id="modaldemo8-{{ $group->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">{{ __('groups.delete_group') }}</h6><button
                                                        aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('groups.destroy', $group->id) }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>{{ __('groups.are_you_sure_delete_group') }}</p><br>
                                                        <input type="hidden" name="group_id"
                                                            value="{{ $group->id }}">
                                                        <input class="form-control" name="group_name"
                                                            value="{{ $group->name }}" type="text" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ __('groups.cancel') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ __('groups.sure') }}</button>
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

    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
