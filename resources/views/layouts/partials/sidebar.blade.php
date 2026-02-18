<div class="sidebar" id="sidebar">
    <!-- Toggle Button -->
    <div class="sidebar-toggle">
        <button class="btn btn-link text-white" id="sidebarToggle">
            <i class="bi bi-list" id="toggleIcon"></i>
        </button>
    </div>
    
    <div class="brand">
        <h4><i class="bi bi-headset"></i> <span class="sidebar-text">Helpdesk System</span></h4>
        <small class="text-white-50 sidebar-text">{{ auth()->user()->role_name }}</small>
    </div>
    
    <nav class="nav flex-column">
        @if(auth()->user()->isDealer())
            <!-- Dealer Menu -->
            <a href="{{ route('dealer.dashboard') }}" class="nav-link {{ Request::routeIs('dealer.dashboard') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Dashboard">
                <i class="bi bi-speedometer2"></i> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="{{ route('dealer.tickets.index') }}" class="nav-link {{ Request::routeIs('dealer.tickets.*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="My Tickets">
                <i class="bi bi-ticket-perforated"></i> <span class="sidebar-text">My Tickets</span>
            </a>
            <a href="{{ route('dealer.tickets.create') }}" class="nav-link" data-bs-toggle="tooltip" title="Create Ticket">
                <i class="bi bi-plus-circle"></i> <span class="sidebar-text">Create Ticket</span>
            </a>
            <a href="{{ route('dealer.profile') }}" class="nav-link {{ Request::routeIs('dealer.profile') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Profile">
                <i class="bi bi-person"></i> <span class="sidebar-text">Profile</span>
            </a>
            
        @elseif(auth()->user()->isAdminIT() || auth()->user()->isSuperAdmin())
            <!-- Admin IT Menu -->
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Dashboard">
                <i class="bi bi-speedometer2"></i> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.tickets.index') }}" class="nav-link {{ Request::routeIs('admin.tickets.*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="All Tickets">
                <i class="bi bi-ticket-perforated"></i> <span class="sidebar-text">All Tickets</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ Request::routeIs('admin.categories.*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Categories">
                <i class="bi bi-folder"></i> <span class="sidebar-text">Categories</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ Request::routeIs('admin.users.*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Users">
                <i class="bi bi-people"></i> <span class="sidebar-text">Users</span>
            </a>
            <a href="{{ route('admin.branches.index') }}" class="nav-link {{ Request::routeIs('admin.branches.*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Branches">
                <i class="bi bi-building"></i> <span class="sidebar-text">Branches</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ Request::routeIs('admin.reports.*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Reports">
                <i class="bi bi-graph-up"></i> <span class="sidebar-text">Reports</span>
            </a>
            
        @elseif(auth()->user()->isHelpdesk())
            <!-- Helpdesk Menu -->
            <a href="{{ route('helpdesk.dashboard') }}" class="nav-link {{ Request::routeIs('helpdesk.dashboard') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Dashboard">
                <i class="bi bi-speedometer2"></i> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="{{ route('helpdesk.tickets.index') }}" class="nav-link {{ Request::routeIs('helpdesk.tickets.index') ? 'active' : '' }}" data-bs-toggle="tooltip" title="My Tickets">
                <i class="bi bi-ticket-perforated"></i> <span class="sidebar-text">My Tickets</span>
            </a>
            <a href="{{ route('helpdesk.tickets.all') }}" class="nav-link {{ Request::routeIs('helpdesk.tickets.all') ? 'active' : '' }}" data-bs-toggle="tooltip" title="All Tickets">
                <i class="bi bi-list-ul"></i> <span class="sidebar-text">All Tickets</span>
            </a>
            <a href="{{ route('helpdesk.profile') }}" class="nav-link {{ Request::routeIs('helpdesk.profile') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Profile">
                <i class="bi bi-person"></i> <span class="sidebar-text">Profile</span>
            </a>
        @endif
    </nav>
</div>
