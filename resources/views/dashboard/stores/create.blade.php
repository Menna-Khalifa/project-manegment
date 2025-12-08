@extends('dashboard.layouts.master')

@section('title')
    Add store
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('stores.index') }}">stores</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Add store
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
                            <a class="btn btn-primary btn-sm" href="{{ route('stores.index') }}">Back</a>
                        </div>
                    </div><br>
                    <form class="needs-validation" action="{{ route('stores.store') }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-6 mg-b-20">
                                <div class="form-group mg-b-0">
                                    <label>Brand:<span class="tx-danger">*</span></label>
                                    <select class="form-control select2" name="brand_id">
                                        @foreach ($brands as $brand)
                                            <option value="">Select Brand</option>
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mg-b-20">
                                <div class="form-group mg-b-0">
                                    <label>UUID Store:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="UUID Store" name="uuid"
                                        value="{{ old('uuid') }}" type="text">
                                    @error('uuid')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mg-b-20">
                                <div class="form-group mg-b-0">
                                    <label>Name:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="Name" name="name"
                                        value="{{ old('name') }}" type="text">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mg-b-20">
                                <div class="form-group mg-b-0">
                                    <label>Email:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="Email" name="email"
                                        value="{{ old('email') }}" type="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mg-b-20">
                                <div class="form-group mg-b-0">
                                    <label>Phone:</label>
                                    <input class="form-control" placeholder="Phone" name="phone"
                                        value="{{ old('phone') }}" type="text">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mg-b-20">
                                <div class="form-group mg-b-0">
                                    <label>Country:</label>
                                    <input class="form-control" placeholder="Country" name="country"
                                        value="{{ old('country') }}" type="text">
                                    @error('country')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mg-b-20">
                                <div class="form-group mg-b-0">
                                    <label>City:</label>
                                    <input class="form-control" placeholder="City" name="city"
                                        value="{{ old('city') }}" type="text">
                                    @error('city')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mg-b-20">
                                <div class="form-group mg-b-0">
                                    <label>State:</label>
                                    <input class="form-control" placeholder="State" name="state"
                                        value="{{ old('state') }}" type="text">
                                    @error('state')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mg-b-20">
                                <div class="form-group mg-b-0">
                                    <label>Address:</label>
                                    <input class="form-control" placeholder="Address" name="address"
                                        value="{{ old('address') }}" type="text">
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mg-b-20">
                                <div class="form-group mg-b-0">
                                    <label>Zip:</label>
                                    <input class="form-control" placeholder="Zip" name="zip"
                                        value="{{ old('zip') }}" type="text">
                                    @error('zip')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                            <button class="btn btn-main-primary pd-x-20" type="submit">Save</button>
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
