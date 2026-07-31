@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<style>
    .stat-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    
    [data-bs-theme="dark"] .stat-card:hover { box-shadow: 0 12px 30px rgba(0,0,0,0.8) !important; background-color: #171A21 !important; border-color: rgba(255, 255, 255, 0.2) !important; }
    [data-bs-theme="dark"] .table { color: #F8FAFC; border-color: rgba(255, 255, 255, 0.08); }
    [data-bs-theme="dark"] .table-hover tbody tr:hover { color: #fff; background-color: #171A21; }
    [data-bs-theme="dark"] .card { background-color: #111318 !important; border: 1px solid rgba(255, 255, 255, 0.08) !important; }
    [data-bs-theme="dark"] .text-body { color: #F8FAFC !important; }
    [data-bs-theme="dark"] .text-body-secondary { color: #94A3B8 !important; }

    .value-update-flash {
        animation: valueFlash 1s ease-out;
    }
    
    @keyframes valueFlash {
        0% { color: inherit; }
        50% { color: #007CEF; transform: scale(1.05); }
        100% { color: inherit; transform: scale(1); }
    }
</style>

<div class="page-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-uppercase tracking-wider fw-bold text-body-secondary mb-0" style="font-size: 0.85rem; letter-spacing: 0.08em;">{{ __('DASHBOARD') }}</h3>
        <span class="text-muted small"><i class="bi bi-clock me-1"></i> Live Updating</span>
    </div>

    <!-- Top Financial Summary Cards (Existing System Metrics + Empire POS Polish) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body stat-card position-relative overflow-hidden mb-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-body-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Today Sales</span>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; background: rgba(0, 124, 239, 0.15); color: #007CEF;">
                            <i class="bi bi-cart-check-fill fs-6"></i>
                        </div>
                    </div>
                    <div>
                        <h4 class="fw-bolder mb-1 text-body fs-5">Rs. <span id="stat-today-sales">{{ number_format($todaySales, 2) }}</span></h4>
                        <div class="small text-muted d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                            <i class="bi bi-arrow-up-right text-primary-custom"></i> Today's Total
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body stat-card position-relative overflow-hidden mb-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-body-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Today Profit</span>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; background: rgba(0, 210, 0, 0.15); color: #00D200;">
                            <i class="bi bi-graph-up-arrow fs-6"></i>
                        </div>
                    </div>
                    <div>
                        <h4 class="fw-bolder mb-1 text-success fs-5">Rs. <span id="stat-today-profit">{{ number_format($todayProfit, 2) }}</span></h4>
                        <div class="small text-muted d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                            <i class="bi bi-check-circle-fill text-success"></i> Net Revenue
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body stat-card position-relative overflow-hidden mb-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-body-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Monthly Sales</span>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; background: rgba(13, 202, 240, 0.15); color: #0DCAF0;">
                            <i class="bi bi-calendar2-check-fill fs-6"></i>
                        </div>
                    </div>
                    <div>
                        <h4 class="fw-bolder mb-1 text-body fs-5">Rs. <span id="stat-monthly-sales">{{ number_format($monthlySales, 2) }}</span></h4>
                        <div class="small text-muted d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                            <i class="bi bi-calendar-range text-info"></i> This Month
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body stat-card position-relative overflow-hidden mb-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-body-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Monthly Profit</span>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; background: rgba(255, 199, 0, 0.15); color: #FFC700;">
                            <i class="bi bi-piggy-bank-fill fs-6"></i>
                        </div>
                    </div>
                    <div>
                        <h4 class="fw-bolder mb-1 text-warning fs-5">Rs. <span id="stat-monthly-profit">{{ number_format($monthlyProfit, 2) }}</span></h4>
                        <div class="small text-muted d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                            <i class="bi bi-graph-up text-warning"></i> Monthly Net
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body stat-card position-relative overflow-hidden mb-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-body-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Cash In Hand</span>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; background: rgba(111, 66, 193, 0.15); color: #6F42C1;">
                            <i class="bi bi-wallet2 fs-6"></i>
                        </div>
                    </div>
                    <div>
                        <h4 class="fw-bolder mb-1 text-body fs-5">Rs. <span id="stat-cash-in-hand">{{ number_format($cashInHand, 2) }}</span></h4>
                        <div class="small text-muted d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                            <i class="bi bi-cash-stack" style="color: #6F42C1;"></i> Available Cash
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body stat-card position-relative overflow-hidden mb-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-body-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Bank Balance</span>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; background: rgba(32, 201, 151, 0.15); color: #20C997;">
                            <i class="bi bi-bank fs-6"></i>
                        </div>
                    </div>
                    <div>
                        <h4 class="fw-bolder mb-1 text-body fs-5">Rs. <span id="stat-bank-balance">{{ number_format($totalBankBalance, 2) }}</span></h4>
                        <div class="small text-muted d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                            <i class="bi bi-shield-check" style="color: #20C997;"></i> Accounts Total
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid Container (Empire POS Layout) -->
    <div class="row g-4 pb-5">
        
        <!-- Row 1: Stock Status Widgets -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body">
                <div class="card-body p-3 p-lg-4 d-flex flex-column flex-sm-row justify-content-between align-items-center text-center text-sm-start position-relative overflow-hidden gap-3 gap-sm-0">
                    <div>
                        <h3 class="fs-5 fw-bold text-body">{{ __('Current Stocks') }}</h3>
                        <div class="display-3 fw-bolder lh-1 mt-3 text-body">{{ $currentStockItemsCount }}</div>
                        <p class="fs-6 fw-semibold mt-2 text-body-secondary mb-0">{{ __('Of') }} {{ $totalProducts }} {{ __('Product') }}</p>
                    </div>
                    <div class="position-relative" style="width: 140px; height: 140px;">
                        <svg class="w-100 h-100" viewBox="0 0 100 100" style="transform: rotate(-90deg);">
                            <circle cx="50" cy="50" r="40" stroke="rgba(0, 210, 0, 0.15)" stroke-width="12" fill="none" />
                            <circle cx="50" cy="50" r="40" stroke="#00D200" stroke-width="12" fill="none" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (251.2 * $currentStockPercentage / 100) }}" stroke-linecap="round" />
                        </svg>
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <span class="fs-4 fw-bolder" style="color: #00D200;">{{ $currentStockPercentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body">
                <div class="card-body p-3 p-lg-4 d-flex flex-column flex-sm-row justify-content-between align-items-center text-center text-sm-start position-relative overflow-hidden gap-3 gap-sm-0">
                    <div>
                        <h3 class="fs-5 fw-bold text-body">{{ __('Out of Stock') }}</h3>
                        <div class="display-3 fw-bolder lh-1 mt-3 text-body">{{ $outOfStockItemsCount }}</div>
                        <p class="fs-6 fw-semibold mt-2 text-body-secondary mb-0">{{ __('Of') }} {{ $totalProducts }} {{ __('Product') }}</p>
                    </div>
                    <div class="position-relative" style="width: 140px; height: 140px;">
                        <svg class="w-100 h-100" viewBox="0 0 100 100" style="transform: rotate(-90deg);">
                            <circle cx="50" cy="50" r="40" stroke="rgba(255, 51, 51, 0.15)" stroke-width="12" fill="none" />
                            <circle cx="50" cy="50" r="40" stroke="#FF3333" stroke-width="12" fill="none" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (251.2 * $outOfStockPercentage / 100) }}" stroke-linecap="round" />
                        </svg>
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <span class="fs-4 fw-bolder" style="color: #FF3333;">{{ $outOfStockPercentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Out of Stock Alert Table + Invoice Quick Actions -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fs-5 fw-bold mb-0 text-body">{{ __('Out of Stock Alert') }}</h3>
                        <a href="{{ route('products.index') }}" class="small text-decoration-none fw-semibold">View All Items</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless text-start align-middle fw-semibold text-body mb-0">
                            <thead>
                                <tr class="text-body small border-bottom">
                                    <th class="pb-3 fw-bold ps-0">{{ __('Product Name') }}</th>
                                    <th class="pb-3 fw-bold">{{ __('Code') }}</th>
                                    <th class="pb-3 fw-bold">{{ __('Qut of Now') }}</th>
                                    <th class="pb-3 pe-0 text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outOfStockAlerts as $alert)
                                <tr>
                                    <td class="py-3 ps-0 border-bottom">{{ $alert->name }}</td>
                                    <td class="border-bottom">{{ $alert->code ?? 'N/A' }}</td>
                                    <td class="border-bottom"><span class="badge bg-light-danger text-danger">{{ $alert->batches_sum_quantity }}</span></td>
                                    <td class="text-end pe-0 border-bottom"><a href="{{ route('suppliers.index') }}" class="btn btn-primary-custom px-4 py-1 rounded-3 small fw-bold shadow-sm">{{ __('Order') }}</a></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-4 ps-0 text-muted text-center">{{ __('No out of stock alerts.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice / POS Terminal Section -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body">
                <div class="card-body p-3 p-lg-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h3 class="fs-5 fw-bold text-body">{{ __('Sales & Invoices') }}</h3>
                        <div class="text-end">
                            <div class="small fw-semibold text-body-secondary">{{ __('Invoices (This Month)') }}</div>
                            <div class="display-4 fw-bolder lh-1 text-body mt-1">{{ $totalInvoices }}</div>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 mt-auto">
                        <a href="{{ route('pos.index') }}" class="btn btn-primary-custom fw-bold py-3 rounded-4 shadow-sm fs-5 w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-cart-fill"></i> {{ __('Open POS Terminal') }}
                        </a>
                        <a href="{{ route('cashier.daily_sales') }}" class="btn btn-outline-primary-custom fw-bold py-3 rounded-4 fs-5 w-100 transition d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-file-earmark-bar-graph"></i> {{ __('Daily Sales Report') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Employee Status & Cheque/Credit Reminder -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body">
                <div class="card-body p-3 p-lg-4 d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fs-5 fw-bold text-body mb-1">{{ __('Staff Overview') }}</h3>
                        <p class="small text-body-secondary fw-semibold mb-4">{{ __('Active System Users') }}</p>
                        <div class="d-flex align-items-end gap-2 mb-4">
                            <span class="display-3 fw-bolder lh-1 text-body">{{ str_pad($attendanceCount, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="fs-5 fw-bold mb-1 text-body-secondary">{{ __('Of') }} {{ str_pad($totalEmployees, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 mt-auto">
                        <a href="{{ route('users.index') }}" class="btn btn-primary-custom fw-bold py-3 rounded-4 shadow-sm fs-6 w-100">{{ __('Manage Users & Cashiers') }}</a>
                        <a href="{{ route('settings.index') }}" class="btn btn-outline-primary-custom fw-bold py-3 rounded-4 fs-6 w-100 transition">{{ __('System Settings') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cheque & Credit Reminder -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(to right, #FFE700, #FFC700);">
                <div class="position-absolute opacity-75" style="right: -30px; bottom: -50px; filter: drop-shadow(0 25px 25px rgba(0,0,0,0.25));">
                    <svg width="250" height="250" viewBox="0 0 24 24" fill="none" stroke="#402D00" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" fill="#402D00"></path>
                        <line x1="12" y1="9" x2="12" y2="13" stroke="#FFE700" stroke-width="3"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17" stroke="#FFE700" stroke-width="3"></line>
                    </svg>
                </div>

                <div class="card-body p-3 p-lg-4 position-relative z-1 w-100" style="max-width: 100%; z-index: 2;">
                    <h3 class="small fw-bold text-uppercase mb-2" style="color: #624100;">{{ __('Cheque & Credit Reminder') }}</h3>
                    <h4 class="fs-4 fw-bolder mb-4" style="color: #624100;">{{ __('Due Window') }} - {{ \Carbon\Carbon::today()->format('d/m/Y') }} {{ __('(7 Days)') }}</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless fw-semibold text-start mb-0" style="color: #624100;">
                            <thead>
                                <tr>
                                    <th class="pb-2 small ps-0" style="color: inherit;">{{ __('Number') }}</th>
                                    <th class="pb-2 small" style="color: inherit;">{{ __('Name / Party') }}</th>
                                    <th class="pb-2 small" style="color: inherit;">{{ __('Type') }}</th>
                                    <th class="pb-2 small" style="color: inherit;">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingReminders as $reminder)
                                <tr>
                                    <td class="py-2 ps-0 border-bottom border-warning" style="color: inherit;">{{ $reminder->invoice_no }}</td>
                                    <td class="border-bottom border-warning" style="color: inherit;">{{ $reminder->name }}</td>
                                    <td class="border-bottom border-warning" style="color: inherit;">{{ $reminder->type }}</td>
                                    <td class="border-bottom border-warning" style="color: inherit;">Rs.{{ number_format($reminder->amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-3 ps-0" style="color: inherit;">{{ __('No pending cheque or credit reminders due soon.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('cheques.index') }}" class="btn mt-4 px-4 py-2 rounded-3 fw-bold shadow-sm" style="background-color: #624100 !important; color: #EBA00F !important; border: 2px solid #624100 !important; display: inline-block !important; opacity: 1 !important;">{{ __('Get Action') }}</a>
                </div>
            </div>
        </div>

        <!-- Row 4: Fast Moving & Slow Moving Items -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body position-relative overflow-hidden">
                <div class="position-absolute bottom-0 start-0 w-100 h-50 opacity-50 z-0" style="background: linear-gradient(to top, rgba(25,135,84,0.15), transparent);"></div>
                
                <div class="card-body p-3 p-lg-4 position-relative z-1 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start">
                        <h3 class="fs-5 fw-bold text-body d-flex align-items-center gap-2">
                            {{ __('Fast Moving Items (This Month)') }}
                            <svg width="18" height="18" fill="#198754" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                        </h3>
                    </div>
                    
                    <div class="d-flex mt-4" style="height: 160px;">
                        <div class="w-75 d-flex align-items-end justify-content-around border-bottom border-start pb-2 ps-2 text-body-tertiary" style="font-size: 0.75rem;">
                            @php
                                $maxQty = collect($fastMovingItems)->max('total_sold') ?: 1;
                                $colors = ['#86EFAC', '#4ADE80', '#22C55E', '#16A34A', '#15803D'];
                            @endphp
                            @foreach($fastMovingItems as $index => $item)
                                @php $height = max(10, ($item->total_sold / $maxQty) * 100); @endphp
                                <div class="rounded-top shadow-sm position-relative" style="width: 32px; height: {{ $height }}%; background-color: {{ $colors[$index % 5] }};" title="{{ $item->total_sold }} sold"></div>
                            @endforeach
                        </div>
                        <div class="w-25 d-flex flex-column justify-content-start ps-4 py-2 fw-bold text-body small gap-2">
                            @foreach($fastMovingItems as $index => $item)
                            <div class="d-flex align-items-center gap-2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->name }}">
                                <span class="rounded flex-shrink-0" style="width: 14px; height: 14px; background-color: {{ $colors[$index % 5] }};"></span> 
                                <span class="text-truncate">{{ $item->name }}</span>
                            </div>
                            @endforeach
                            @if(count($fastMovingItems) == 0)
                                <div class="text-muted">{{ __('No sales data') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body position-relative overflow-hidden">
                <div class="position-absolute bottom-0 start-0 w-100 h-50 opacity-50 z-0" style="background: linear-gradient(to top, rgba(220,53,69,0.15), transparent);"></div>
                
                <div class="card-body p-3 p-lg-4 position-relative z-1 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start">
                        <h3 class="fs-5 fw-bold text-body d-flex align-items-center gap-2">
                            {{ __('Slow Moving Items (This Month)') }}
                            <svg width="18" height="18" fill="#dc3545" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </h3>
                    </div>
                    
                    <div class="d-flex mt-4" style="height: 160px;">
                        <div class="w-75 d-flex align-items-end justify-content-around border-bottom border-start pb-2 ps-2 text-body-tertiary" style="font-size: 0.75rem;">
                            @php
                                $maxSlowQty = collect($slowMovingItems)->max('total_sold') ?: 1;
                                $slowColors = ['#FCA5A5', '#F87171', '#EF4444', '#DC2626', '#B91C1C'];
                            @endphp
                            @foreach($slowMovingItems as $index => $item)
                                @php $height = max(10, ($item->total_sold / $maxSlowQty) * 100); @endphp
                                <div class="rounded-top shadow-sm" style="width: 32px; height: {{ $height }}%; background-color: {{ $slowColors[$index % 5] }};" title="{{ $item->total_sold }} sold"></div>
                            @endforeach
                        </div>
                        <div class="w-25 d-flex flex-column justify-content-start ps-4 py-2 fw-bold text-body small gap-2">
                            @foreach($slowMovingItems as $index => $item)
                            <div class="d-flex align-items-center gap-2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->name }}">
                                <span class="rounded flex-shrink-0" style="width: 14px; height: 14px; background-color: {{ $slowColors[$index % 5] }};"></span> 
                                <span class="text-truncate">{{ $item->name }}</span>
                            </div>
                            @endforeach
                            @if(count($slowMovingItems) == 0)
                                <div class="text-muted">{{ __('No data') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 5: 6-Month Sales Analytics & 7-Day Sales Forecast -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 rounded-4 shadow-sm bg-body h-100">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fs-5 fw-bold text-body mb-0">{{ __('6-Month Sales Analytics') }}</h3>
                        <span class="badge bg-light-primary text-primary px-3 py-2 fw-bold">Revenue Trend</span>
                    </div>
                    <div style="position: relative; height: 320px; max-height: 320px; width: 100%; overflow: hidden;">
                        <canvas id="monthlySalesChart" style="max-height: 320px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7-Day Sales Forecast (AI Powered) -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 rounded-4 shadow-sm bg-body h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-3 opacity-25">
                    <svg width="64" height="64" fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                        <path d="M12.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 1 .5-.5zm-4 3a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5zm-4 2a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 1 .5-.5z"/>
                        <path fill-rule="evenodd" d="M14.5 13.5a.5.5 0 0 1 .5.5H1a.5.5 0 0 1 0-1h13a.5.5 0 0 1 .5.5z"/>
                        <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5-2.646 2.647a.5.5 0 0 1-.708-.708l3-3a.5.5 0 0 1 .708 0l1.5 1.5 2.646-2.647a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </div>
                <div class="card-body p-3 p-lg-4 position-relative z-1 d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary text-white rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
                            {{ __('AI Powered') }}
                        </span>
                    </div>
                    <h3 class="fs-5 fw-bold text-body mb-3">{{ __('7-Day Sales Forecast') }}</h3>
                    <p class="text-muted small mb-4">{{ __('Based on linear regression of the past 30 days of sales data, here is the projected revenue trend for the coming week.') }}</p>
                    
                    <div style="position: relative; height: 260px; max-height: 260px; width: 100%; overflow: hidden;">
                        <canvas id="forecastChart" style="max-height: 260px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 6: Recent Transactions & Top Selling Products (From Existing System) -->
        <!-- Row 6: Recent Transactions & Top Selling Products (Empire POS Style) -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 rounded-4 shadow-sm bg-body h-100 position-relative overflow-hidden">
                <div class="card-header bg-transparent pt-4 px-4 pb-3 d-flex justify-content-between align-items-center border-bottom border-secondary-subtle">
                    <h5 class="fw-bold mb-0 text-body d-flex align-items-center gap-2">
                        <i class="bi bi-receipt-cutoff text-primary-custom"></i>
                        {{ __('Recent POS Transactions') }}
                    </h5>
                    <a href="{{ route('cashier.daily_sales') }}" class="btn btn-sm btn-outline-primary-custom px-3 py-1">{{ __('View All') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="text-body-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em; background-color: rgba(0,0,0,0.02);">
                                    <th class="py-3 ps-4 fw-bold">{{ __('INVOICE') }}</th>
                                    <th class="py-3 fw-bold">{{ __('DATE') }}</th>
                                    <th class="py-3 text-end fw-bold">{{ __('AMOUNT') }}</th>
                                    <th class="py-3 text-center fw-bold">{{ __('CASHIER') }}</th>
                                    <th class="py-3 pe-4 text-center fw-bold">{{ __('ACTION') }}</th>
                                </tr>
                            </thead>
                            <tbody id="recent-transactions-tbody">
                                @forelse($recentSales as $sale)
                                <tr class="border-bottom border-secondary-subtle">
                                    <td class="ps-4 py-3">
                                        <span class="fw-bold font-monospace text-primary-custom">{{ $sale->invoice_no }}</span>
                                    </td>
                                    <td class="text-muted small">
                                        <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($sale->created_at)->format('M d, H:i') }}
                                    </td>
                                    <td class="text-success fw-bolder text-end">
                                        Rs. {{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-1 fw-bold" style="background: rgba(0, 124, 239, 0.12); color: #007CEF;">
                                            <i class="bi bi-person-fill me-1"></i>{{ $sale->cashier_name }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-center">
                                        <button class="btn btn-sm btn-outline-primary-custom rounded-3 px-3 py-1" onclick="reprintBill('{{ $sale->id }}')" title="Reprint">
                                            <i class="bi bi-printer me-1"></i>{{ __('Print') }}
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        {{ __('No recent transactions found.') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 rounded-4 shadow-sm bg-body h-100 position-relative overflow-hidden">
                <div class="card-header bg-transparent pt-4 px-4 pb-3 d-flex justify-content-between align-items-center border-bottom border-secondary-subtle">
                    <h5 class="fw-bold mb-0 text-body d-flex align-items-center gap-2">
                        <i class="bi bi-trophy-fill text-warning"></i>
                        {{ __('Top Selling Products') }}
                    </h5>
                    <span class="badge rounded-pill bg-light text-muted small">{{ __('This Month') }}</span>
                </div>
                <div class="card-body p-0" id="top-products-container">
                    @php
                        $rankColors = ['#007CEF', '#00D200', '#FFC700', '#0DCAF0', '#6F42C1'];
                    @endphp
                    @forelse($topProducts as $index => $top)
                    <div class="px-4 py-3 d-flex justify-content-between align-items-center border-bottom border-secondary-subtle">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center fw-bolder text-white flex-shrink-0" style="width: 36px; height: 36px; background-color: {{ $rankColors[$index % 5] }}; font-size: 0.9rem;">
                                #{{ $index + 1 }}
                            </div>
                            <div class="name">
                                <h6 class="mb-1 text-truncate text-body fw-bold" style="max-width: 170px;" title="{{ $top->product_name }}">{{ $top->product_name }}</h6>
                                <span class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-bag-check-fill" style="color: {{ $rankColors[$index % 5] }};"></i> {{ __('Total Sold:') }} <strong>{{ (int)$top->total_qty }}</strong>
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="badge rounded-pill px-3 py-1 small fw-bold" style="background: rgba(0, 124, 239, 0.12); color: #007CEF;">
                                {{ (int)$top->total_qty }} {{ __('Sold') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-box-seam fs-3 d-block mb-2"></i>
                        {{ __('No sales data yet.') }}
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ==========================================
        // 6-Month Sales Analytics Line Chart
        // ==========================================
        const ctxMonthly = document.getElementById('monthlySalesChart');
        if (ctxMonthly) {
            new Chart(ctxMonthly, {
                type: 'line',
                data: {
                    labels: @json($monthlySalesLabels),
                    datasets: [{
                        label: 'Total Sales (Rs.)',
                        data: @json($monthlySalesData),
                        borderColor: '#007CEF',
                        backgroundColor: 'rgba(0, 124, 239, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#007CEF',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#007CEF',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(128, 128, 128, 0.2)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // ==========================================
        // 7-Day Sales Forecast Bar Chart
        // ==========================================
        const forecastCtx = document.getElementById('forecastChart');
        if (forecastCtx) {
            new Chart(forecastCtx, {
                type: 'bar',
                data: {
                    labels: @json($forecastLabels),
                    datasets: [{
                        label: 'Predicted Sales (Rs.)',
                        data: @json($forecastData),
                        backgroundColor: 'rgba(111, 66, 193, 0.8)',
                        borderRadius: 4,
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(128, 128, 128, 0.2)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // ==========================================
        // Bill Reprint & Live Updater (Existing)
        // ==========================================
        window.reprintBill = function(saleId) {
            let url = "{{ route('pos.print', ':id') }}".replace(':id', saleId);
            window.open(url, '_blank');
        };

        function updateElementValue(id, newValue, isCurrency = false) {
            const el = document.getElementById(id);
            if(!el || newValue === undefined) return;

            let displayVal = isCurrency ? 
                parseFloat(newValue).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : 
                newValue;

            if (el.innerText !== displayVal.toString()) {
                el.innerText = displayVal;
                el.classList.remove('value-update-flash');
                void el.offsetWidth;
                el.classList.add('value-update-flash');
            }
        }

        function fetchDashboardLiveStats() {
            fetch('{{ route('home') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error("Network Error");
                return response.json();
            })
            .then(data => {
                updateElementValue('stat-today-sales', data.todaySales, true);
                updateElementValue('stat-today-profit', data.todayProfit, true);
                updateElementValue('stat-monthly-sales', data.monthlySales, true);
                updateElementValue('stat-monthly-profit', data.monthlyProfit, true);
                updateElementValue('stat-cash-in-hand', data.cashInHand, true);
                updateElementValue('stat-bank-balance', data.totalBankBalance, true);
            })
            .catch(error => console.error('Live Update Error:', error));
        }

        setInterval(fetchDashboardLiveStats, 15000);
    });
</script>
@endsection
