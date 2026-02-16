@extends('layouts.app')

@section('title', 'All Tickets')
@section('page-title', 'All Tickets')
@section('page-description', 'Manage all support tickets')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <!-- Filters -->
        <form method="GET" class="d-flex gap-2 align-items-center">
            <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                <option value="reopened" {{ request('status') == 'reopened' ? 'selected' : '' }}>Reopened</option>
            </select>
            
            <select name="priority" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                <option value="">All Priority</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
            </select>
            
            <select name="category" class="form-select form-select-sm" style="width: 200px;" onchange="this.form.submit()">
                <option value="">All Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
            
            <input type="text" 
                   name="search" 
                   class="form-control form-control-sm" 
                   placeholder="Search tickets..." 
                   value="{{ request('search') }}"
                   style="width: 250px;">
            
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-search"></i> Search
            </button>
            
            @if(request()->hasAny(['status', 'priority', 'category', 'search']))
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-secondary">
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
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>SLA</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr class="{{ $ticket->isSlaBreached() ? 'table-danger' : '' }}">
                                        <td>
                                            <strong class="text-primary">{{ $ticket->ticket_number }}</strong>
                                        </td>
                                        <td>
                                            {{ Str::limit($ticket->subject, 40) }}<br>
                                            <small class="text-muted">by {{ $ticket->creator->name }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $ticket->dealerBranch->branch_code }}</span>
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
                                            @if($ticket->assignedHelpdesk)
                                                <small>{{ $ticket->assignedHelpdesk->name }}</small>
                                            @else
                                                <span class="badge bg-warning text-dark">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(in_array($ticket->status, ['resolved', 'closed']))
                                                <span class="sla-indicator {{ $ticket->sla_met ? 'met' : 'breached' }}">
                                                    {{ $ticket->sla_met ? 'Met' : 'Breach' }}
                                                </span>
                                            @else
                                                @php $remaining = $ticket->remaining_sla_minutes; @endphp
                                                @if($remaining < 0)
                                                    <span class="sla-indicator breached">
                                                        <i class="bi bi-x-circle"></i> {{ abs($remaining) }}m
                                                    </span>
                                                @elseif($remaining < 30)
                                                    <span class="sla-indicator critical">
                                                        <i class="bi bi-exclamation-triangle"></i> {{ $remaining }}m
                                                    </span>
                                                @else
                                                    <small class="text-muted">{{ $remaining }}m left</small>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $ticket->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
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