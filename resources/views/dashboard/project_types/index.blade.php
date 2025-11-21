@extends('dashboard.layouts.master')

@section('title')
    Project Types
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_types.index') }}">Project Types</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        List
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                @can('add_project_type')
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        <a class="btn btn-primary" href="{{ route('project_types.create') }}">
                            <i class="las la-plus"></i>
                            Add Type</a>
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
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($types as $type)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $type->name }}
                                        </td>
                                        <td>{{ Str::limit($type->description, 60) }}</td>
                                        <td>
                                            @can('edit_project_type')
                                            <a class="btn btn-sm btn-primary" href="{{ route('project_types.edit', $type->id) }}">Edit</a>
                                            @endcan
                                            @can('delete_project_type')
                                            <a class="btn btn-sm btn-danger modal-effect" data-effect="effect-scale" data-toggle="modal" href="#delete-type-{{ $type->id }}">Delete</a>
                                            @endcan
                                        </td>
                                    </tr>
                                    <div class="modal" id="delete-type-{{ $type->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Delete Type</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project_types.destroy', $type->id) }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this type?</p>
                                                        <input class="form-control" value="{{ $type->name }}" type="text" readonly>
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
                    {{ $types->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection