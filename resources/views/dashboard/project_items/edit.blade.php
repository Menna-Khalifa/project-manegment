@extends('dashboard.layouts.master')

@section('title')
    Edit Project Item
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-items.index') }}">Project Items</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Edit Project Item
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
                        <h4 class="card-title mg-b-0">Edit Project Item</h4>
                        <div>
                            <a class="btn btn-info" href="{{ route('project-items.show', $projectItem->id) }}">
                                <i class="las la-eye"></i> View Project Item
                            </a>
                            <a class="btn btn-secondary" href="{{ route('project-items.index') }}">
                                <i class="las la-arrow-left"></i> Back to Project Items
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('project-items.update', $projectItem->id) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id">Project <span class="text-danger">*</span></label>
                                    <select class="form-control @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id', $projectItem->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="section_id">Section <span class="text-danger">*</span></label>
                                    <select class="form-control @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ old('section_id', $projectItem->section_id) == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="section_item_id">Section Item <span class="text-danger">*</span></label>
                                    <select class="form-control @error('section_item_id') is-invalid @enderror" id="section_item_id" name="section_item_id" required>
                                        <option value="">Select Section Item</option>
                                        @foreach($sectionItems as $item)
                                            <option value="{{ $item->id }}" data-section="{{ $item->section_id }}" 
                                                {{ old('section_item_id', $projectItem->section_item_id) == $item->id ? 'selected' : '' }}
                                                style="{{ $item->section_id != $projectItem->section_id ? 'display:none' : '' }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_item_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('qty') is-invalid @enderror" 
                                           id="qty" name="qty" value="{{ old('qty', $projectItem->qty) }}" min="1" required>
                                    @error('qty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="received_qty">Received Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('received_qty') is-invalid @enderror" 
                                           id="received_qty" name="received_qty" value="{{ old('received_qty', $projectItem->received_qty) }}" 
                                           min="0" max="{{ old('qty', $projectItem->qty) }}" required>
                                    @error('received_qty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="executed_qty">Executed Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('executed_qty') is-invalid @enderror" 
                                           id="executed_qty" name="executed_qty" value="{{ old('executed_qty', $projectItem->executed_qty) }}" 
                                           min="0" max="{{ old('received_qty', $projectItem->received_qty) }}" required>
                                    @error('executed_qty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expected_arrival">Expected Arrival <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('expected_arrival') is-invalid @enderror" 
                                           id="expected_arrival" name="expected_arrival" 
                                           value="{{ old('expected_arrival', $projectItem->expected_arrival ? $projectItem->expected_arrival->format('Y-m-d') : '') }}" required>
                                    @error('expected_arrival')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-save"></i> Update Project Item
                            </button>
                            <a href="{{ route('project-items.show', $projectItem->id) }}" class="btn btn-info">
                                <i class="las la-eye"></i> View Project Item
                            </a>
                            <a href="{{ route('project-items.index') }}" class="btn btn-secondary">
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
    // Filter section items based on selected section
    document.getElementById('section_id').addEventListener('change', function() {
        const sectionId = this.value;
        const sectionItemSelect = document.getElementById('section_item_id');
        const allOptions = sectionItemSelect.querySelectorAll('option[data-section]');
        
        // Reset selection
        sectionItemSelect.value = '';
        
        // Show/hide options based on section
        allOptions.forEach(option => {
            if (option.dataset.section == sectionId) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    });

    // Validate quantities
    document.getElementById('qty').addEventListener('input', function() {
        const qty = parseInt(this.value) || 0;
        const receivedInput = document.getElementById('received_qty');
        const executedInput = document.getElementById('executed_qty');
        
        receivedInput.max = qty;
        if (parseInt(receivedInput.value) > qty) {
            receivedInput.value = qty;
        }
        
        // Update executed quantity max as well
        const receivedQty = parseInt(receivedInput.value) || 0;
        executedInput.max = receivedQty;
        if (parseInt(executedInput.value) > receivedQty) {
            executedInput.value = receivedQty;
        }
    });

    document.getElementById('received_qty').addEventListener('input', function() {
        const receivedQty = parseInt(this.value) || 0;
        const executedInput = document.getElementById('executed_qty');
        
        executedInput.max = receivedQty;
        if (parseInt(executedInput.value) > receivedQty) {
            executedInput.value = receivedQty;
        }
    });
</script>
@endsection