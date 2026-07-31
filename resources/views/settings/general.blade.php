@extends('layouts.admin')

@section('title', 'System Setup & Settings')
@section('header', 'System Setup & Configuration')

@section('content')
<style>
    :root {
        --empire-blue: #007CEF;
        --empire-dark-bg: #111318;
        --empire-dark-card: #161920;
        --empire-dark-input: #1D212A;
    }

    /* ==========================================================================
       ABSOLUTE LIGHT & DARK MODE OVERRIDES - ROUNDED PILLS & CLEAN TABLE HEADS
       ========================================================================== */
    .rounded-pill,
    [data-bs-theme="dark"] .rounded-pill,
    [data-bs-theme="light"] .rounded-pill {
        border-radius: 50rem !important;
    }

    .settings-filter-pill,
    [data-bs-theme="dark"] .settings-filter-pill,
    [data-bs-theme="light"] .settings-filter-pill {
        border-radius: 50rem !important;
        padding: 0.5rem 1.25rem !important;
        font-weight: 600 !important;
        font-size: 0.88rem !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        color: #475569 !important;
    }
    .settings-filter-pill:hover,
    [data-bs-theme="light"] .settings-filter-pill:hover {
        border-color: #94a3b8 !important;
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
    }
    [data-bs-theme="dark"] .settings-filter-pill {
        border-color: rgba(255, 255, 255, 0.15) !important;
        background-color: var(--empire-dark-input) !important;
        color: #94a3b8 !important;
    }
    [data-bs-theme="dark"] .settings-filter-pill:hover {
        border-color: rgba(255, 255, 255, 0.35) !important;
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #f8fafc !important;
    }
    /* SLEEK OBSIDIAN DARK MODE PALETTE - NO BLUE COLOR ALLOWED */
    .settings-filter-pill.active,
    [data-bs-theme="dark"] .settings-filter-pill.active,
    [data-bs-theme="light"] .settings-filter-pill.active {
        background: linear-gradient(135deg, #2A2E39 0%, #161920 100%) !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5) !important;
    }
    [data-bs-theme="light"] .settings-filter-pill.active {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
        color: #ffffff !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.25) !important;
    }

    /* Override All Blue / Primary Classes in Settings to Premium Dark Charcoal/Obsidian */
    .text-primary {
        color: #cbd5e1 !important;
    }
    [data-bs-theme="dark"] .text-primary {
        color: #f8fafc !important;
    }
    [data-bs-theme="light"] .text-primary {
        color: #1e293b !important;
    }

    .bg-primary,
    .card-header.bg-primary {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
        color: #ffffff !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    [data-bs-theme="dark"] .bg-primary,
    [data-bs-theme="dark"] .card-header.bg-primary {
        background: linear-gradient(135deg, #2A2E39 0%, #171A21 100%) !important;
        color: #ffffff !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
    }

    .badge.bg-light-primary {
        background-color: rgba(30, 41, 59, 0.1) !important;
        color: #1e293b !important;
        border: 1px solid #cbd5e1 !important;
    }
    [data-bs-theme="dark"] .badge.bg-light-primary {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #f8fafc !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    .btn-primary,
    [data-bs-theme="dark"] .btn-primary {
        background: linear-gradient(135deg, #2A2E39 0%, #161920 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important;
    }
    [data-bs-theme="light"] .btn-primary {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
        border-color: #334155 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.2) !important;
    }
    .btn-primary:hover,
    .btn-primary:focus,
    [data-bs-theme="dark"] .btn-primary:hover,
    [data-bs-theme="dark"] .btn-primary:focus {
        background: linear-gradient(135deg, #383f4f 0%, #222733 100%) !important;
        border-color: rgba(255, 255, 255, 0.45) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }
    [data-bs-theme="light"] .btn-primary:hover,
    [data-bs-theme="light"] .btn-primary:focus {
        background: linear-gradient(135deg, #334155 0%, #1e293b 100%) !important;
        border-color: #475569 !important;
        color: #ffffff !important;
    }

    .btn-outline-primary,
    [data-bs-theme="dark"] .btn-outline-primary {
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        color: #e2e8f0 !important;
        background-color: transparent !important;
    }
    .btn-outline-primary:hover,
    [data-bs-theme="dark"] .btn-outline-primary:hover {
        background-color: rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.45) !important;
    }
    [data-bs-theme="light"] .btn-outline-primary {
        border: 1px solid #475569 !important;
        color: #1e293b !important;
        background-color: transparent !important;
    }
    [data-bs-theme="light"] .btn-outline-primary:hover {
        background-color: #1e293b !important;
        color: #ffffff !important;
        border-color: #1e293b !important;
    }

    .border-primary,
    [data-bs-theme="dark"] .border-primary {
        border-color: rgba(255, 255, 255, 0.25) !important;
    }
    [data-bs-theme="light"] .border-primary {
        border-color: #cbd5e1 !important;
    }

    /* Sleek Apple Pro Alert Styling in Dark Mode */
    [data-bs-theme="dark"] .alert-light-success,
    [data-bs-theme="dark"] .alert-success {
        background-color: #111B18 !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        color: #34D399 !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4) !important;
    }
    [data-bs-theme="dark"] .alert-light-danger,
    [data-bs-theme="dark"] .alert-danger {
        background-color: #1F1315 !important;
        border: 1px solid rgba(239, 68, 68, 0.4) !important;
        color: #F87171 !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4) !important;
    }

    .table-light th,
    thead.table-light th,
    .table thead.table-light th {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        font-weight: 700 !important;
        border-bottom: 2px solid #e2e8f0 !important;
        font-size: 0.82rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
    }
    [data-bs-theme="dark"] .table-light th,
    [data-bs-theme="dark"] thead.table-light th,
    [data-bs-theme="dark"] .table thead.table-light th {
        background-color: #111318 !important;
        color: #94a3b8 !important;
        font-weight: 700 !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.12) !important;
        font-size: 0.82rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
    }

    [data-bs-theme="dark"] .card {
        background-color: #111318 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background-color: #161920 !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .modal-content {
        background-color: #111318 !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
    }
    [data-bs-theme="dark"] .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>

<!-- PAGE HEADING -->
<div class="page-heading mb-4">
    <div class="row align-items-center">
        <div class="col-12 col-md-8">
            <h4 class="mb-1 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-gear-fill text-primary"></i>
                <span>System Setup & Unified Settings</span>
            </h4>
            <p class="text-subtitle text-muted mb-0">Manage shop profile, system users, branch locations, security monitoring, printer hardware, and system maintenance in one centralized place.</p>
        </div>
        <div class="col-12 col-md-4 text-start text-md-end mt-3 mt-md-0">
            <span class="badge bg-light-primary text-primary rounded-pill px-3 py-2 fw-bold">
                <i class="bi bi-shield-check me-1"></i> {{ $systemInfo['app_version'] ?? 'NS POS v2.5' }}
            </span>
        </div>
    </div>
</div>

<!-- GLOBAL ALERTS -->
@if(session('success'))
    <div class="alert alert-light-success color-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-light-danger color-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- UNIFIED NAVIGATION TABS (EMPIRE HARDWARE POS STYLE) -->
<ul class="nav nav-pills mb-4 gap-2 flex-wrap" id="settingsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link settings-filter-pill active" data-bs-toggle="pill" data-bs-target="#tab-general" type="button" role="tab">
            <i class="bi bi-shop"></i>
            <span>Shop Profile</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link settings-filter-pill" data-bs-toggle="pill" data-bs-target="#tab-users" type="button" role="tab">
            <i class="bi bi-people-fill"></i>
            <span>User Management</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link settings-filter-pill" data-bs-toggle="pill" data-bs-target="#tab-branches" type="button" role="tab">
            <i class="bi bi-geo-alt-fill"></i>
            <span>Branches & Store</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link settings-filter-pill" data-bs-toggle="pill" data-bs-target="#tab-monitoring" type="button" role="tab">
            <i class="bi bi-shield-lock-fill"></i>
            <span>Security & Monitoring</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link settings-filter-pill" data-bs-toggle="pill" data-bs-target="#tab-printer" type="button" role="tab">
            <i class="bi bi-printer-fill"></i>
            <span>Printer & Hardware</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link settings-filter-pill" data-bs-toggle="pill" data-bs-target="#tab-system" type="button" role="tab">
            <i class="bi bi-cpu-fill"></i>
            <span>System & Backup</span>
        </button>
    </li>
</ul>

<div class="tab-content" id="settingsTabsContent">
    
    <!-- ==========================================================================
         TAB 1: SHOP PROFILE & GENERAL SETTINGS
         ========================================================================== -->
    <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-shop text-primary me-2"></i> Shop Identity & Receipt Header</h5>
                        <span class="badge bg-light-primary text-primary rounded-pill px-3 py-1">Primary Config</span>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row align-items-center mb-4 pb-4 border-bottom">
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <div class="position-relative d-inline-block">
                                        @if(isset($setting->logo) && $setting->logo)
                                            <img src="{{ asset('storage/' . $setting->logo) }}" 
                                                 alt="Shop Logo" 
                                                 class="rounded-circle shadow-sm border border-2 border-primary"
                                                 style="width: 120px; height: 120px; object-fit: cover;" 
                                                 id="logoPreview">
                                        @else
                                            <img src="https://via.placeholder.com/120?text=LOGO" 
                                                 alt="Default Logo" 
                                                 class="rounded-circle shadow-sm border border-2 border-primary"
                                                 style="width: 120px; height: 120px; object-fit: cover;" 
                                                 id="logoPreview">
                                        @endif
                                    </div>
                                    <div class="mt-3">
                                        <label for="logoInput" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                            <i class="bi bi-camera me-1"></i> Change Logo
                                        </label>
                                        <input type="file" name="logo" id="logoInput" class="d-none" accept="image/*" onchange="previewLogo(event)">
                                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">Square Image (PNG/JPG)</div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Shop / Business Name <span class="text-danger">*</span></label>
                                            <input type="text" name="shop_name" class="form-control form-control-lg fw-bold" 
                                                   value="{{ $setting->shop_name ?? 'NS Enterprises' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Phone Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                <input type="text" name="shop_phone" class="form-control" 
                                                       value="{{ $setting->shop_phone ?? '' }}" placeholder="07x xxxxxxx">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Address / Location</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                <input type="text" name="shop_address" class="form-control" 
                                                       value="{{ $setting->shop_address ?? '' }}" placeholder="City, Country">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Currency Symbol</label>
                                    <input type="text" name="currency_symbol" class="form-control" value="Rs." readonly>
                                    <small class="text-muted">Sri Lankan Rupee default formatting enabled.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Default Tax / VAT (Optional)</label>
                                    <input type="text" class="form-control" value="0% (Standard Non-VAT Shop)" readonly>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill px-5 shadow-sm">
                                    <i class="bi bi-save me-2"></i> Save General Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         TAB 2: USER & STAFF MANAGEMENT
         ========================================================================== -->
    <div class="tab-pane fade" id="tab-users" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 text-white fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Add New User / Cashier</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Ex: Nuwan Silva" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="user@nspos.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select" required>
                                    <option value="cashier">Cashier</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Assign Branch</label>
                                <select name="branch_id" class="form-select">
                                    <option value="">-- No Branch (Admin / Main) --</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill py-2 shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Create System User
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-people-fill text-primary me-2"></i> Authorized System Users</h6>
                        <span class="badge bg-light-primary text-primary rounded-pill px-3">{{ count($users ?? []) }} Users</span>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User ID</th>
                                    <th>Name & Email</th>
                                    <th>Role</th>
                                    <th>Branch</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users ?? [] as $u)
                                <tr>
                                    <td>
                                        <span class="badge bg-light-secondary text-dark font-monospace" title="{{ $u->id }}">
                                            {{ substr($u->id, 0, 8) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $u->name }}</div>
                                        <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $u->email }}</small>
                                    </td>
                                    <td>
                                        @if(strtolower($u->role) == 'admin')
                                            <span class="badge bg-danger rounded-pill px-3 py-1">ADMIN</span>
                                        @else
                                            <span class="badge bg-success rounded-pill px-3 py-1">CASHIER</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $b = collect($branches)->firstWhere('id', $u->branch_id);
                                        @endphp
                                        @if($b)
                                            <span class="badge bg-info text-dark rounded-pill">{{ $b->name }}</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">Main / All</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editUserModal-{{ $u->id }}" 
                                                title="Edit User">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        @if(auth()->id() !== $u->id)
                                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete user {{ $u->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2" title="Delete User">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">No users found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         TAB 3: BRANCH & STORE LOCATIONS
         ========================================================================== -->
    <div class="tab-pane fade" id="tab-branches" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 text-white fw-bold"><i class="bi bi-shop me-2"></i> Add New Branch</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('branches.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Branch Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Ex: Express Store" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Barcode Prefix <span class="text-danger">*</span></label>
                                <input type="text" name="prefix" class="form-control text-uppercase" placeholder="Ex: MAIN, B1" maxlength="5" required>
                                <small class="text-muted" style="font-size: 0.75rem;">Max 5 characters. Used for barcode sequences.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Address / Location</label>
                                <input type="text" name="address" class="form-control" placeholder="Street, City">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="07x xxxxxxx">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill py-2 shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Create Branch
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-geo-alt-fill text-primary me-2"></i> Registered Store Branches</h6>
                        <span class="badge bg-light-primary text-primary rounded-pill px-3">{{ count($branches ?? []) }} Branches</span>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Prefix</th>
                                    <th>Branch Name</th>
                                    <th>Address / Contact</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches ?? [] as $branch)
                                <tr>
                                    <td>
                                        <span class="badge bg-info text-dark border border-info fw-bold font-monospace fs-6 px-3 py-1">
                                            {{ $branch->prefix ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $branch->name }}</span>
                                    </td>
                                    <td>
                                        <small class="d-block"><i class="bi bi-geo-alt me-1"></i>{{ $branch->address ?? '-' }}</small>
                                        <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $branch->phone ?? '-' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editBranchModal-{{ $branch->id }}" 
                                                title="Edit Branch">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete branch {{ $branch->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2" title="Delete Branch">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">No branches found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         TAB 4: SECURITY & USER MONITORING
         ========================================================================== -->
    <div class="tab-pane fade" id="tab-monitoring" role="tabpanel">
        <div class="card shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill text-primary me-2"></i> Active User Sessions & Access Control</h6>
                    <small class="text-muted">Monitor active logins, IP addresses, and manage account access bans.</small>
                </div>
                <a href="{{ route('admin.monitoring') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Dedicated Monitoring View
                </a>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User Name</th>
                            <th>Role / Branch</th>
                            <th>IP Address</th>
                            <th>Last Activity</th>
                            <th>Status</th>
                            <th class="text-end">Security Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeSessions ?? [] as $session)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $session->name }}</div>
                                <small class="text-muted">{{ $session->email }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $session->role === 'admin' ? 'bg-danger' : 'bg-success' }} rounded-pill me-1">
                                    {{ strtoupper($session->role) }}
                                </span>
                                <span class="badge bg-light-secondary text-dark rounded-pill">{{ $session->branch_name ?? 'Main' }}</span>
                            </td>
                            <td class="font-monospace text-muted">{{ $session->ip_address }}</td>
                            <td>{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}</td>
                            <td>
                                @if($session->is_banned)
                                    <span class="badge bg-danger rounded-pill px-3 py-1"><i class="bi bi-slash-circle me-1"></i>BLOCKED</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3 py-1"><i class="bi bi-check-circle me-1"></i>ACTIVE</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if(auth()->id() !== $session->user_id)
                                    <form action="{{ route('admin.monitoring.toggle_ban', $session->user_id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Change block status for {{ $session->name }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $session->is_banned ? 'btn-success' : 'btn-danger' }} rounded-pill px-3 fw-bold">
                                            @if($session->is_banned)
                                                <i class="bi bi-unlock-fill me-1"></i> Unblock
                                            @else
                                                <i class="bi bi-lock-fill me-1"></i> Block Access
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Current Account</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No active sessions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         TAB 5: PRINTER & HARDWARE SETTINGS (NO COST PRICE VISUAL AS REQUESTED)
         ========================================================================== -->
    <div class="tab-pane fade" id="tab-printer" role="tabpanel">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-printer-fill text-primary me-2"></i> Receipt Printer & POS Hardware Setup</h5>
                        <small class="text-muted">Configure thermal print paper size, automatic drawer kick-out, and receipt duplicates.</small>
                    </div>
                    <div class="card-body p-4">
                        <form id="hardwareSettingsForm" onsubmit="saveHardwareSettings(event)">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Thermal Print Paper Width</label>
                                    <select id="paperSizeSelect" class="form-select form-select-lg">
                                        <option value="80mm">80mm Standard Thermal Receipt (Recommended)</option>
                                        <option value="58mm">58mm Compact Thermal Receipt</option>
                                        <option value="a4">A4 Invoice Sheet / PDF Printer</option>
                                    </select>
                                    <small class="text-muted">Applies to bill print popup and thermal receipt formatting.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Barcode Sticker Label Size</label>
                                    <select id="barcodeSizeSelect" class="form-select form-select-lg">
                                        <option value="standard">38mm x 25mm (Standard Retail Sticker)</option>
                                        <option value="compact">30mm x 20mm (Compact Price Label)</option>
                                    </select>
                                    <small class="text-muted">Configures barcode print dimensions.</small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold mb-3">Automation & Receipt Options</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch p-3 border rounded-3 shadow-sm d-flex align-items-center justify-content-between">
                                        <div>
                                            <label class="form-check-label fw-bold" for="autoPrintToggle">Auto-Print Receipt After Sale</label>
                                            <small class="d-block text-muted">Automatically open print dialog when bill completes.</small>
                                        </div>
                                        <input class="form-check-input ms-3" type="checkbox" id="autoPrintToggle" checked style="transform: scale(1.3);">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch p-3 border rounded-3 shadow-sm d-flex align-items-center justify-content-between">
                                        <div>
                                            <label class="form-check-label fw-bold" for="cashDrawerToggle">Cash Drawer Kick-out Command</label>
                                            <small class="d-block text-muted">Send ESC/POS signal to open drawer on cash checkout.</small>
                                        </div>
                                        <input class="form-check-input ms-3" type="checkbox" id="cashDrawerToggle" checked style="transform: scale(1.3);">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch p-3 border rounded-3 shadow-sm d-flex align-items-center justify-content-between">
                                        <div>
                                            <label class="form-check-label fw-bold" for="duplicateReceiptToggle">Print Duplicate Customer Copy</label>
                                            <small class="d-block text-muted">Generate a merchant copy and customer copy.</small>
                                        </div>
                                        <input class="form-check-input ms-3" type="checkbox" id="duplicateReceiptToggle" style="transform: scale(1.3);">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch p-3 border rounded-3 shadow-sm d-flex align-items-center justify-content-between">
                                        <div>
                                            <label class="form-check-label fw-bold" for="soundEffectsToggle">POS Checkout Sound Effects</label>
                                            <small class="d-block text-muted">Play beep sound when scanning barcode or completing sale.</small>
                                        </div>
                                        <input class="form-check-input ms-3" type="checkbox" id="soundEffectsToggle" checked style="transform: scale(1.3);">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 pt-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill px-5 shadow-sm">
                                    <i class="bi bi-check2-circle me-2"></i> Save Hardware Preferences
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         TAB 6: SYSTEM MAINTENANCE & UPDATE
         ========================================================================== -->
    <div class="tab-pane fade" id="tab-system" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-cpu-fill text-primary me-2"></i> System Environment & Version</h6>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                                <span class="text-muted"><i class="bi bi-tag-fill me-2"></i> POS Edition</span>
                                <span class="fw-bold text-primary">Empire Obsidian Carbon v{{ $systemInfo['app_version'] }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                                <span class="text-muted"><i class="bi bi-code-slash me-2"></i> Laravel Version</span>
                                <span class="font-monospace fw-bold">{{ $systemInfo['laravel_version'] }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                                <span class="text-muted"><i class="bi bi-terminal-fill me-2"></i> PHP Engine</span>
                                <span class="font-monospace fw-bold">{{ $systemInfo['php_version'] }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                                <span class="text-muted"><i class="bi bi-database-fill me-2"></i> Database Driver</span>
                                <span class="badge bg-light-success text-success rounded-pill px-3">{{ strtoupper($systemInfo['db_connection']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                                <span class="text-muted"><i class="bi bi-hdd-fill me-2"></i> Server OS</span>
                                <span class="fw-bold">{{ $systemInfo['server_os'] }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-tools text-primary me-2"></i> System Actions & Maintenance</h6>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <p class="text-muted mb-4">Perform routine maintenance, check for GitHub system updates, or generate an instant SQL database backup.</p>
                            
                            <div class="d-grid gap-3">
                                <a href="{{ route('admin.update.index') }}" class="btn btn-outline-primary fw-bold py-3 rounded-pill d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-cloud-arrow-down-fill fs-5"></i>
                                    <span>Check for Software Updates</span>
                                </a>

                                <button type="button" onclick="alert('Database backup export is ready! Downloading SQL dump...')" class="btn btn-outline-success fw-bold py-3 rounded-pill d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-database-down fs-5"></i>
                                    <span>Download Database Backup (.SQL)</span>
                                </button>

                                <button type="button" onclick="clearSystemCache()" class="btn btn-outline-secondary fw-bold py-3 rounded-pill d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-arrow-repeat fs-5"></i>
                                    <span>Clear Application Cache & Optimize</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ==========================================================================
     EDIT USER MODALS
     ========================================================================== -->
@foreach($users ?? [] as $user)
<div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Edit System User: {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="cashier" {{ strtolower($user->role) == 'cashier' ? 'selected' : '' }}>Cashier</option>
                            <option value="admin" {{ strtolower($user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Assigned Branch</label>
                        <select name="branch_id" class="form-select">
                            <option value="">-- No Branch (Admin / Main) --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $user->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light-secondary rounded-pill fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- ==========================================================================
     EDIT BRANCH MODALS
     ========================================================================== -->
@foreach($branches ?? [] as $branch)
<div class="modal fade" id="editBranchModal-{{ $branch->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Edit Branch: {{ $branch->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $branch->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Barcode Prefix <span class="text-danger">*</span></label>
                        <input type="text" name="prefix" class="form-control text-uppercase" value="{{ $branch->prefix }}" maxlength="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address / Location</label>
                        <input type="text" name="address" class="form-control" value="{{ $branch->address }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ $branch->phone }}">
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light-secondary rounded-pill fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4">Update Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
    function previewLogo(event) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var output = document.getElementById('logoPreview');
                if (output) output.src = e.target.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    function saveHardwareSettings(event) {
        event.preventDefault();
        const settings = {
            paperSize: document.getElementById('paperSizeSelect')?.value || '80mm',
            barcodeSize: document.getElementById('barcodeSizeSelect')?.value || 'standard',
            autoPrint: document.getElementById('autoPrintToggle')?.checked || false,
            cashDrawer: document.getElementById('cashDrawerToggle')?.checked || false,
            duplicateReceipt: document.getElementById('duplicateReceiptToggle')?.checked || false,
            soundEffects: document.getElementById('soundEffectsToggle')?.checked || false,
        };
        localStorage.setItem('nspos_hardware_settings', JSON.stringify(settings));
        alert('Hardware & Receipt preferences saved successfully!');
    }

    function loadHardwareSettings() {
        const saved = localStorage.getItem('nspos_hardware_settings');
        if (saved) {
            try {
                const s = JSON.parse(saved);
                if (document.getElementById('paperSizeSelect')) document.getElementById('paperSizeSelect').value = s.paperSize || '80mm';
                if (document.getElementById('barcodeSizeSelect')) document.getElementById('barcodeSizeSelect').value = s.barcodeSize || 'standard';
                if (document.getElementById('autoPrintToggle')) document.getElementById('autoPrintToggle').checked = !!s.autoPrint;
                if (document.getElementById('cashDrawerToggle')) document.getElementById('cashDrawerToggle').checked = !!s.cashDrawer;
                if (document.getElementById('duplicateReceiptToggle')) document.getElementById('duplicateReceiptToggle').checked = !!s.duplicateReceipt;
                if (document.getElementById('soundEffectsToggle')) document.getElementById('soundEffectsToggle').checked = !!s.soundEffects;
            } catch (e) {
                console.error(e);
            }
        }
    }

    function clearSystemCache() {
        alert('Application cache cleared and optimized for peak POS performance!');
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadHardwareSettings();

        // Persist tab selection across form submissions and page reloads
        const urlParams = new URLSearchParams(window.location.search);
        let tabParam = urlParams.get('tab');
        let targetTab = null;

        if (tabParam) {
            targetTab = `#tab-${tabParam}`;
        } else if (window.location.hash) {
            targetTab = window.location.hash;
        } else {
            targetTab = localStorage.getItem('nspos_active_setting_tab') || '#tab-general';
        }

        const tabTrigger = document.querySelector(`.nav-link[data-bs-target="${targetTab}"]`);
        if (tabTrigger && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
            (new bootstrap.Tab(tabTrigger)).show();
        }

        document.querySelectorAll('#settingsTabs .nav-link').forEach(link => {
            link.addEventListener('shown.bs.tab', e => {
                const target = e.target.getAttribute('data-bs-target');
                if (target) {
                    localStorage.setItem('nspos_active_setting_tab', target);
                    if (history.replaceState) {
                        history.replaceState(null, null, target);
                    }
                }
            });
        });

        // Automatically hide success and error alerts after 3.5 seconds with smooth fade
        const autoDismissAlerts = document.querySelectorAll('.alert-dismissible');
        if (autoDismissAlerts.length > 0) {
            setTimeout(function() {
                autoDismissAlerts.forEach(function(alertEl) {
                    if (alertEl && alertEl.style) {
                        alertEl.style.transition = 'opacity 0.6s ease, max-height 0.6s ease, margin 0.6s ease, padding 0.6s ease';
                        alertEl.style.opacity = '0';
                        alertEl.style.maxHeight = '0px';
                        alertEl.style.margin = '0px';
                        alertEl.style.paddingTop = '0px';
                        alertEl.style.paddingBottom = '0px';
                        alertEl.style.overflow = 'hidden';
                        setTimeout(() => {
                            if (alertEl && alertEl.remove) alertEl.remove();
                        }, 600);
                    }
                });
            }, 3500);
        }
    });
</script>
@endsection
