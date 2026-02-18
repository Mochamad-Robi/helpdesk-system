@extends('layouts.app')

@section('title', 'Manage Sub-Categories')
@section('page-title', 'Manage Sub-Categories')
@section('page-description', $category->category_name)

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Categories
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
                <i class="bi bi-plus-circle"></i> Add Sub-Category
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">{{ $category->icon }} {{ $category->category_name }} - Sub-Categories</h6>
            </div>
            <div class="card-body">
                @if($category->subCategories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Sub-Category Name</th>
                                    <th>Priority</th>
                                    <th>SLA</th>
                                    <th>Default Specialist</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->subCategories as $subCat)
                                    <tr>
                                        <td>
                                            <strong>{{ $subCat->sub_category_name }}</strong><br>
                                            <small class="text-muted">{{ $subCat->description }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-priority-{{ $subCat->priority }}">
                                                {{ strtoupper($subCat->priority) }}
                                            </span>
                                        </td>
                                        <td>{{ $subCat->sla_display }}</td>
                                        <td>
                                            {{ $subCat->defaultSpecialist->name ?? 'Not Set' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $subCat->is_active ? 'success' : 'secondary' }}">
                                                {{ $subCat->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary"
                                                    onclick="editSubCategory({{ $subCat->id }}, '{{ $subCat->sub_category_name }}', '{{ $subCat->priority }}', {{ $subCat->sla_minutes }}, {{ $subCat->default_specialist_id ?? 'null' }}, '{{ addslashes($subCat->description ?? '') }}', {{ $subCat->is_active }})">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
                        <p class="text-muted mt-3">No sub-categories yet</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
                            <i class="bi bi-plus-circle"></i> Add First Sub-Category
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Sub-Category Modal -->
<div class="modal fade" id="addSubCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.subcategories.store', $category->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Sub-Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Sub-Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="sub_category_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority <span class="text-danger">*</span></label>
                        <select class="form-select" name="priority" required>
                            <option value="high">High</option>
                            <option value="medium" selected>Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SLA (minutes) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="sla_minutes" value="120" required>
                        <small class="text-muted">Examples: 30 (30 min), 120 (2 hours), 1440 (1 day)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Specialist</label>
                        <select class="form-select" name="default_specialist_id">
                            <option value="">Not Set</option>
                            @foreach($helpdeskUsers as $helpdesk)
                                <option value="{{ $helpdesk->id }}">{{ $helpdesk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Sub-Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Sub-Category Modal -->
<div class="modal fade" id="editSubCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSubCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sub-Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Sub-Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="sub_category_name" id="edit_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority <span class="text-danger">*</span></label>
                        <select class="form-select" name="priority" id="edit_priority" required>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SLA (minutes) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="sla_minutes" id="edit_sla" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Specialist</label>
                        <select class="form-select" name="default_specialist_id" id="edit_specialist">
                            <option value="">Not Set</option>
                            @foreach($helpdeskUsers as $helpdesk)
                                <option value="{{ $helpdesk->id }}">{{ $helpdesk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="is_active" id="edit_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editSubCategory(id, name, priority, sla, specialist, description, active) {
    $('#editSubCategoryForm').attr('action', '/admin/subcategories/' + id);
    $('#edit_name').val(name);
    $('#edit_priority').val(priority);
    $('#edit_sla').val(sla);
    $('#edit_specialist').val(specialist || '');
    $('#edit_description').val(description || '');
    $('#edit_active').val(active ? '1' : '0');
    $('#editSubCategoryModal').modal('show');
}
</script>
@endpush