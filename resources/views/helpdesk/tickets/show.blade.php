@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)
@section('page-title', 'Ticket Detail')
@section('page-description', $ticket->ticket_number)

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Ticket Info -->
        <div class="card mb-3">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $ticket->subject }}</h5>
                        <div class="d-flex gap-2 mt-2">
                            <span class="badge badge-priority-{{ $ticket->priority }}">
                                {{ strtoupper($ticket->priority) }}
                            </span>
                            <span class="badge bg-{{ $ticket->status_color }}">
                                {{ $ticket->status_label }}
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        <h6 class="text-muted">{{ $ticket->ticket_number }}</h6>
                        <small>{{ $ticket->created_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p>{{ $ticket->description }}</p>
                
                @if($ticket->attachments->count() > 0)
                    <hr>
                    <strong>Attachments:</strong>
                    <div class="mt-2">
                        @foreach($ticket->attachments as $attachment)
                            <div class="mb-2">
                                <i class="bi bi-paperclip"></i>
                                <a href="{{ route('common.attachments.download', $attachment->id) }}">
                                    {{ $attachment->file_name }}
                                </a>
                                <small class="text-muted">({{ $attachment->file_size_formatted }})</small>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Action Buttons -->
        @if($canEdit && $ticket->status != 'resolved' && $ticket->status != 'closed')
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-tools"></i> Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        @if($ticket->status == 'assigned')
                            <form action="{{ route('helpdesk.tickets.start', $ticket->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-play-circle"></i> Start Working
                                </button>
                            </form>
                        @endif
                        
                        @if($ticket->status == 'in_progress')
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#resolveModal">
                                <i class="bi bi-check-circle"></i> Mark as Resolved
                            </button>
                            
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#pendingModal">
                                <i class="bi bi-clock"></i> Set Pending
                            </button>
                        @endif
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#escalateModal">
                            <i class="bi bi-exclamation-triangle"></i> Escalate
                        </button>
                        
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="bi bi-paperclip"></i> Upload File
                        </button>
                    </div>
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
                    <p class="mb-0">{{ $ticket->resolution_note }}</p>
                    <hr>
                    <small class="text-muted">
                        Resolved by {{ $ticket->assignedHelpdesk->name }} at {{ $ticket->resolved_at->format('d M Y, H:i') }}
                    </small>
                </div>
            </div>
        @endif
        
        <!-- Comments -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-chat"></i> Comments & Updates</h6>
            </div>
            <div class="card-body">
                @foreach($ticket->comments as $comment)
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $comment->user->name }}</strong>
                                    @if($comment->is_internal)
                                        <span class="badge bg-warning text-dark ms-1">Internal</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mt-2 mb-0">{{ $comment->comment }}</p>
                        </div>
                    </div>
                @endforeach
                
                <!-- Add Comment Form -->
                @if($canEdit && !in_array($ticket->status, ['closed']))
                    <hr>
                    <form action="{{ route('helpdesk.tickets.comment', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" name="comment" rows="3" required></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_internal" id="is_internal" value="1">
                                <label class="form-check-label" for="is_internal">
                                    Internal Note (IT team only)
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Post Comment
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Ticket Info -->
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Ticket Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="100"><strong>Branch:</strong></td>
                        <td>{{ $ticket->dealerBranch->branch_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created By:</strong></td>
                        <td>{{ $ticket->creator->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Category:</strong></td>
                        <td>
                            {{ $ticket->category->category_name }}<br>
                            <small class="text-muted">{{ $ticket->subCategory->sub_category_name }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SLA:</strong></td>
                        <td>{{ $ticket->sla_deadline->format('d M, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Activity Log -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Activity Log</h6>
            </div>
            <div class="card-body">
                @foreach($ticket->logs as $log)
                    <div class="mb-2">
                        <small class="text-muted">{{ $log->created_at->format('d M, H:i') }}</small>
                        <p class="mb-0 small">{{ $log->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Resolve Modal -->
<div class="modal fade" id="resolveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('helpdesk.tickets.resolve', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Resolve Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Resolution Note <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="resolution_note" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Mark as Resolved</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Pending Modal -->
<div class="modal fade" id="pendingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('helpdesk.tickets.pending', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Set Ticket to Pending</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Message to Dealer <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="comment" rows="4" required 
                                  placeholder="What information do you need from the dealer?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Set Pending</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Escalate Modal -->
<div class="modal fade" id="escalateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('helpdesk.tickets.escalate', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Escalate to Admin IT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reason" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Escalate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('helpdesk.tickets.attachments.upload', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file" required>
                        <small class="text-muted">Max 5MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection