@extends('dashboard.layouts.master')

@section('title')
    Edit Invoice
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('invoices.index') }}">Project Invoices</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Edit Invoice
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">Edit Invoice: {{ $invoice->invoice_number }}</h4>
                        <div>
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm mr-2">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Current Status Alert -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Current Status:</strong>
                                @if($invoice->status === 'pending')
                                    <span class="badge badge-warning ml-2">Pending</span>
                                @elseif($invoice->status === 'approved')
                                    <span class="badge badge-success ml-2">Approved</span>
                                @else
                                    <span class="badge badge-danger ml-2">Rejected</span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <strong>Created:</strong> {{ $invoice->created_at->format('Y-m-d H:i') }}
                            </div>
                            @if($invoice->approved_at)
                            <div class="col-md-3">
                                <strong>{{ $invoice->status === 'approved' ? 'Approved' : 'Rejected' }}:</strong>
                                {{ $invoice->approved_at->format('Y-m-d H:i') }}
                            </div>
                            <div class="col-md-3">
                                <strong>By:</strong> {{ $invoice->approvedBy->name ?? 'Unknown' }}
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($invoice->status === 'approved')
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Warning:</strong> This invoice has been approved. Editing it may affect project payment calculations.
                        </div>
                    @endif

                    <form action="{{ route('invoices.update', $invoice->id) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Project Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id">Project <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('project_id') is-invalid @enderror"
                                            name="project_id" id="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}"
                                                    {{ (old('project_id', $invoice->project_id) == $project->id) ? 'selected' : '' }}
                                                    data-cost="{{ $project->project_cost }}"
                                                    data-paid="{{ $project->total_paid }}"
                                                    data-remaining="{{ $project->remaining_amount }}">
                                                {{ $project->name }} - {{ $project->po_num }}
                                                (Remaining: {{ number_format($project->remaining_amount, 2) }} SAR)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Invoice Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_number">Invoice Number <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('invoice_number') is-invalid @enderror"
                                           name="invoice_number"
                                           id="invoice_number"
                                           value="{{ old('invoice_number', $invoice->invoice_number) }}"
                                           placeholder="INV-XXXX-XXX"
                                           required>
                                    @error('invoice_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Project Info Display -->
                        <div class="row" id="project-info">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><strong>Project Information:</strong></h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Total Cost:</strong> <span id="total-cost">{{ number_format($invoice->project->project_cost, 2) }}</span> SAR
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Already Paid:</strong> <span id="already-paid">{{ number_format($invoice->project->total_paid, 2) }}</span> SAR
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Remaining:</strong> <span id="remaining-amount">{{ number_format($invoice->project->remaining_amount, 2) }}</span> SAR
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Payment Progress:</strong> <span id="payment-progress">{{ number_format($invoice->project->payment_progress, 1) }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Amount -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Payment Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">SAR</span>
                                        </div>
                                        <input type="number"
                                               class="form-control @error('amount') is-invalid @enderror"
                                               name="amount"
                                               id="amount"
                                               value="{{ old('amount', $invoice->amount) }}"
                                               step="0.01"
                                               min="0.01"
                                               max="{{ $invoice->project->remaining_amount + $invoice->amount }}"
                                               placeholder="0.00"
                                               required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Original amount: {{ number_format($invoice->amount, 2) }} SAR</small>
                                </div>
                            </div>

                            <!-- Payment File -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_file">Payment Receipt/Document</label>
                                    <input type="file"
                                           class="form-control @error('payment_file') is-invalid @enderror"
                                           name="payment_file"
                                           id="payment_file"
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    @error('payment_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave empty to keep current file. Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max: 5MB)</small>

                                    @if($invoice->payment_file)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $invoice->payment_file) }}"
                                               target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View Current File
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Notes -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              name="notes"
                                              id="notes"
                                              rows="4"
                                              placeholder="Add any additional notes about this payment...">{{ old('notes', $invoice->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- File Preview -->
                        <div class="row" id="file-preview" style="display: none;">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>New File Preview:</label>
                                    <div class="border p-3 rounded">
                                        <div id="preview-content"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="history.back()">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Invoice
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Project selection change handler
    $('#project_id').on('change', function() {
        var selectedOption = $(this).find(':selected');
        if (selectedOption.val()) {
            var cost = parseFloat(selectedOption.data('cost'));
            var paid = parseFloat(selectedOption.data('paid'));
            var remaining = parseFloat(selectedOption.data('remaining'));
            var progress = paid > 0 ? ((paid / cost) * 100).toFixed(1) : 0;

            $('#total-cost').text(cost.toFixed(2));
            $('#already-paid').text(paid.toFixed(2));
            $('#remaining-amount').text(remaining.toFixed(2));
            $('#payment-progress').text(progress + '%');

            // Update max amount (add current invoice amount to remaining)
            var currentAmount = {{ $invoice->amount }};
            $('#amount').attr('max', (remaining + currentAmount).toFixed(2));
        }
    });

    // Amount validation
    $('#amount').on('input', function() {
        var amount = parseFloat($(this).val());
        var maxAmount = parseFloat($(this).attr('max'));

        if (amount > maxAmount) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Amount cannot exceed available project amount</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    // File preview
    $('#payment_file').on('change', function() {
        var file = this.files[0];
        if (file) {
            var fileName = file.name;
            var fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
            var fileType = file.type;

            var previewHtml = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-alt fa-3x text-primary mr-3"></i>
                    <div>
                        <h6 class="mb-1">${fileName}</h6>
                        <small class="text-muted">Size: ${fileSize} MB</small><br>
                        <small class="text-muted">Type: ${fileType}</small>
                    </div>
                </div>
            `;

            $('#preview-content').html(previewHtml);
            $('#file-preview').show();
        } else {
            $('#file-preview').hide();
        }
    });

    // Form validation before submit
    $('form').on('submit', function(e) {
        var amount = parseFloat($('#amount').val());
        var maxAmount = parseFloat($('#amount').attr('max'));

        if (amount > maxAmount) {
            e.preventDefault();
            alert('Payment amount cannot exceed the available project amount.');
            return false;
        }
    });
});
</script>
@endsection
