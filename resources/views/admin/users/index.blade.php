@extends('layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')
@section('page-description', 'Manage system users')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New User
        </a>
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Branch</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role == 'super_admin' ? 'danger' : ($user->role == 'admin_it' ? 'primary' : ($user->role == 'helpdesk' ? 'info' : 'secondary')) }}">
                                            {{ $user->role_name }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->dealerBranch)
                                            <small>{{ $user->dealerBranch->branch_name }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $user->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="btn btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            @if($user->id != auth()->id())
                                                <button type="button" 
                                                        class="btn btn-outline-danger"
                                                        onclick="if(confirm('Delete this user?')) document.getElementById('delete-form-{{ $user->id }}').submit()">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                                <form id="delete-form-{{ $user->id }}" 
                                                      action="{{ route('admin.users.destroy', $user->id) }}" 
                                                      method="POST" 
                                                      class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </div>
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
@endsection