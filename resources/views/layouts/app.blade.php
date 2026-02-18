<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Helpdesk System')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            padding-top: 20px;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            transition: width 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar .brand {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .sidebar.collapsed .brand {
            padding: 0 10px 20px;
        }
        
        /* Hamburger button styling */
        .sidebar .brand button {
            transition: all 0.3s ease;
        }
        
        .sidebar .brand button:hover {
            transform: scale(1.1);
        }
        
        .sidebar .brand button:focus {
            box-shadow: none;
        }
        
        .sidebar.collapsed .brand .d-flex {
            justify-content: center !important;
        }
        
        .sidebar .brand h4 {
            color: white;
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .sidebar.collapsed .brand h4 {
            font-size: 1.5rem;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
            white-space: nowrap;
            overflow: hidden;
        }
        
        .sidebar.collapsed .nav-link {
            padding: 12px 0;
            text-align: center;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.05);
            border-left-color: #3498db;
        }
        
        .sidebar.collapsed .nav-link:hover {
            border-left-color: transparent;
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background: rgba(52, 152, 219, 0.2);
            border-left-color: #3498db;
        }
        
        .sidebar.collapsed .nav-link.active {
            border-left-color: transparent;
            background: rgba(52, 152, 219, 0.3);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.3rem;
        }
        
        .sidebar-text {
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .sidebar-text {
            opacity: 0;
            display: none;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .content-wrapper {
            padding: 0 30px 30px;
        }
        
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        
        .stat-card .icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }
        
        .badge-priority-high {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-priority-medium {
            background-color: #ffc107;
            color: #000;
        }
        
        .badge-priority-low {
            background-color: #28a745;
            color: white;
        }
        
        .ticket-card {
            border-left: 4px solid #dee2e6;
            transition: all 0.2s;
        }
        
        .ticket-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateX(2px);
        }
        
        .ticket-card.priority-high {
            border-left-color: #dc3545;
        }
        
        .ticket-card.priority-medium {
            border-left-color: #ffc107;
        }
        
        .ticket-card.priority-low {
            border-left-color: #28a745;
        }
        
        .sla-indicator {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .sla-indicator.met {
            background-color: #d4edda;
            color: #155724;
        }
        
        .sla-indicator.critical {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .sla-indicator.breached {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-menu .dropdown-menu {
            right: 0;
            left: auto;
        }
        
        /* Tooltip styling for collapsed sidebar */
        .tooltip {
            font-size: 0.875rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed-width);
            }
            
            .sidebar .sidebar-text {
                display: none;
            }
            
            .main-content {
                margin-left: var(--sidebar-collapsed-width);
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    @include('layouts.partials.sidebar')
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        @include('layouts.partials.navbar')
        
        <!-- Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
    $(document).ready(function() {
        // Check localStorage for sidebar state
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            $('#sidebar').addClass('collapsed');
            $('#mainContent').addClass('expanded');
            $('#toggleIcon').removeClass('bi-list').addClass('bi-x-lg');
        }
        
        // Toggle sidebar
        $('#sidebarToggle').on('click', function() {
            $('#sidebar').toggleClass('collapsed');
            $('#mainContent').toggleClass('expanded');
            
            // Change icon
            if ($('#sidebar').hasClass('collapsed')) {
                $('#toggleIcon').removeClass('bi-list').addClass('bi-x-lg');
                localStorage.setItem('sidebarCollapsed', 'true');
                
                // Initialize tooltips for collapsed state
                initTooltips();
            } else {
                $('#toggleIcon').removeClass('bi-x-lg').addClass('bi-list');
                localStorage.setItem('sidebarCollapsed', 'false');
                
                // Destroy tooltips for expanded state
                destroyTooltips();
            }
        });
        
        // Initialize tooltips if sidebar is collapsed on load
        if ($('#sidebar').hasClass('collapsed')) {
            initTooltips();
        }
        
        function initTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.sidebar.collapsed .nav-link[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    placement: 'right'
                });
            });
        }
        
        function destroyTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.nav-link[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                var tooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                if (tooltip) {
                    tooltip.dispose();
                }
            });
        }
    });
    </script>
    
    @stack('scripts')
</body>
</html>