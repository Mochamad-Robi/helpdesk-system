@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="login-card">
    <div class="login-header">
        <i class="bi bi-headset" style="font-size: 3rem;"></i>
        <h3 class="mt-3">Helpdesk System</h3>
        <p class="mb-0 small">IT Support Ticketing</p>
    </div>
    
    <div class="login-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="Enter your email">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           required
                           placeholder="Enter your password">
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-login w-100">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        </form>
        
        <hr class="my-4">
        
        <div class="text-center">
            <small class="text-muted">
                <strong>Demo Accounts:</strong><br>
                Admin: admin@helpdesk.com<br>
                Dealer: jkt-slt.user1@dealer.com<br>
                Helpdesk: budi.helpdesk@helpdesk.com<br>
                Password: <code>password</code>
            </small>
        </div>
    </div>
</div>
@endsection