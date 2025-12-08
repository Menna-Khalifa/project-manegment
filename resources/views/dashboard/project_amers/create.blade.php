@extends('dashboard.layouts.master')

@section('title')
    Add ProjectAmer
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project_amers.index') }}">ProjectAmer</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Add ProjectAmer
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
                        <h4 class="card-title mg-b-0">Add New ProjectAmer</h4>
                        <a class="btn btn-secondary" href="{{ route('project_amers.index') }}">
                            <i class="las la-arrow-left"></i> Back to Projects
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('project_amers.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="po_num">PO Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('po_num') is-invalid @enderror" id="po_num" name="po_num" value="{{ old('po_num') }}" required>
                                    @error('po_num')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dept">Department <span class="text-danger">*</span></label>
                                    <select class="form-control @error('dept') is-invalid @enderror" id="dept" name="dept" required>
                                        <option value="">Select Department</option>
                                        <option value="project" {{ old('dept') == 'project' ? 'selected' : '' }}>Project</option>
                                        <option value="facility" {{ old('dept') == 'facility' ? 'selected' : '' }}>Facility</option>
                                        <option value="maintenance_replacing" {{ old('dept') == 'maintenance_replacing' ? 'selected' : '' }}>Maintenance / Replacing</option>
                                        <option value="maintenance" {{ old('dept') == 'maintenance' ? 'selected' : '' }}>Maintenance / Maintenance</option>
                                        <option value="other" {{ old('dept') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('dept')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="region">Region <span class="text-danger">*</span></label>
                                    <select class="form-control @error('region') is-invalid @enderror" id="region" name="region" required>
                                        <option value="">Select Region</option>
                                        <option value="western_province" {{ old('region') == 'western_province' ? 'selected' : '' }}>Western Province</option>
                                        <option value="central_province" {{ old('region') == 'central_province' ? 'selected' : '' }}>Central Province</option>
                                        <option value="eastern_province" {{ old('region') == 'eastern_province' ? 'selected' : '' }}>Eastern Province</option>
                                        <option value="general" {{ old('region') == 'general' ? 'selected' : '' }}>General</option>
                                    </select>
                                    @error('region')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="store_id">Store <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('store_id') is-invalid @enderror" id="store_id" name="store_id" required>
                                        <option value="">Select Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->uuid }}</option>
                                        @endforeach
                                    </select>
                                    @error('store_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">User <span class="text-danger">*</span></label>
                                    <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name ?? $user->email }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="po_file">PO File</label>
                                    <input type="file" class="form-control @error('po_file') is-invalid @enderror" id="po_file" name="po_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    @error('po_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Priority <span class="text-danger">*</span></label>
                                    <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                        <option value="">Select Priority</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date') }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="request_status">Request Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('request_status') is-invalid @enderror" id="request_status" name="request_status" required>
                                        <option value="">Select Status</option>
                                        <option value="new_order" {{ old('request_status') == 'new_order' ? 'selected' : '' }}>New Order</option>
                                        <option value="under_working" {{ old('request_status') == 'under_working' ? 'selected' : '' }}>Under Working</option>
                                        <option value="completed" {{ old('request_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="on_hold" {{ old('request_status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                        <option value="cancelled" {{ old('request_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('request_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="maintenance-section" style="display: none;" class="mt-3">
                            <div class="card">
                                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Maintenance Items</h5>
                                    <button type="button" class="btn btn-sm btn-primary" id="add-maintenance-row">Add Row</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="maintenance-table">
                                            <thead>
                                                <tr>
                                                    <th>Type of Maintenance</th>
                                                    <th>Model</th>
                                                    <th>Qty</th>
                                                    <th>Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="project-section" style="display: none;" class="mt-3">
                            <div class="card">
                                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Project Items</h5>
                                    <button type="button" class="btn btn-sm btn-primary" id="add-project-row">Add Row</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="project-table">
                                            <thead>
                                                <tr>
                                                    <th>Type of Project</th>
                                                    <th>Capacity</th>
                                                    <th>Volt</th>
                                                    <th>Qty</th>
                                                    <th>Brand</th>
                                                    <th>Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-save"></i> Save ProjectAmer
                            </button>
                            <a href="{{ route('project_amers.index') }}" class="btn btn-secondary">
                                <i class="las la-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const typesMaintenance = @json($typesMaintenance ?? []);
        const typesProject = @json($typesProject ?? []);
        const capacities = @json($capacities ?? []);
        const volts = @json($volts ?? []);
        const brands = @json($brands ?? []);
        const models = @json($models ?? []);

        function renderOptions(data, valueKey = 'id', labelKey = 'name') {
            return `<option value="">Select</option>` + data.map(d => `<option value="${d[valueKey]}">${d[labelKey] ?? d[valueKey]}</option>`).join('');
        }

        function filterModelsByType(typeId) {
            return models.filter(m => m.project_type_id === Number(typeId));
        }

        function toggleSections() {
            const dept = document.getElementById('dept').value;
            document.getElementById('maintenance-section').style.display = dept === 'maintenance' ? '' : 'none';
            document.getElementById('project-section').style.display = dept !== 'maintenance' && dept !== '' ? '' : 'none';
        }

        document.getElementById('dept').addEventListener('change', toggleSections);
        toggleSections();

        document.getElementById('add-maintenance-row').addEventListener('click', function() {
            const tbody = document.querySelector('#maintenance-table tbody');
            const index = tbody.children.length;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select class="form-control" name="items_maintenance[${index}][project_type_id]" onchange="onMaintenanceTypeChange(this, ${index})">
                        ${renderOptions(typesMaintenance, 'id', 'name')}
                    </select>
                </td>
                <td>
                    <select class="form-control" name="items_maintenance[${index}][project_model_id]" data-model-select>
                        <option value="">Select</option>
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="items_maintenance[${index}][qty]" min="1" value="1" />
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Remove</button>
                </td>
            `;
            tbody.appendChild(row);
        });

        window.onMaintenanceTypeChange = function(select, index) {
            const typeId = select.value;
            const row = select.closest('tr');
            const modelSelect = row.querySelector('[data-model-select]');
            const filtered = filterModelsByType(typeId);
            modelSelect.innerHTML = renderOptions(filtered, 'id', 'name');
        }

        document.getElementById('add-project-row').addEventListener('click', function() {
            const tbody = document.querySelector('#project-table tbody');
            const index = tbody.children.length;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select class="form-control" name="items_project[${index}][project_type_id]">
                        ${renderOptions(typesProject, 'id', 'name')}
                    </select>
                </td>
                <td>
                    <select class="form-control" name="items_project[${index}][project_capacity_id]">
                        ${renderOptions(capacities, 'id', 'name')}
                    </select>
                </td>
                <td>
                    <select class="form-control" name="items_project[${index}][project_volt_id]">
                        ${renderOptions(volts, 'id', 'value')}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="items_project[${index}][qty]" min="1" value="1" />
                </td>
                <td>
                    <select class="form-control select2" name="items_project[${index}][brand_id]">
                        ${renderOptions(brands, 'id', 'name')}
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Remove</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    </script>
@endsection
