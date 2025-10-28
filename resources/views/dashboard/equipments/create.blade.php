@extends('dashboard.layouts.master')

@section('title')
    Add Equipment
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('equipments.index') }}">Equipments</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Add Equipment
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('equipments.index') }}">Back</a>
                        </div>
                    </div><br>
                    <form class="needs-validation" action="{{ route('equipments.store') }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row mg-b-20">
                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>Name:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="Name" name="name"
                                        value="{{ old('name') }}" type="text">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>Count:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="Count" name="count"
                                        value="{{ old('count') }}" type="number">
                                    @error('count')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mg-b-20">
                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>Description:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="Description" name="description"
                                        value="{{ old('description') }}" type="text">
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                            <button class="btn btn-main-primary pd-x-20" type="submit">Sure</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
