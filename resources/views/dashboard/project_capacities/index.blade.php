@extends('dashboard.layouts.master')

@section('title')
    Capacities
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_capacities.index') }}">Capacities</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        List
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                @can('add_project_capacity')
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        <a class="btn btn-primary" href="{{ route('project_capacities.create') }}">
                            <i class="las la-plus"></i>
                            Add Capacity</a>
                    </div>
                </div>
                @endcan
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table key-buttons text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($capacities as $capacity)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $capacity->name }}
                                        </td>
                                        <td>
                                            @can('edit_project_capacity')
                                            <a class="btn btn-sm btn-primary" href="{{ route('project_capacities.edit', $capacity->id) }}">Edit</a>
                                            @endcan
                                            @can('delete_project_capacity')
                                            <a class="btn btn-sm btn-danger modal-effect" data-effect="effect-scale" data-toggle="modal" href="#delete-capacity-{{ $capacity->id }}">Delete</a>
                                            @endcan
                                        </td>
                                    </tr>
                                    <div class="modal" id="delete-capacity-{{ $capacity->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Delete Capacity</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project_capacities.destroy', $capacity->id) }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this capacity?</p>
                                                        <input class="form-control" value="{{ $capacity->name }}" type="text" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                    {{ $capacities->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection