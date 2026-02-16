@extends('layouts.app')

@section('title', 'Dealer Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back, ' . auth()->user()->name)

@section('content')
<div class="row">
    <!-- Statistics Cards -->
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
                        <h6 class="mb-1 text-white-50">Resolved</h6>
                        <h2 class="mb-0">{{ $stats['resolved_tickets'] }}</h2>
                    </div>
                    <div class="icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card bg-dark text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 text-white-50">Closed</h6>
                        <h2 class="mb-0">{{ $stats['closed_tickets'] }}</h2>
                    </div>
                    <div class="icon">
                        <i class="bi bi-archive"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Branch Info -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-building"></i> Branch Information
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Branch Name:</strong></td>
                                <td>{{ $branch->branch_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Branch Code:</strong></td>
                                <td><span class="badge bg-secondary">{{ $branch->branch_code }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td>{{ $branch->address ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Phone:</strong></td>
                                <td>{{ $branch->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>PIC Name:</strong></td>
                                <td>{{ $branch->pic_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>PIC Email:</strong></td>
                                <td>{{ $branch->pic_email ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Tickets -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Tickets</h5>
                    <a href="{{ route('dealer.tickets.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Create New Ticket
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentTickets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTickets as $ticket)
                                    <tr>
                                        <td>
                                            <strong>{{ $ticket->ticket_number }}</strong>
                                        </td>
                                        <td>{{ Str::limit($ticket->subject, 40) }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $ticket->category->category_name }} > 
                                                {{ $ticket->subCategory->sub_category_name }}
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
                                            <small>{{ $ticket->assignedHelpdesk->name ?? 'Unassigned' }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $ticket->created_at->format('d M Y, H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('dealer.tickets.show', $ticket->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('dealer.tickets.index') }}" class="btn btn-outline-secondary">
                            View All Tickets <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #ddd;"></i>
                        <p class="text-muted mt-3">No tickets yet</p>
                        <a href="{{ route('dealer.tickets.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create Your First Ticket
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection