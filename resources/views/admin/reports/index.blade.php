@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-description', 'System performance reports')

@section('content')
<!-- Date Range Filter -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="d-flex gap-2 align-items-end">
                    <div>
                        <label class="form-label">Start Date</label>
                        <input type="date" 
                               class="form-control" 
                               name="start_date" 
                               value="{{ $startDate }}">
                    </div>
                    <div>
                        <label class="form-label">End Date</label>
                        <input type="date" 
                               class="form-control" 
                               name="end_date" 
                               value="{{ $endDate }}">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Generate Report
                    </button>
                    <a href="{{ route('admin.reports.export') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
                       class="btn btn-success">
                        <i class="bi bi-download"></i> Export
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tickets by Status -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Tickets by Status</h6>
            </div>
            <div class="card-body">
                @if($ticketsReport->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = $ticketsReport->sum('total'); @endphp
                                @foreach($ticketsReport as $item)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ strtoupper(str_replace('_', ' ', $item->status)) }}</span>
                                        </td>
                                        <td class="text-end"><strong>{{ $item->total }}</strong></td>
                                        <td class="text-end">{{ number_format(($item->total / $total) * 100, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th>Total</th>
                                    <th class="text-end">{{ $total }}</th>
                                    <th class="text-end">100%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No data available</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- SLA Compliance -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-speedometer"></i> SLA Compliance</h6>
            </div>
            <div class="card-body">
                @if($slaReport->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>SLA Status</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSla = $slaReport->sum('total'); @endphp
                                @foreach($slaReport as $item)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $item->sla_met ? 'success' : 'danger' }}">
                                                {{ $item->sla_met ? 'SLA Met' : 'SLA Breached' }}
                                            </span>
                                        </td>
                                        <td class="text-end"><strong>{{ $item->total }}</strong></td>
                                        <td class="text-end">{{ number_format(($item->total / $totalSla) * 100, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th>Total</th>
                                    <th class="text-end">{{ $totalSla }}</th>
                                    <th class="text-end">100%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tickets by Category -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-folder"></i> Tickets by Category</h6>
            </div>
            <div class="card-body">
                @if($categoryReport->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Total Tickets</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryReport as $item)
                                    <tr>
                                        <td>{{ $item->category->category_name }}</td>
                                        <td class="text-end"><strong>{{ $item->total }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No data available</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Tickets by Branch -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-building"></i> Tickets by Branch</h6>
            </div>
            <div class="card-body">
                @if($branchReport->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th class="text-end">Total Tickets</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branchReport as $item)
                                    <tr>
                                        <td>{{ $item->dealerBranch->branch_name }}</td>
                                        <td class="text-end"><strong>{{ $item->total }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Helpdesk Performance -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-people"></i> Helpdesk Performance</h6>
            </div>
            <div class="card-body">
                @if($helpdeskPerformance->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Helpdesk Name</th>
                                    <th class="text-end">Total Tickets</th>
                                    <th class="text-end">SLA Met</th>
                                    <th class="text-end">SLA Compliance</th>
                                    <th class="text-end">Avg Resolution Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($helpdeskPerformance as $item)
                                    <tr>
                                        <td>{{ $item->assignedHelpdesk->name ?? 'N/A' }}</td>
                                        <td class="text-end"><strong>{{ $item->total_tickets }}</strong></td>
                                        <td class="text-end">{{ $item->sla_met_count }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-{{ ($item->sla_met_count / $item->total_tickets * 100) >= 90 ? 'success' : 'warning' }}">
                                                {{ number_format(($item->sla_met_count / $item->total_tickets) * 100, 1) }}%
                                            </span>
                                        </td>
                                        <td class="text-end">{{ number_format($item->avg_resolution_time, 0) }} min</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4 mb-0">No data available</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection