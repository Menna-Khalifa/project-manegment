@extends('dashboard.layouts.master')

@section('title')
    Add New Invoice
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('invoices.index') }}">Project Invoices</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Add New Invoice
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
                        <h4 class="card-title mg-b-0">Add New Invoice</h4>
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="post" enctype="multipart/form-data">
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
                                                    {{ old('project_id') == $project->id ? 'selected' : '' }}
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
                                           value="{{ old('invoice_number') }}"
                                           placeholder="INV-XXXX-XXX"
                                           required>
                                    @error('invoice_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Project Info Display -->
                        <div class="row" id="project-info" style="display: none;">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><strong>Project Information:</strong></h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Total Cost:</strong> <span id="total-cost">0.00</span> SAR
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Already Paid:</strong> <span id="already-paid">0.00</span> SAR
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Remaining:</strong> <span id="remaining-amount">0.00</span> SAR
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Payment Progress:</strong> <span id="payment-progress">0%</span>
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
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number"
                                               class="form-control @error('amount') is-invalid @enderror"
                                               name="amount"
                                               id="amount"
                                               value="{{ old('amount') }}"
                                               step="0.01"
                                               min="0.01"
                                               max="999999.99"
                                               placeholder="0.00"
                                               required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Maximum remaining amount will be validated</small>
                                </div>
                            </div>

                            <!-- Payment File -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_file">Payment Receipt/Document <span class="text-danger">*</span></label>
                                    <input type="file"
                                           class="form-control @error('payment_file') is-invalid @enderror"
                                           name="payment_file"
                                           id="payment_file"
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                           required>
                                    @error('payment_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max: 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Notes -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes">Notes (Optional)</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              name="notes"
                                              id="notes"
                                              rows="4"
                                              placeholder="Add any additional notes about this payment...">{{ old('notes') }}</textarea>
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
                                    <label>File Preview:</label>
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
                                    <i class="fas fa-save"></i> Submit Invoice
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

            $('#project-info').show();

            // Set max amount for payment
            $('#amount').attr('max', remaining.toFixed(2));
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
                $(this).after('<div class="invalid-feedback">Amount cannot exceed remaining project amount</div>');
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
            alert('Payment amount cannot exceed the remaining project amount.');
            return false;
        }
    });
});
</script>
@endsection
