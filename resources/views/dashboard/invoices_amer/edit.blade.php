<!-- resources/views/dashboard/invoices_amer/edit.blade.php -->
@extends('dashboard.layouts.master')

@section('title')
    Edit Americana Invoice
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('invoices_amer.index') }}">Americana Invoices</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Edit Invoice
    </li>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">Edit: {{ $invoice->invoice_number }}</h4>
                    <div>
                        @can('show_invoice_amer')
                            <a class="btn btn-info mr-2" href="{{ route('invoices_amer.show', $invoice->id) }}"><i
                                    class="fas fa-eye"></i> View</a>
                        @endcan
                        <a class="btn btn-secondary" href="{{ route('invoices_amer.index') }}"><i
                                class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices_amer.update', $invoice->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Americana Project</label>
                            <select name="project_amer_id" id="project_amer_id" disabled class="form-control select2"
                                required>
                                @foreach ($projects as $p)
                                    <option value="{{ $p->id }}" data-cost="{{ $p->amount }}"
                                        {{ old('project_amer_id', $invoice->project_amer_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->po_num }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_amer_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Project Info Display -->
                        <div class="row" id="project-info">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><strong>Project Information:</strong></h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Total Cost:</strong> <span
                                                id="total-cost">{{ number_format($invoice->projectAmer->amount, 2) }}</span>
                                            SAR
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control"
                                value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
                            @error('invoice_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                                name="date" value="{{ old('date', $invoice->date) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Payment Amount (SAR)</label>
                            <input type="number" step="0.01" min="0.01" max="{{ $invoice->projectAmer->amount }}"
                                placeholder="0.00" name="amount" id="amount" class="form-control"
                                value="{{ old('amount', $invoice->amount) }}" required>
                            @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="crane" name="crane"
                                        value="1" {{ old('crane', $invoice->crane) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="crane">Crane</label>
                                </div>
                                <input type="number" name="amount_crane" class="form-control"
                                    value="{{ old('amount_crane', $invoice->amount_crane) }}" placeholder="Crane Amount">
                                @error('amount_crane')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="capper_pipe" name="capper_pipe"
                                        value="1" {{ old('capper_pipe', $invoice->capper_pipe) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="capper_pipe">Capper Pipe</label>
                                </div>
                                <input type="number" name="amount_capper_pipe" class="form-control"
                                    value="{{ old('amount_capper_pipe', $invoice->amount_capper_pipe) }}"
                                    placeholder="Capper Pipe Amount">
                                @error('amount_capper_pipe')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="power_cable" name="power_cable"
                                        value="1" {{ old('power_cable', $invoice->power_cable) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="power_cable">Power Cable</label>
                                </div>
                                <input type="number" name="amount_power_cable" class="form-control"
                                    value="{{ old('amount_power_cable', $invoice->amount_power_cable) }}"
                                    placeholder="Power Cable Amount">
                                @error('amount_power_cable')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div> --}}

                        <div class="form-group mt-3">
                            <label>Payment File</label>
                            <input type="file" name="payment_file" class="form-control">
                            @if ($invoice->payment_file)
                                <small class="d-block mt-2">
                                    Current: <a href="{{ asset('storage/' . $invoice->payment_file) }}"
                                        target="_blank">{{ basename($invoice->payment_file) }}</a>
                                </small>
                            @endif
                            @error('payment_file')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="4">{{ old('notes', $invoice->notes) }}</textarea>
                            @error('notes')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('invoices_amer.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Project selection change handler
            $('#project_amer_id').on('change', function() {
                var selectedOption = $(this).find(':selected');
                if (selectedOption.val()) {
                    var cost = parseFloat(selectedOption.data('cost'));

                    $('#total-cost').text(cost.toFixed(2));

                    $('#project-info').show();

                    // Set max amount for payment
                    $('#amount').attr('max', cost.toFixed(2));
                } else {
                    $('#project-info').hide();
                    $('#amount').attr('max', '999999.99');
                }
            });

            // Amount validation
            $('#amount').on('input', function() {
                var amount = parseFloat($(this).val());
                var maxAmount = parseFloat($(this).attr('max'));

                if (amount > maxAmount) {
                    $(this).addClass('is-invalid');
                    if (!$(this).next('.invalid-feedback').length) {
                        $(this).after(
                            '<div class="invalid-feedback">Amount cannot exceed total project amount</div>'
                            );
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            // Form validation before submit
            $('form').on('submit', function(e) {
                var amount = parseFloat($('#amount').val());
                var maxAmount = parseFloat($('#amount').attr('max'));

                if (amount > maxAmount) {
                    e.preventDefault();
                    alert('Payment amount cannot exceed the total project amount.');
                    return false;
                }
            });
        });
    </script>
@endsection
