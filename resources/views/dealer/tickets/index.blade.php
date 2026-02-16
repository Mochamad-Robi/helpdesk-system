@extends('layouts.app')

@section('title', 'My Tickets')
@section('page-title', 'My Tickets')
@section('page-description', 'View and manage your support tickets')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <!-- Filters -->
                <form method="GET" class="d-inline-flex gap-2">
                    <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
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
                           placeholder="Search tickets..." 
                           value="{{ request('search') }}"
                           style="width: 200px;">
                    
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                    
                    @if(request()->hasAny(['status', 'priority', 'search']))
                        <a href="{{ route('dealer.tickets.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    @endif
                </form>
            </div>
            
            <a href="{{ route('dealer.tickets.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Ticket
            </a>
        </div>
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
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>SLA</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $ticket->ticket_number }}</strong>
                                        </td>
                                        <td>
                                            <div>{{ Str::limit($ticket->subject, 50) }}</div>
                                            <small class="text-muted">
                                                Assigned to: {{ $ticket->assignedHelpdesk->name ?? 'Unassigned' }}
                                            </small>
                                        </td>
                                        <td>
                                            <small>
                                                {{ $ticket->category->category_name }}<br>
                                                <span class="text-muted">{{ $ticket->subCategory->sub_category_name }}</span>
                                            </small>
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
                                            @if(in_array($ticket->status, ['resolved', 'closed']))
                                                <span class="sla-indicator {{ $ticket->sla_met ? 'met' : 'breached' }}">
                                                    <i class="bi bi-{{ $ticket->sla_met ? 'check-circle' : 'x-circle' }}"></i>
                                                    {{ $ticket->sla_met ? 'Met' : 'Breach' }}
                                                </span>
                                            @else
                                                @php
                                                    $remaining = $ticket->remaining_sla_minutes;
                                                @endphp
                                                @if($remaining < 0)
                                                    <span class="sla-indicator breached">
                                                        <i class="bi bi-exclamation-triangle"></i>
                                                        Breached
                                                    </span>
                                                @elseif($remaining < 30)
                                                    <span class="sla-indicator critical">
                                                        <i class="bi bi-hourglass-bottom"></i>
                                                        {{ abs($remaining) }}m left
                                                    </span>
                                                @else
                                                    <small class="text-muted">
                                                        {{ $ticket->sla_deadline->format('d M, H:i') }}
                                                    </small>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $ticket->created_at->format('d M Y') }}</small><br>
                                            <small class="text-muted">{{ $ticket->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('dealer.tickets.show', $ticket->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $tickets->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #ddd;"></i>
                        <p class="text-muted mt-3 mb-3">
                            @if(request()->hasAny(['status', 'priority', 'search']))
                                No tickets found matching your filters
                            @else
                                You haven't created any tickets yet
                            @endif
                        </p>
                        @if(!request()->hasAny(['status', 'priority', 'search']))
                            <a href="{{ route('dealer.tickets.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Your First Ticket
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection