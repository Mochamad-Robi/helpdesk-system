@extends('layouts.app')

@section('title', 'Helpdesk Dashboard')
@section('page-title', 'Helpdesk Dashboard')
@section('page-description', 'Welcome, ' . auth()->user()->name)

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 text-white-50">Assigned to Me</h6>
                        <h2 class="mb-0">{{ $stats['assigned_to_me'] }}</h2>
                    </div>
                    <div class="icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 text-white-50">In Progress</h6>
                        <h2 class="mb-0">{{ $stats['in_progress'] }}</h2>
                    </div>
                    <div class="icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 text-white-50">Resolved Today</h6>
                        <h2 class="mb-0">{{ $stats['resolved_today'] }}</h2>
                    </div>
                    <div class="icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 text-white-50">SLA Critical</h6>
                        <h2 class="mb-0">{{ $stats['sla_critical'] }}</h2>
                    </div>
                    <div class="icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Urgent Tickets -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle text-warning"></i> 
                        My Urgent Tickets
                    </h5>
                    <a href="{{ route('helpdesk.tickets.index') }}" class="btn btn-sm btn-outline-primary">
                        View All My Tickets <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($urgentTickets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Subject</th>
                                    <th>Branch</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>SLA Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($urgentTickets as $ticket)
                                    <tr class="{{ $ticket->isSlaBreached() ? 'table-danger' : '' }}">
                                        <td>
                                            <strong>{{ $ticket->ticket_number }}</strong>
                                        </td>
                                        <td>{{ Str::limit($ticket->subject, 40) }}</td>
                                        <td>
                                            <small>{{ $ticket->dealerBranch->branch_code }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $ticket->category->category_name }}
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
                                            @php
                                                $remaining = $ticket->remaining_sla_minutes;
                                            @endphp
                                            @if($remaining < 0)
                                                <span class="sla-indicator breached">
                                                    <i class="bi bi-x-circle"></i>
                                                    {{ abs($remaining) }}m overdue
                                                </span>
                                            @elseif($remaining < 30)
                                                <span class="sla-indicator critical">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    {{ $remaining }}m left
                                                </span>
                                            @else
                                                <small class="text-muted">{{ $remaining }}m left</small>
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
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3 mb-0">No urgent tickets. Great job!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection