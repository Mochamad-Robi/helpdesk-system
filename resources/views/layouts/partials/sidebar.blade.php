<div class="sidebar">
    <div class="brand">
        <h4><i class="bi bi-headset"></i> Helpdesk System</h4>
        <small class="text-white-50">{{ auth()->user()->role_name }}</small>
    </div>
    
    <nav class="nav flex-column">
        @if(auth()->user()->isDealer())
            <!-- Dealer Menu -->
            <a href="{{ route('dealer.dashboard') }}" class="nav-link {{ Request::routeIs('dealer.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('dealer.tickets.index') }}" class="nav-link {{ Request::routeIs('dealer.tickets.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i> My Tickets
            </a>
            <a href="{{ route('dealer.tickets.create') }}" class="nav-link">
                <i class="bi bi-plus-circle"></i> Create Ticket
            </a>
            <a href="{{ route('dealer.profile') }}" class="nav-link {{ Request::routeIs('dealer.profile') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Profile
            </a>
            
        @elseif(auth()->user()->isAdminIT() || auth()->user()->isSuperAdmin())
            <!-- Admin IT Menu -->
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.tickets.index') }}" class="nav-link {{ Request::routeIs('admin.tickets.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i> All Tickets
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ Request::routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i> Categories
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ Request::routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a>
            <a href="{{ route('admin.branches.index') }}" class="nav-link {{ Request::routeIs('admin.branches.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Branches
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ Request::routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Reports
            </a>
            
        @elseif(auth()->user()->isHelpdesk())
            <!-- Helpdesk Menu -->
            <a href="{{ route('helpdesk.dashboard') }}" class="nav-link {{ Request::routeIs('helpdesk.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('helpdesk.tickets.index') }}" class="nav-link {{ Request::routeIs('helpdesk.tickets.index') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i> My Tickets
            </a>
            <a href="{{ route('helpdesk.tickets.all') }}" class="nav-link {{ Request::routeIs('helpdesk.tickets.all') ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i> All Tickets
            </a>
            <a href="{{ route('helpdesk.profile') }}" class="nav-link {{ Request::routeIs('helpdesk.profile') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Profile
            </a>
        @endif
    </nav>
</div>