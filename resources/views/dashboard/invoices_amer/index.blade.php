<!-- resources/views/dashboard/invoices_amer/index.blade.php -->
@extends('dashboard.layouts.master')

@section('title')
    Americana Invoices List
@endsection

@section('page-header')
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('invoices_amer.index') }}">Americana Invoices</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Invoices List
    </li>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card">
            @can('add_invoice_amer')
            <div class="card-header pb-0">
                <div class="col-sm-1 col-md-2">
                    <a class="btn btn-primary" href="{{ route('invoices_amer.create') }}">
                        <i class="las la-file-invoice"></i> Add Invoice
                    </a>
                </div>
            </div>
            @endcan
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table key-buttons text-md-nowrap" id="example1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice Number</th>
                                <th>Americana Project</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Uploaded Date</th>
                                <th>Payment File</th>
                                <th>Processes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>
                                        <strong>{{ $invoice->projectAmer->po_num ?? ('#' . $invoice->projectAmer->id) }}</strong>
                                    </td>
                                    <td><strong>{{ number_format($invoice->amount, 2) }} SAR</strong></td>
                                    <td>
                                        @php
                                            $status = $invoice->status;
                                        @endphp
                                        @if ($status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif ($status === 'submitted')
                                            <span class="badge badge-info">Submitted</span>
                                        @elseif ($status === 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif ($status === 'ready_of_invoicing')
                                            <span class="badge badge-primary">Ready Of Invoicing</span>
                                        @elseif ($status === 'invoice_issuse')
                                            <span class="badge badge-dark">Invoice Issue</span>
                                        @elseif ($status === 'canceled')
                                            <span class="badge badge-danger">Canceled</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @if ($invoice->payment_file)
                                            <a href="{{ asset('storage/' . $invoice->payment_file) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View File
                                            </a>
                                        @else
                                            <span class="text-muted">No File</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn ripple btn-primary btn-sm" data-toggle="dropdown" type="button">
                                                Processes&nbsp;&nbsp;<i class="fas fa-caret-down ml-1"></i>
                                            </button>
                                            <div class="dropdown-menu tx-13">
                                                @can('show_invoice_amer')
                                                <a class="dropdown-item" href="{{ route('invoices_amer.show', $invoice->id) }}">
                                                    <i class="text-info fas fa-eye"></i>&nbsp;&nbsp;View
                                                </a>
                                                @endcan
                                                @can('edit_invoice_amer')
                                                <a class="dropdown-item" href="{{ route('invoices_amer.edit', $invoice->id) }}">
                                                    <i class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit
                                                </a>
                                                @endcan
                                            @can('delete_invoice_amer')
                                            <form action="{{ route('invoices_amer.destroy', $invoice->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="text-danger fas fa-trash"></i>&nbsp;&nbsp;Delete
                                                </button>
                                            </form>
                                            @endcan
                                            @can('approve_invoice_amer')
                                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#statusModal" data-id="{{ $invoice->id }}" data-status="{{ $invoice->status }}">
                                                <i class="text-secondary fas fa-exchange-alt"></i>&nbsp;&nbsp;Change Status
                                            </button>
                                            @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@can('approve_invoice_amer')
<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="statusForm" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="statusModalLabel">Change Invoice Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label for="modal_status">Status</label>
                <select name="status" id="modal_status" class="form-control">
                    @foreach ($availableStatuses as $status)
                        <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <textarea name="notes" class="form-control" rows="2" placeholder="Notes (optional)"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </div>
    </form>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#statusModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var invoiceId = button.data('id');
            var currentStatus = button.data('status');

            var action = "{{ route('invoices_amer.update-status', ':id') }}";
            action = action.replace(':id', invoiceId);
            $('#statusForm').attr('action', action);

            $('#modal_status').val(currentStatus);
        });
    });
</script>
@endcan
@endsection