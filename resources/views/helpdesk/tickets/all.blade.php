@extends('layouts.app')

@section('title', 'All Tickets')
@section('page-title', 'All Tickets')
@section('page-description', 'View all system tickets')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
            
            <select name="priority" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                <option value="">All Priority</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
            </select>
            
            <input type="text" 
                   name="search" 
                   class="form-control form-control-sm" 
                   placeholder="Search..." 
                   value="{{ request('search') }}"
                   style="width: 250px;">
            
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-search"></i>
            </button>
            
            @if(request()->hasAny(['status', 'priority', 'search']))
                <a href="{{ route('helpdesk.tickets.all') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
            @endif
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @if($tickets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Subject</th>
                                    <th>Branch</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td><strong>{{ $ticket->ticket_number }}</strong></td>
                                        <td>{{ Str::limit($ticket->subject, 40) }}</td>
                                        <td>
                                            <small>{{ $ticket->dealerBranch->branch_code }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-priority-{{ $ticket->priority }}">
                                                {{ strtoupper($ticket->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $ticket->status_color }}">
                                                {{ $ticket->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($ticket->assignedHelpdesk)
                                                <small>{{ $ticket->assignedHelpdesk->name }}</small>
                                            @else
                                                <span class="badge bg-warning">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $ticket->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('helpdesk.tickets.show', $ticket->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $tickets->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #ddd;"></i>
                        <p class="text-muted mt-3 mb-0">No tickets found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection