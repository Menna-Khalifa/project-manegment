<!-- resources/views/dashboard/invoices_amer/create.blade.php -->
@extends('dashboard.layouts.master')

@section('title')
    Add Amer Invoice
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('invoices_amer.index') }}">Amer Invoices</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Add Invoice
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title mg-b-0">New Americana Invoice</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices_amer.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Americana Project</label>
                            <select name="project_amer_id" class="form-control select2" required>
                                <option value="">Select Project</option>
                                @foreach ($projects as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('project_amer_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->po_num }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_amer_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control"
                                value="{{ old('invoice_number') }}" required>
                            @error('invoice_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Amount (SAR)</label>
                            <input type="number" step="0.01" name="amount" class="form-control"
                                value="{{ old('amount') }}" required>
                            @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="crane" name="crane"
                                        value="1" {{ old('crane') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="crane">Crane</label>
                                </div>
                                <input type="number" name="amount_crane" class="form-control" placeholder="Crane Amount"
                                    value="{{ old('amount_crane') }}">
                                @error('amount_crane')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="capper_pipe" name="capper_pipe"
                                        value="1" {{ old('capper_pipe') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="capper_pipe">Capper Pipe</label>
                                </div>
                                <input type="number" name="amount_capper_pipe" class="form-control"
                                    placeholder="Capper Pipe Amount" value="{{ old('amount_capper_pipe') }}">
                                @error('amount_capper_pipe')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="power_cable" name="power_cable"
                                        value="1" {{ old('power_cable') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="power_cable">Power Cable</label>
                                </div>
                                <input type="number" name="amount_power_cable" class="form-control"
                                    placeholder="Power Cable Amount" value="{{ old('amount_power_cable') }}">
                                @error('amount_power_cable')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>Payment File</label>
                            <input type="file" name="payment_file" class="form-control" required>
                            @error('payment_file')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                            @error('notes')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Save Invoice</button>
                        <a href="{{ route('invoices_amer.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
