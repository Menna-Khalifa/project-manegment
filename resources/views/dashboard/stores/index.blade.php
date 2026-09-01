@extends('dashboard.layouts.master')

@section('title')
    stores List
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('stores.index') }}">stores</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        stores List
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <!-- Filter Card -->
            <div class="card mb-3">
                <div class="card-header pb-0">
                    <h5>Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('stores.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Search</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Name, Email, Phone, UUID">
                            </div>
                            <div class="col-md-3">
                                <label>Brand</label>
                                <select class="form-control" name="brand_id">
                                    <option value="">All Brands</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Country</label>
                                <input type="text" class="form-control" name="country" value="{{ request('country') }}"
                                    placeholder="Country">
                            </div>
                            <div class="col-md-2">
                                <label>City</label>
                                <input type="text" class="form-control" name="city" value="{{ request('city') }}"
                                    placeholder="City">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary btn-block mr-1">Filter</button>
                                    <a href="{{ route('stores.index') }}" class="btn btn-secondary btn-block">Clear</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        @can('add_store')
                            <a class="btn btn-primary" href="{{ route('stores.create') }}">
                                <i class="las la-user-plus"></i>
                                Add store</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered border-primary">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Uuid</th>
                                    <th>Brand Name</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>State</th>
                                    @if (auth()->user()->can('edit_store') || auth()->user()->can('delete_store'))
                                        <th>Processes</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>


                                @foreach ($stores as $key => $store)
                                    <tr>
                                        <td>
                                            {{ ($stores->currentPage() - 1) * $stores->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $store->uuid }}</td>
                                        <td>{{ $store->brand->name ?? __('general.not_found') }}</td>
                                        <td>{{ $store->name ?? __('general.not_found') }}</td>
                                        <td>{{ $store->email ?? __('general.not_found') }}</td>
                                        <td>{{ $store->phone ?? __('general.not_found') }}</td>
                                        <td>{{ $store->country ?? __('general.not_found') }}</td>
                                        <td>{{ $store->city ?? __('general.not_found') }}</td>
                                        <td>{{ $store->state ?? __('general.not_found') }}</td>
                                        <!-- permission some -->
                                        @if (auth()->user()->can('edit_store') || auth()->user()->can('delete_store'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">Processes&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('edit_store')
                                                            <a class="dropdown-item"
                                                                href="{{ route('stores.edit', $store->id) }}"><i
                                                                    class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                        @endcan

                                                        @can('delete_store')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#modaldemo8-{{ $store->id }}"
                                                                title="{{ __('stores.delete') }}"><i
                                                                    class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal" id="modaldemo8-{{ $store->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">{{ __('stores.delete_store') }}</h6><button
                                                        aria-label="Close" class="close" data-dismiss="modal"
                                                        type="button"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('stores.destroy', $store->id) }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>{{ __('stores.are_you_sure_delete_store') }}</p><br>
                                                        <input type="hidden" name="store_id" value="{{ $store->id }}">
                                                        <input class="form-control" name="store_name"
                                                            value="{{ $store->name }} ({{ $store->uuid }})"
                                                            type="text" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ __('stores.cancel') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ __('stores.sure') }}</button>
                                                    </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $stores->appends(request()->query())->links('component.pagination', ['items' => $stores]) }}
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
