@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-description', 'Manage your account')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Profile Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('helpdesk.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="{{ $user->role_name }}" disabled>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="mb-3"><i class="bi bi-key"></i> Change Password</h6>
                    <p class="text-muted small">Leave blank if you don't want to change password</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               name="current_password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   name="new_password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Min. 8 characters</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   name="new_password_confirmation">
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('helpdesk.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Statistics Card -->
        <div class="card mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> My Performance</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h4 class="text-primary">
                            {{ $user->assignedTickets()->count() }}
                        </h4>
                        <small class="text-muted">Total Tickets</small>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-success">
                            {{ $user->assignedTickets()->where('status', 'resolved')->count() }}
                        </h4>
                        <small class="text-muted">Resolved</small>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-warning">
                            {{ $user->assignedTickets()->whereIn('status', ['assigned', 'in_progress', 'pending'])->count() }}
                        </h4>
                        <small class="text-muted">In Progress</small>
                    </div>
                    <div class="col-md-3">
                        @php
                            $total = $user->assignedTickets()->whereIn('status', ['resolved', 'closed'])->count();
                            $met = $user->assignedTickets()->whereIn('status', ['resolved', 'closed'])->where('sla_met', true)->count();
                            $rate = $total > 0 ? round(($met / $total) * 100, 1) : 0;
                        @endphp
                        <h4 class="text-{{ $rate >= 90 ? 'success' : ($rate >= 75 ? 'warning' : 'danger') }}">
                            {{ $rate }}%
                        </h4>
                        <small class="text-muted">SLA Compliance</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection