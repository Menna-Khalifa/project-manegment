@extends('dashboard.layouts.master')

@section('title')
    Volts
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_volts.index') }}">Volts</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        List
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                @can('add_project_volt')
                    <div class="card-header pb-0">
                        <div class="col-sm-1 col-md-2">
                            <a class="btn btn-primary" href="{{ route('project_volts.create') }}">
                                <i class="las la-plus"></i>
                                Add Volt</a>
                        </div>
                    </div>
                @endcan
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered border-primary">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($volts as $volt)
                                    <tr>
                                        <td>
                                            {{ ($volts->currentPage() - 1) * $volts->perPage() + $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $volt->value }}
                                        </td>
                                        <td>
                                            @can('edit_project_volt')
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('project_volts.edit', $volt->id) }}">Edit</a>
                                            @endcan
                                            @can('delete_project_volt')
                                                <a class="btn btn-sm btn-danger modal-effect" data-effect="effect-scale"
                                                    data-toggle="modal" href="#delete-volt-{{ $volt->id }}">Delete</a>
                                            @endcan
                                        </td>
                                    </tr>
                                    <div class="modal" id="delete-volt-{{ $volt->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Delete Volt</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project_volts.destroy', $volt->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this volt?</p>
                                                        <input class="form-control" value="{{ $volt->value }}"
                                                            type="text" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $volts->appends(request()->query())->links('component.pagination', ['items' => $volts]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
