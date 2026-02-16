<div class="top-navbar">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            <small class="text-muted">@yield('page-description', '')</small>
        </div>
        
        <div class="user-menu dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-envelope"></i> {{ auth()->user()->email }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-building"></i> 
                        @if(auth()->user()->dealerBranch)
                            {{ auth()->user()->dealerBranch->branch_name }}
                        @else
                            IT Staff
                        @endif
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                @if(auth()->user()->isDealer())
                    <li>
                        <a class="dropdown-item" href="{{ route('dealer.profile') }}">
                            <i class="bi bi-person"></i> Profile
                        </a>
                    </li>
                @elseif(auth()->user()->isHelpdesk())
                    <li>
                        <a class="dropdown-item" href="{{ route('helpdesk.profile') }}">
                            <i class="bi bi-person"></i> Profile
                        </a>
                    </li>
                @endif
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
        <i class="bi bi-exclamation-triangle"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif