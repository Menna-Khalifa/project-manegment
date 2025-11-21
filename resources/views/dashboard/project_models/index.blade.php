@extends('dashboard.layouts.master')

@section('title')
    Project Models
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_models.index') }}">Project Models</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        List
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                @can('add_project_model')
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        <a class="btn btn-primary" href="{{ route('project_models.create') }}">
                            <i class="las la-plus"></i>
                            Add Model</a>
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
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($models as $model)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $model->name }}
                                        </td>
                                        <td>{{ $model->projectType->name ?? '-' }}</td>
                                        <td>{{ Str::limit($model->description, 60) }}</td>
                                        <td>
                                            @can('edit_project_model')
                                            <a class="btn btn-sm btn-primary" href="{{ route('project_models.edit', $model->id) }}">Edit</a>
                                            @endcan
                                            @can('delete_project_model')
                                            <a class="btn btn-sm btn-danger modal-effect" data-effect="effect-scale" data-toggle="modal" href="#delete-model-{{ $model->id }}">Delete</a>
                                            @endcan
                                        </td>
                                    </tr>
                                    <div class="modal" id="delete-model-{{ $model->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Delete Model</h6>
                                                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('project_models.destroy', $model->id) }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this model?</p>
                                                        <input class="form-control" value="{{ $model->name }}" type="text" readonly>
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
                    {{ $models->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection