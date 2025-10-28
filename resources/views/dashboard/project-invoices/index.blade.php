@extends('dashboard.layouts.master')

@section('title')
    Project Invoices List
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('invoices.index') }}">Project Invoices</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Invoices List
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
                        @can('add_invoice')
                            <a class="btn btn-primary" href="{{ route('invoices.create') }}">
                                <i class="las la-file-invoice"></i>
                                Add Invoice</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table key-buttons text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Invoice Number</th>
                                    <th>Project</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Uploaded Date</th>
                                    @can('view_payment_invoice')
                                        <th>Payment File</th>
                                    @endcan
                                    @if (auth()->user()->can('edit_invoice') || auth()->user()->can('delete_invoice') || auth()->user()->can('show_invoice'))
                                        <th>Processes</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $key => $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $invoice->invoice_number ?? __('general.not_found') }}</td>
                                        <td>
                                            <strong>{{ $invoice->project->name ?? __('general.not_found') }}</strong><br>
                                            <small class="text-muted">PO: {{ $invoice->project->po_num ?? '' }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ number_format($invoice->amount, 2) }} SAR</strong>
                                        </td>
                                        <td>
                                            @if ($invoice->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($invoice->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                        @can('view_payment_invoice')
                                            <td>
                                                @if ($invoice->payment_file)
                                                    <a href="{{ asset('storage/' . $invoice->payment_file) }}" target="_blank"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> View File
                                                    </a>
                                                @else
                                                    <span class="text-muted">No File</span>
                                                @endif
                                            </td>
                                        @endcan
                                        <!-- permission some -->
                                        @if (auth()->user()->can('edit_invoice') || auth()->user()->can('delete_invoice') || auth()->user()->can('show_invoice'))
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">Processes&nbsp;&nbsp;<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('show_invoice')
                                                            <a class="dropdown-item"
                                                                href="{{ route('invoices.show', $invoice->id) }}"><i
                                                                    class="text-info fas fa-eye"></i>&nbsp;&nbsp;View</a>
                                                        @endcan

                                                        @can('edit_invoice')
                                                            <a class="dropdown-item"
                                                                href="{{ route('invoices.edit', $invoice->id) }}"><i
                                                                    class="text-primary fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                        @endcan

                                                        @if ($invoice->status === 'pending' && auth()->user()->can('approve_invoice'))
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal"
                                                                href="#approveModal-{{ $invoice->id }}"
                                                                title="Approve Invoice"><i
                                                                    class="text-success fas fa-check"></i>&nbsp;&nbsp;Approve</a>

                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#rejectModal-{{ $invoice->id }}"
                                                                title="Reject Invoice"><i
                                                                    class="text-warning fas fa-times"></i>&nbsp;&nbsp;Reject</a>
                                                        @endif

                                                        @can('delete_invoice')
                                                            <a class="dropdown-item modal-effect" data-effect="effect-scale"
                                                                data-toggle="modal" href="#deleteModal-{{ $invoice->id }}"
                                                                title="Delete Invoice"><i
                                                                    class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Approve Modal -->
                                    <div class="modal" id="approveModal-{{ $invoice->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Approve Invoice</h6><button aria-label="Close"
                                                        class="close" data-dismiss="modal" type="button"><span
                                                            aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('invoices.approve', $invoice->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to approve this invoice?</p><br>
                                                        <div class="form-group">
                                                            <label>Invoice Number:</label>
                                                            <input class="form-control"
                                                                value="{{ $invoice->invoice_number }}" type="text"
                                                                readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Amount:</label>
                                                            <input class="form-control"
                                                                value="${{ number_format($invoice->amount, 2) }}"
                                                                type="text" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Notes (Optional):</label>
                                                            <textarea class="form-control" name="notes" rows="3" placeholder="Add approval notes..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Approve</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal" id="rejectModal-{{ $invoice->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Reject Invoice</h6><button aria-label="Close"
                                                        class="close" data-dismiss="modal" type="button"><span
                                                            aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('invoices.reject', $invoice->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to reject this invoice?</p><br>
                                                        <div class="form-group">
                                                            <label>Invoice Number:</label>
                                                            <input class="form-control"
                                                                value="{{ $invoice->invoice_number }}" type="text"
                                                                readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Amount:</label>
                                                            <input class="form-control"
                                                                value="${{ number_format($invoice->amount, 2) }}"
                                                                type="text" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Rejection Reason:</label>
                                                            <textarea class="form-control" name="notes" rows="3" placeholder="Please specify rejection reason..."
                                                                required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning">Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal" id="deleteModal-{{ $invoice->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Delete Invoice</h6><button aria-label="Close"
                                                        class="close" data-dismiss="modal" type="button"><span
                                                            aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form action="{{ route('invoices.destroy', $invoice->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this invoice?</p><br>
                                                        <input class="form-control" name="invoice_number"
                                                            value="{{ $invoice->invoice_number }}" type="text"
                                                            readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </form>
                                            </div>
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
