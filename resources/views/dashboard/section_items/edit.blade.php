@extends('dashboard.layouts.master')

@section('title')
    Edit Section Item
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('section_items.index') }}">Section Items</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Edit Section Item
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
                            <a class="btn btn-primary btn-sm" href="{{ route('section_items.index') }}">Back</a>
                        </div>
                    </div><br>
                    <form class="needs-validation" action="{{ route('section_items.update', $section_item->id) }}"
                        method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row mg-b-20">
                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>Name:<span class="tx-danger">*</span></label>
                                    <input class="form-control" placeholder="Name" name="name"
                                        value="{{ $section_item->name }}" type="text">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label>Section:<span class="tx-danger">*</span></label>
                                    <select class="form-control select2" name="section_id">
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}" {{ $section_item->section_id == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
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
                                        value="{{ $section_item->description }}" type="text">
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
