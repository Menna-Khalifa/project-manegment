@extends('dashboard.layouts.master')

@section('title')
    Equipments List
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('equipments.index') }}">Equipments</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Equipments List
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
                        @can('add_equipment')
                            <a class="btn btn-primary" href="{{ route('equipments.create') }}">
                                <i class="las la-user-plus"></i>
                                Add equipment</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table key-buttons text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Count</th>
                                    @if (
                                            auth()->user()->can('edit_equipment') ||
                                            auth()->user()->can('delete_equipment'))
                                        <th>Processes</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>


                                @foreach ($equipments as $key => $equipment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $equipment->name ?? __('general.not_found') }}</td>
                                        <td>{{ $equipment->description ?? __('general.not_found') }}</td>
                                        <td>{{ $equipment->count }}</td>
                                        <!-- permission some -->
                                        @if (
                                                auth()->user()->can('edit_equipment') ||
                                                auth()->user()->can('delete_equipment'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">Processes&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('edit_equipment')
                                                            <a class="dropdown-item"
                                                                href="{{ route('equipments.edit', $equipment->id) }}"><i
                                                                    class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                        @endcan

                                                        @can('delete_equipment')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#modaldemo8-{{ $equipment->id }}"
                                                                title="{{ __('equipments.delete') }}"><i
                                                                    class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal" id="modaldemo8-{{ $equipment->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">{{ __('equipments.delete_equipment') }}</h6><button
                                                        aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('equipments.destroy', $equipment->id) }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>{{ __('equipments.are_you_sure_delete_equipment') }}</p><br>
                                                        <input type="hidden" name="equipment_id"
                                                            value="{{ $equipment->id }}">
                                                        <input class="form-control" name="equipment_name"
                                                            value="{{ $equipment->name }}" type="text" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ __('equipments.cancel') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ __('equipments.sure') }}</button>
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
