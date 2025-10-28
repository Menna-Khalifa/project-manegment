@extends('dashboard.layouts.master')

@section('title')
    Bulk Add Project Items
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('project-items.index') }}">Project Items</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        Bulk Add Project Items
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
                        <h4 class="card-title mg-b-0">Bulk Add Project Items</h4>
                        <a class="btn btn-secondary" href="{{ route('project-items.index') }}">
                            <i class="las la-arrow-left"></i> Back to Project Items
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="bulkAddForm" action="{{ route('project-items.store') }}" method="post">
                        @csrf

                        <!-- Project Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id">Select Project <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                                        <option value="">Choose Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>

                        <!-- Items Container -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Project Items</h5>
                                    <button type="button" class="btn btn-success btn-sm" id="addItemBtn">
                                        <i class="las la-plus"></i> Add Item
                                    </button>
                                </div>

                                <div id="itemsContainer">
                                    <!-- Initial item row -->
                                    <div class="item-row" data-index="0">
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
    <div class="form-group">
        <label>Section <span class="text-danger">*</span></label>
        <div class="input-group-custom">
            <select class="form-control section-select" name="items[0][section_id]" required>
                <option value="">Select Section</option>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-outline-primary add-btn add-section-btn" title="Add New Section">
                <i class="las la-plus"></i>
            </button>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>Section Item <span class="text-danger">*</span></label>
        <div class="input-group-custom">
            <select class="form-control section-item-select" name="items[0][section_item_id]" required>
                <option value="">Select Section Item</option>
            </select>
            <button type="button" class="btn btn-outline-primary add-btn add-section-item-btn" title="Add New Section Item">
                <i class="las la-plus"></i>
            </button>
        </div>
    </div>
</div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Quantity <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control qty-input" name="items[0][qty]" min="1" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Received Qty</label>
                                                            <input type="number" class="form-control received-qty-input" name="items[0][received_qty]" value="0" min="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Executed Qty</label>
                                                            <input type="number" class="form-control executed-qty-input" name="items[0][executed_qty]" value="0" min="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            <label>Custom Expected Arrival (Optional)</label>
                                                            <input type="date" class="form-control" name="items[0][custom_expected_arrival]" min="{{ date('Y-m-d') }}">
                                                            <small class="text-muted">Leave empty to use the global expected arrival date</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-block remove-item-btn" style="display: none;">
                                                                <i class="las la-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="las la-save"></i> Save All Project Items
                            </button>
                            <a href="{{ route('project-items.index') }}" class="btn btn-secondary btn-lg">
                                <i class="las la-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Add these modals before the closing </body> tag -->

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة قسم جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSectionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="section_name" class="form-label">اسم القسم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="section_name" name="name" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="section_description" class="form-label">الوصف</label>
                        <textarea class="form-control" id="section_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-save"></i> إضافة القسم
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Section Item Modal -->
<div class="modal fade" id="addSectionItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة عنصر جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSectionItemForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="item_section_id" class="form-label">اختر القسم <span class="text-danger">*</span></label>
                        <select class="form-control" id="item_section_id" name="section_id" required>
                            <option value="">اختر القسم</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="section_item_name" class="form-label">اسم العنصر <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="section_item_name" name="name" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="section_item_description" class="form-label">الوصف</label>
                        <textarea class="form-control" id="section_item_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-save"></i> إضافة العنصر
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div>جاري الإضافة...</div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Add this CSS for the add buttons -->
<style>
.add-btn {
    width: 35px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 5px;
    border-radius: 4px;
}

.input-group-custom {
    display: flex;
    align-items: end;
}

.input-group-custom .form-control {
    flex: 1;
}

.add-btn:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}
</style>
@endsection

