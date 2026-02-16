@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)
@section('page-title', 'Ticket Management')
@section('page-description', $ticket->ticket_number)

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Ticket Info Card -->
        <div class="card mb-3">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1">{{ $ticket->subject }}</h5>
                        <div class="d-flex gap-2 mt-2">
                            <span class="badge badge-priority-{{ $ticket->priority }}">
                                {{ strtoupper($ticket->priority) }}
                            </span>
                            <span class="badge bg-{{ $ticket->status_color }}">
                                {{ $ticket->status_label }}
                            </span>
                            @if($ticket->sla_met !== null)
                                <span class="badge bg-{{ $ticket->sla_met ? 'success' : 'danger' }}">
                                    SLA {{ $ticket->sla_met ? 'Met' : 'Breached' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <h6 class="text-muted mb-1">{{ $ticket->ticket_number }}</h6>
                        <small class="text-muted">Created: {{ $ticket->created_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Description:</strong>
                    <p class="mt-2">{{ $ticket->description }}</p>
                </div>
                
                @if($ticket->attachments->count() > 0)
                    <div class="mt-3">
                        <strong>Attachments:</strong>
                        <div class="mt-2">
                            @foreach($ticket->attachments as $attachment)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-paperclip"></i>
                                    <a href="{{ route('common.attachments.download', $attachment->id) }}" target="_blank">
                                        {{ $attachment->file_name }}
                                    </a>
                                    <small class="text-muted">({{ $attachment->file_size_formatted }})</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Assignment Actions -->
        @if($ticket->status == 'new' || !$ticket->assigned_to)
            <div class="card mb-3 border-warning">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-exclamation-triangle"></i> Action Required: Assign Ticket
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <select name="assigned_to" class="form-select" required>
                            <option value="">-- Select Helpdesk --</option>
                            @foreach($helpdeskUsers as $helpdesk)
                                <option value="{{ $helpdesk->id }}" {{ $ticket->assigned_to == $helpdesk->id ? 'selected' : '' }}>
                                    {{ $helpdesk->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-check"></i> Assign
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-person-badge"></i> Assignment Management</h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        <strong>Currently assigned to:</strong> {{ $ticket->assignedHelpdesk->name }}
                        <span class="badge bg-info ms-2">{{ $ticket->assignedHelpdesk->email }}</span>
                    </p>
                    
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#reassignModal">
                        <i class="bi bi-arrow-left-right"></i> Re-assign Ticket
                    </button>
                </div>
            </div>
        @endif
        
        <!-- Resolution -->
        @if($ticket->resolution_note)
            <div class="card mb-3 border-success">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-check-circle"></i> Resolution
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Resolved by:</strong> {{ $ticket->assignedHelpdesk->name }}</p>
                    <p class="mb-2"><strong>Resolved at:</strong> {{ $ticket->resolved_at->format('d M Y, H:i') }}</p>
                    <hr>
                    <p class="mb-0">{{ $ticket->resolution_note }}</p>
                </div>
            </div>
        @endif
        
        <!-- Comments & Updates -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-chat-left-text"></i> Comments & Updates</h6>
            </div>
            <div class="card-body">
                @if($ticket->comments->count() > 0)
                    @foreach($ticket->comments as $comment)
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-{{ $comment->user->isITStaff() ? 'primary' : 'secondary' }} text-white d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $comment->user->name }}</strong>
                                        <span class="badge bg-secondary ms-2">{{ $comment->user->role_name }}</span>
                                        @if($comment->is_internal)
                                            <span class="badge bg-warning text-dark ms-1">Internal</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mt-2 mb-0" style="white-space: pre-wrap;">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center mb-0">No comments yet</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Ticket Info -->
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Ticket Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="120"><strong>Branch:</strong></td>
                        <td>
                            {{ $ticket->dealerBranch->branch_name }}<br>
                            <small class="text-muted">{{ $ticket->dealerBranch->branch_code }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created By:</strong></td>
                        <td>
                            {{ $ticket->creator->name }}<br>
                            <small class="text-muted">{{ $ticket->creator->email }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Category:</strong></td>
                        <td>
                            {{ $ticket->category->category_name }}<br>
                            <small class="text-muted">{{ $ticket->subCategory->sub_category_name }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Priority:</strong></td>
                        <td>
                            <span class="badge badge-priority-{{ $ticket->priority }}">
                                {{ strtoupper($ticket->priority) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge bg-{{ $ticket->status_color }}">
                                {{ $ticket->status_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SLA Deadline:</strong></td>
                        <td>{{ $ticket->sla_deadline->format('d M Y, H:i') }}</td>
                    </tr>
                    @if($ticket->assigned_at)
                        <tr>
                            <td><strong>Assigned At:</strong></td>
                            <td>{{ $ticket->assigned_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @endif
                    @if($ticket->started_at)
                        <tr>
                            <td><strong>Started At:</strong></td>
                            <td>{{ $ticket->started_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @endif
                    @if($ticket->resolved_at)
                        <tr>
                            <td><strong>Resolved At:</strong></td>
                            <td>{{ $ticket->resolved_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Resolution Time:</strong></td>
                            <td>{{ $ticket->actual_minutes_taken }} minutes</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
        
        <!-- Activity Log -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-clock-history"></i> Activity Log</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($ticket->logs as $log)
                        <div class="timeline-item mb-3">
                            <div class="d-flex gap-2">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-circle-fill text-primary" style="font-size: 8px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <small class="text-muted">{{ $log->created_at->format('d M Y, H:i') }}</small>
                                    <p class="mb-0 small">{{ $log->description }}</p>
                                    @if($log->user)
                                        <small class="text-muted">by {{ $log->user->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Re-assign Modal -->
<div class="modal fade" id="reassignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.tickets.reassign', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Re-assign Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Assign to <span class="text-danger">*</span></label>
                        <select name="assigned_to" class="form-select" required>
                            <option value="">-- Select Helpdesk --</option>
                            @foreach($helpdeskUsers as $helpdesk)
                                <option value="{{ $helpdesk->id }}" {{ $ticket->assigned_to == $helpdesk->id ? 'selected' : '' }}>
                                    {{ $helpdesk->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  name="reason" 
                                  rows="3" 
                                  placeholder="Why are you re-assigning this ticket?"
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Re-assign</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection