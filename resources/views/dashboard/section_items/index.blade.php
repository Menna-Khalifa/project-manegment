@extends('dashboard.layouts.master')

@section('title')
    Section Items List
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('section_items.index') }}">Section Items</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Section Items List
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
                        @can('add_section_item')
                            <a class="btn btn-primary" href="{{ route('section_items.create') }}">
                                <i class="las la-user-plus"></i>
                                Add Section Item</a>
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
                                    <th>Section</th>
                                    @if (
                                            auth()->user()->can('edit_section_item') ||
                                            auth()->user()->can('delete_section_item'))
                                        <th>Processes</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>


                                @foreach ($section_items as $key => $section)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $section->name ?? __('general.not_found') }}</td>
                                        <td>{{ $section->description ?? __('general.not_found') }}</td>
                                        <td>{{ $section->section->name ?? __('general.not_found') }}</td>
                                        <!-- permission some -->
                                        @if (
                                                auth()->user()->can('edit_section_item') ||
                                                auth()->user()->can('delete_section_item'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">Processes&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('edit_section_item')
                                                            <a class="dropdown-item"
                                                                href="{{ route('section_items.edit', $section->id) }}"><i
                                                                    class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                        @endcan

                                                        @can('delete_section_item')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#modaldemo8-{{ $section->id }}"
                                                                title="{{ __('section_items.delete') }}"><i
                                                                    class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal" id="modaldemo8-{{ $section->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">{{ __('section_items.delete_section') }}</h6><button
                                                        aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('section_items.destroy', $section->id) }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>{{ __('section_items.are_you_sure_delete_section') }}</p><br>
                                                        <input type="hidden" name="section_id"
                                                            value="{{ $section->id }}">
                                                        <input class="form-control" name="section_name"
                                                            value="{{ $section->name }}" type="text" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ __('section_items.cancel') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ __('section_items.sure') }}</button>
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