@section('js')
<script>
    let itemIndex = 1;
    let currentTargetSelect = null;
    const sectionItemsData = @json($sectionItems);

    // Initialize modals
    const addSectionModal = new bootstrap.Modal(document.getElementById('addSectionModal'));
    const addSectionItemModal = new bootstrap.Modal(document.getElementById('addSectionItemModal'));
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    // Add new item row
    document.getElementById('addItemBtn').addEventListener('click', function() {
        addNewItemRow();
    });

    function addNewItemRow() {
        const container = document.getElementById('itemsContainer');
        const newRow = document.querySelector('.item-row').cloneNode(true);

        // Update data-index
        newRow.setAttribute('data-index', itemIndex);

        // Update all input names and IDs
        newRow.querySelectorAll('select, input').forEach(function(element) {
            const name = element.getAttribute('name');
            if (name) {
                element.setAttribute('name', name.replace(/\[\d+\]/, '[' + itemIndex + ']'));
            }

            // Clear values
            if (element.tagName === 'SELECT') {
                element.selectedIndex = 0;
            } else {
                element.value = element.name.includes('received_qty') || element.name.includes('executed_qty') ? '0' : '';
            }
        });

        // Show remove button
        newRow.querySelector('.remove-item-btn').style.display = 'block';

        container.appendChild(newRow);
        itemIndex++;

        // Update remove button visibility
        updateRemoveButtons();
    }

    // Remove item row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item-btn')) {
            e.target.closest('.item-row').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(function(row, index) {
            const removeBtn = row.querySelector('.remove-item-btn');
            if (rows.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    // Add Section Button Click
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-section-btn')) {
            const targetRow = e.target.closest('.item-row');
            currentTargetSelect = targetRow.querySelector('.section-select');
            addSectionModal.show();
        }
    });

    // Add Section Item Button Click
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-section-item-btn')) {
            const targetRow = e.target.closest('.item-row');
            const sectionSelect = targetRow.querySelector('.section-select');
            const selectedSectionId = sectionSelect.value;
            
            if (!selectedSectionId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'يرجى اختيار قسم أولاً'
                });
                return;
            }
            
            currentTargetSelect = targetRow.querySelector('.section-item-select');
            
            // Set the section in the modal
            document.getElementById('item_section_id').value = selectedSectionId;
            addSectionItemModal.show();
        }
    });

    // Add Section Form Submit
    document.getElementById('addSectionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const name = formData.get('name').trim();
        
        if (!name) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'اسم القسم مطلوب'
            });
            return;
        }

        // Show loading
        addSectionModal.hide();
        loadingModal.show();

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // AJAX call
        fetch('{{ route("sections.ajax-store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                description: formData.get('description')
            })
        })
        .then(response => response.json())
        .then(data => {
            loadingModal.hide();
            
            if (data.success) {
                // Add to all section selects
                const sectionSelects = document.querySelectorAll('.section-select');
                sectionSelects.forEach(select => {
                    const option = new Option(data.section.name, data.section.id);
                    select.add(option);
                });

                // Also add to section item modal select
                const modalSectionSelect = document.getElementById('item_section_id');
                const modalOption = new Option(data.section.name, data.section.id);
                modalSectionSelect.add(modalOption);
                
                // Select the new section in the target select automatically
                if (currentTargetSelect) {
                    currentTargetSelect.value = data.section.id;
                    // Trigger change event to load section items
                    const changeEvent = new Event('change', { bubbles: true });
                    currentTargetSelect.dispatchEvent(changeEvent);
                }
                
                // Reset form
                this.reset();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: data.message,
                    timer: 2000
                });
                
            } else {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: data.message
                });
            }
        })
        .catch(error => {
            loadingModal.hide();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'حدث خطأ أثناء إضافة القسم'
            });
        });
    });

    // Add Section Item Form Submit
    document.getElementById('addSectionItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const name = formData.get('name').trim();
        const sectionId = formData.get('section_id');
        
        if (!name || !sectionId) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'اسم العنصر والقسم مطلوبان'
            });
            return;
        }

        // Show loading
        addSectionItemModal.hide();
        loadingModal.show();

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // AJAX call
        fetch('{{ route("section_items.ajax-store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                description: formData.get('description'),
                section_id: sectionId
            })
        })
        .then(response => response.json())
        .then(data => {
            loadingModal.hide();
            
            if (data.success) {
                // Add to sectionItemsData
                sectionItemsData.push({
                    id: data.section_item.id,
                    name: data.section_item.name,
                    section_id: parseInt(data.section_item.section_id)
                });
                
                // Update the target section item select
                if (currentTargetSelect) {
                    const option = new Option(data.section_item.name, data.section_item.id);
                    currentTargetSelect.add(option);
                    currentTargetSelect.value = data.section_item.id;
                }
                
                // Reset form
                this.reset();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: data.message,
                    timer: 2000
                });
                
            } else {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: data.message
                });
            }
        })
        .catch(error => {
            loadingModal.hide();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'حدث خطأ أثناء إضافة العنصر'
            });
        });
    });

    // Filter section items based on selected section
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('section-select')) {
            const sectionId = e.target.value;
            const sectionItemSelect = e.target.closest('.item-row').querySelector('.section-item-select');

            // Clear section items
            sectionItemSelect.innerHTML = '<option value="">Select Section Item</option>';

            if (sectionId) {
                const filteredItems = sectionItemsData.filter(item => item.section_id == sectionId);
                filteredItems.forEach(item => {
                    const option = new Option(item.name, item.id);
                    sectionItemSelect.add(option);
                });
            }
        }
    });

    // Validate quantities
    document.addEventListener('input', function(e) {
        const row = e.target.closest('.item-row');
        if (!row) return;

        const qtyInput = row.querySelector('.qty-input');
        const receivedInput = row.querySelector('.received-qty-input');
        const executedInput = row.querySelector('.executed-qty-input');

        if (e.target.classList.contains('qty-input')) {
            const qty = parseInt(e.target.value) || 0;
            receivedInput.max = qty;
            if (parseInt(receivedInput.value) > qty) {
                receivedInput.value = qty;
            }
        }

        if (e.target.classList.contains('received-qty-input')) {
            const receivedQty = parseInt(e.target.value) || 0;
            executedInput.max = receivedQty;
            if (parseInt(executedInput.value) > receivedQty) {
                executedInput.value = receivedQty;
            }
        }
    });

    // Form validation
    document.getElementById('bulkAddForm').addEventListener('submit', function(e) {
        const projectId = document.getElementById('project_id').value;
        const expectedArrival = document.getElementById('expected_arrival')?.value;

        if (!projectId) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يرجى اختيار مشروع'
            });
            e.preventDefault();
            return;
        }

        // Validate at least one item
        const rows = document.querySelectorAll('.item-row');
        let hasValidItem = false;

        rows.forEach(function(row) {
            const sectionId = row.querySelector('.section-select').value;
            const sectionItemId = row.querySelector('.section-item-select').value;
            const qty = row.querySelector('.qty-input').value;

            if (sectionId && sectionItemId && qty) {
                hasValidItem = true;
            }
        });

        if (!hasValidItem) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يرجى إضافة عنصر واحد على الأقل (القسم وعنصر القسم والكمية مطلوبة)'
            });
            e.preventDefault();
            return;
        }
    });
</script>
@endsection
