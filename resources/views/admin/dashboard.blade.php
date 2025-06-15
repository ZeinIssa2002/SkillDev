@extends('admin.layouts.app')

@section('title', 'Admin Panel - Dashboard')

@section('styles')
<style>
    .admin-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 30px;
        max-width: 600px;
        margin: 0 auto;
    }
    .admin-icon {
        font-size: 50px;
        color: #4361ee;
        margin-bottom: 20px;
    }
    .info-item {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .info-label {
        font-weight: 600;
        color: #6c757d;
    }
    .info-value {
        font-size: 18px;
        color: #212529;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Admin Dashboard</h2>
</div>

<div class="admin-card text-center">
    <i class="fas fa-user-shield admin-icon"></i>
    <h3 class="mb-4">Admin Information</h3>
    
    <div class="info-item">
        <div class="info-label">Username</div>
        <div class="info-value">{{ Auth::user()->username }}</div>
    </div>
    
    <div class="info-item">
        <div class="info-label">Email</div>
        <div class="info-value">{{ Auth::user()->email }}</div>
    </div>
    
    <div class="info-item">
        <div class="info-label">Account Type</div>
        <div class="info-value">{{ Auth::user()->account_type }}</div>
    </div>
    
    <div class="info-item">
        <div class="info-label">Last Login</div>
        <div class="info-value">{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Never' }}</div>
    </div>
</div>
@endsection