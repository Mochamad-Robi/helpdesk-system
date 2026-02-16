@extends('layouts.app')

@section('title', 'Create New Ticket')
@section('page-title', 'Create New Ticket')
@section('page-description', 'Submit a new IT support ticket')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> New Support Ticket</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dealer.tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Branch (Auto-filled) -->
                    <div class="mb-3">
                        <label class="form-label">Branch</label>
                        <input type="text" 
                               class="form-control" 
                               value="{{ auth()->user()->dealerBranch->full_name }}" 
                               disabled>
                        <small class="text-muted">Your branch is automatically selected</small>
                    </div>
                    
                    <!-- Category -->
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" 
                                name="category_id" 
                                required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon }} {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Sub-Category (Dynamic) -->
                    <div class="mb-3">
                        <label class="form-label">Sub-Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('sub_category_id') is-invalid @enderror" 
                                id="sub_category_id" 
                                name="sub_category_id" 
                                required>
                            <option value="">-- Select Category First --</option>
                        </select>
                        @error('sub_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Priority & SLA Display (Auto-filled) -->
                    <div id="sla_info" class="alert alert-info d-none mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Priority:</strong>
                                <span id="priority_display" class="badge ms-2"></span>
                            </div>
                            <div class="col-md-4">
                                <strong>SLA Response Time:</strong>
                                <span id="sla_display" class="ms-2"></span>
                            </div>
                            <div class="col-md-4">
                                <strong>Assigned To:</strong>
                                <span id="specialist_display" class="ms-2"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subject -->
                    <div class="mb-3">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('subject') is-invalid @enderror" 
                               name="subject" 
                               value="{{ old('subject') }}" 
                               placeholder="Brief summary of your issue"
                               maxlength="200"
                               required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" 
                                  rows="6" 
                                  placeholder="Please describe your issue in detail..."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Provide as much detail as possible to help us resolve your issue quickly
                        </small>
                    </div>
                    
                    <!-- Attachment -->
                    <div class="mb-3">
                        <label class="form-label">Attachment (Optional)</label>
                        <input type="file" 
                               class="form-control @error('attachment') is-invalid @enderror" 
                               name="attachment"
                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Max 5MB. Supported: JPG, PNG, PDF, DOC, DOCX, XLS, XLSX
                        </small>
                    </div>
                    
                    <hr>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dealer.tickets.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // When category changes, load sub-categories
    $('#category_id').on('change', function() {
        var categoryId = $(this).val();
        var subCategorySelect = $('#sub_category_id');
        var slaInfo = $('#sla_info');
        
        // Reset sub-category
        subCategorySelect.html('<option value="">-- Loading... --</option>');
        slaInfo.addClass('d-none');
        
        if (categoryId) {
            $.ajax({
                url: "{{ route('dealer.api.subcategories', ':id') }}".replace(':id', categoryId),
                method: 'GET',
                success: function(data) {
                    subCategorySelect.html('<option value="">-- Select Sub-Category --</option>');
                    
                    $.each(data, function(key, subCategory) {
                        subCategorySelect.append(
                            '<option value="'+ subCategory.id +'" ' +
                            'data-priority="'+ subCategory.priority +'" ' +
                            'data-sla="'+ subCategory.sla_minutes +'" ' +
                            'data-specialist="'+ (subCategory.default_specialist ? subCategory.default_specialist.name : 'IT Team') +'">' +
                            subCategory.sub_category_name +
                            '</option>'
                        );
                    });
                },
                error: function() {
                    subCategorySelect.html('<option value="">-- Error loading sub-categories --</option>');
                }
            });
        } else {
            subCategorySelect.html('<option value="">-- Select Category First --</option>');
        }
    });
    
    // When sub-category changes, show SLA info
    $('#sub_category_id').on('change', function() {
        var selected = $(this).find(':selected');
        var priority = selected.data('priority');
        var slaMinutes = selected.data('sla');
        var specialist = selected.data('specialist');
        
        if (priority) {
            // Format SLA display
            var slaDisplay = '';
            if (slaMinutes < 60) {
                slaDisplay = slaMinutes + ' minutes';
            } else if (slaMinutes < 1440) {
                slaDisplay = Math.round(slaMinutes / 60 * 10) / 10 + ' hours';
            } else {
                slaDisplay = Math.round(slaMinutes / 1440 * 10) / 10 + ' days';
            }
            
            // Priority badge color
            var priorityColor = priority === 'high' ? 'danger' : (priority === 'medium' ? 'warning' : 'success');
            
            // Show SLA info
            $('#priority_display').attr('class', 'badge bg-' + priorityColor + ' ms-2').text(priority.toUpperCase());
            $('#sla_display').text(slaDisplay);
            $('#specialist_display').text(specialist);
            $('#sla_info').removeClass('d-none');
        } else {
            $('#sla_info').addClass('d-none');
        }
    });
});
</script>
@endpush