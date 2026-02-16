@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-description', 'IT Support System Overview')

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 text-white-50">Total Tickets</h6>
                        <h2 class="mb-0">{{ $stats['total_tickets'] }}</h2>
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
                        <h6 class="mb-1 text-white-50">Open Tickets</h6>
                        <h2 class="mb-0">{{ $stats['open_tickets'] }}</h2>
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
                        <h6 class="mb-1 text-white-50">SLA Breach</h6>
                        <h2 class="mb-0">{{ $stats['sla_breach'] }}</h2>
                    </div>
                    <div class="icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mt-4">
    <!-- Tickets by Priority -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Tickets by Priority</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>High Priority</span>
                    <span class="badge bg-danger">{{ $ticketsByPriority['high'] }}</span>
                </div>
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-danger" 
                         style="width: {{ $ticketsByPriority['high'] > 0 ? ($ticketsByPriority['high'] / array_sum($ticketsByPriority) * 100) : 0 }}%">
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Medium Priority</span>
                    <span class="badge bg-warning">{{ $ticketsByPriority['medium'] }}</span>
                </div>
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-warning" 
                         style="width: {{ $ticketsByPriority['medium'] > 0 ? ($ticketsByPriority['medium'] / array_sum($ticketsByPriority) * 100) : 0 }}%">
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Low Priority</span>
                    <span class="badge bg-success">{{ $ticketsByPriority['low'] }}</span>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar bg-success" 
                         style="width: {{ $ticketsByPriority['low'] > 0 ? ($ticketsByPriority['low'] / array_sum($ticketsByPriority) * 100) : 0 }}%">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tickets by Category -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Tickets by Category</h6>
            </div>
            <div class="card-body">
                @if($ticketsByCategory->count() > 0)
                    @foreach($ticketsByCategory as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $item->category->category_name }}</span>
                            <span class="badge bg-secondary">{{ $item->total }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" 
                                 style="width: {{ ($item->total / $ticketsByCategory->sum('total')) * 100 }}%">
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center mb-0">No data available</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Performance Metrics -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-speedometer"></i> Performance Metrics</h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>SLA Compliance Rate</span>
                        <strong class="text-{{ $slaComplianceRate >= 90 ? 'success' : ($slaComplianceRate >= 75 ? 'warning' : 'danger') }}">
                            {{ number_format($slaComplianceRate, 1) }}%
                        </strong>
                    </div>
                    <div class="progress" style="height: 15px;">
                        <div class="progress-bar bg-{{ $slaComplianceRate >= 90 ? 'success' : ($slaComplianceRate >= 75 ? 'warning' : 'danger') }}" 
                             style="width: {{ $slaComplianceRate }}%">
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-clock-history"></i> Avg Response Time</span>
                        <strong>{{ number_format($avgResponseTime, 0) }} min</strong>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-ticket"></i> Total Tickets</span>
                        <strong>{{ $stats['total_tickets'] }}</strong>
                    </div>
                </div>
                
                <div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-check2-circle"></i> Resolved Rate</span>
                        <strong class="text-success">
                            {{ $stats['total_tickets'] > 0 ? number_format(($stats['resolved_today'] / $stats['total_tickets']) * 100, 1) : 0 }}%
                        </strong>
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
                        Tickets Requiring Attention
                    </h5>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Tickets <i class="bi bi-arrow-right"></i>
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
                                    <th>Assigned To</th>
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
                                                    Overdue {{ abs($remaining) }}m
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
                                            @if($ticket->assignedHelpdesk)
                                                <small>{{ $ticket->assignedHelpdesk->name }}</small>
                                            @else
                                                <span class="badge bg-warning">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
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
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">No urgent tickets at the moment. Great job!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection