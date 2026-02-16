@extends('layouts.app')

@section('title', 'Branches Management')
@section('page-title', 'Dealer Branches Management')
@section('page-description', 'Manage dealer branches')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBranchModal">
            <i class="bi bi-plus-circle"></i> Add New Branch
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>PIC</th>
                                <th>Users</th>
                                <th>Tickets</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branches as $branch)
                                <tr>
                                    <td>
                                        <strong>{{ $branch->branch_code }}</strong>
                                    </td>
                                    <td>{{ $branch->branch_name }}</td>
                                    <td>
                                        <small>{{ Str::limit($branch->address, 50) }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $branch->phone ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $branch->pic_name ?? '-' }}<br>
                                            <span class="text-muted">{{ $branch->pic_email ?? '' }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $branch->users_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $branch->tickets_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $branch->is_active ? 'success' : 'secondary' }}">
                                            {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="editBranch({{ $branch->id }}, '{{ $branch->branch_code }}', '{{ $branch->branch_name }}', '{{ $branch->address }}', '{{ $branch->phone }}', '{{ $branch->pic_name }}', '{{ $branch->pic_email }}', {{ $branch->is_active }})">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Branch Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.branches.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="branch_code" required>
                            <small class="text-muted">e.g., JKT-SLT, BDG, SBY</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="branch_name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">PIC Name</label>
                            <input type="text" class="form-control" name="pic_name">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PIC Email</label>
                        <input type="email" class="form-control" name="pic_email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Branch Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editBranchForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="branch_code" id="edit_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="branch_name" id="edit_name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" id="edit_address" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="edit_phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">PIC Name</label>
                            <input type="text" class="form-control" name="pic_name" id="edit_pic_name">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PIC Email</label>
                        <input type="email" class="form-control" name="pic_email" id="edit_pic_email">
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
function editBranch(id, code, name, address, phone, pic_name, pic_email, active) {
    $('#editBranchForm').attr('action', '/admin/branches/' + id);
    $('#edit_code').val(code);
    $('#edit_name').val(name);
    $('#edit_address').val(address);
    $('#edit_phone').val(phone);
    $('#edit_pic_name').val(pic_name);
    $('#edit_pic_email').val(pic_email);
    $('#edit_active').val(active ? '1' : '0');
    $('#editBranchModal').modal('show');
}
</script>
@endpush