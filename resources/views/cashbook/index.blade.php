@extends('layouts.admin')
@section('title', 'Cash in Hand')
@section('header', 'Cash in Hand Register')

@section('content')
<style>
    :root {
        --empire-blue: #007CEF;
        --empire-dark-bg: #111318;
        --empire-dark-card: #161920;
        --empire-dark-input: #1D212A;
    }

    /* Light Mode Custom Styling */
    .search-input-custom {
        border-radius: 50rem;
        padding-left: 2.75rem;
        border: 1px solid #d1d5db;
        background-color: #ffffff;
        color: #1e293b;
        transition: all 0.2s ease;
    }
    .search-input-custom:focus {
        border-color: #007CEF;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.15);
        background-color: #ffffff;
    }

    /* Filter Bar Container - Crisp in Both Modes */
    .filter-bar-container {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    /* Filter Pills */
    .cash-filter-pill {
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        color: #475569;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.35rem 0.9rem;
        border-radius: 50rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        cursor: pointer;
    }
    .cash-filter-pill:hover {
        border-color: #94a3b8;
        background-color: #f1f5f9;
        color: #1e293b;
    }
    .cash-filter-pill.active {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff !important;
        border-color: #334155;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.25);
    }
    [data-bs-theme="dark"] .cash-filter-pill.active {
        background: linear-gradient(135deg, #2A2E39 0%, #161920 100%) !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5) !important;
    }

    /* Card hover micro-animation */
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
    }

    /* ==========================================================================
       OBSIDIAN CARBON DARK MODE OVERRIDES - PERFECTION IN DARK MODE
       ========================================================================== */
    [data-bs-theme="dark"] .search-input-custom {
        background-color: var(--empire-dark-input) !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        color: #e2e8f0 !important;
        border-radius: 50rem !important;
        padding-left: 2.75rem !important;
    }
    [data-bs-theme="dark"] .search-input-custom:focus {
        border-color: rgba(255, 255, 255, 0.4) !important;
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.15) !important;
    }

    [data-bs-theme="dark"] .filter-bar-container {
        background-color: var(--empire-dark-card) !important;
        border-bottom-color: rgba(255, 255, 255, 0.08) !important;
    }

    [data-bs-theme="dark"] .cash-filter-pill {
        border-color: rgba(255, 255, 255, 0.15) !important;
        background-color: var(--empire-dark-input) !important;
        color: #94a3b8 !important;
        border-radius: 50rem !important;
    }
    [data-bs-theme="dark"] .cash-filter-pill:hover {
        border-color: rgba(255, 255, 255, 0.3) !important;
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #f8fafc !important;
    }
    [data-bs-theme="dark"] .cash-filter-pill.active {
        background: linear-gradient(135deg, #2A2E39 0%, #161920 100%) !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5) !important;
    }

    [data-bs-theme="dark"] .card {
        background-color: var(--empire-dark-card) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .table {
        color: #e2e8f0 !important;
    }
    [data-bs-theme="dark"] .table-light th {
        background-color: #1a1e26 !important;
        color: #94a3b8 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    [data-bs-theme="dark"] .table td {
        border-bottom-color: rgba(255, 255, 255, 0.05) !important;
    }

    /* Modal Form Inputs in Dark Mode */
    [data-bs-theme="dark"] .modal-content {
        background-color: var(--empire-dark-card) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background-color: var(--empire-dark-input) !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        color: #f8fafc !important;
    }
    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        border-color: var(--empire-blue) !important;
        box-shadow: 0 0 0 3px rgba(0, 124, 239, 0.2) !important;
    }
</style>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px;">
    <i class="bi bi-check-circle-fill fs-5"></i>
    <div class="fw-bold">{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px;">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div class="fw-bold">{{ session('error') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- TOP FINANCIAL STATS CARDS -->
@php
    $manualCashIn = $transactions->where('type', 'in')->sum('amount');
    $manualCashOut = $transactions->where('type', 'out')->sum('amount');
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-5">
        <div class="card bg-success text-white mb-0 h-100 hover-lift shadow-sm" style="border-radius: 16px; border: 0 !important; background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="text-white text-uppercase fw-bolder mb-1" style="font-size: 0.75rem; letter-spacing: 0.08em; opacity: 0.9;">Current Cash in Hand</h6>
                        <h2 class="fw-bold mb-0 text-white" style="font-size: 2.1rem;">Rs. {{ number_format($currentCashInHand, 2) }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-wallet2 fs-3 text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 pt-2 border-top border-white border-opacity-25 mt-3">
                    <span class="badge bg-white text-success fw-bold px-2 py-1" style="font-size: 0.75rem;">LIVE BALANCE</span>
                    <small class="text-white text-opacity-75">Auto-calculated from POS Sales & Manual Ledger</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card mb-0 h-100 hover-lift shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Manual Cash In (+)</h6>
                        <h3 class="fw-bold mb-0 text-success">Rs. {{ number_format($manualCashIn, 2) }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: rgba(16, 185, 129, 0.15);">
                        <i class="bi bi-arrow-down-left text-success fs-5"></i>
                    </div>
                </div>
                <small class="text-muted mt-3 d-block">Total manual opening & deposits</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-0 h-100 hover-lift shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Manual Cash Out (-)</h6>
                        <h3 class="fw-bold mb-0 text-danger">Rs. {{ number_format($manualCashOut, 2) }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: rgba(239, 68, 68, 0.15);">
                        <i class="bi bi-arrow-up-right text-danger fs-5"></i>
                    </div>
                </div>
                <small class="text-muted mt-3 d-block">Total expenses & bank transfers</small>
            </div>
        </div>
    </div>
</div>

<!-- QUICK ACTION BUTTONS -->
<div class="card mb-4 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted fw-bold small"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick Actions:</span>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <button class="btn btn-primary rounded-pill px-3 py-2 fw-bold shadow-sm hover-lift d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#openingBalanceModal">
                <i class="bi bi-sun-fill"></i>
                <span>Set Opening Balance</span>
            </button>
            <button class="btn btn-info text-dark rounded-pill px-3 py-2 fw-bold shadow-sm hover-lift d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#fromBankModal">
                <i class="bi bi-bank"></i>
                <span>Withdraw FROM Bank</span>
            </button>
            <button class="btn btn-success rounded-pill px-3 py-2 fw-bold shadow-sm hover-lift d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#otherDepositModal">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Other Cash Deposit</span>
            </button>
            <button class="btn btn-warning text-dark rounded-pill px-3 py-2 fw-bold shadow-sm hover-lift d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#expenseModal">
                <i class="bi bi-receipt"></i>
                <span>Record Cash Expense</span>
            </button>
        </div>
    </div>
</div>

<!-- CASH REGISTER TABLE CARD -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden;">
    <!-- CARD HEADER WITH SEARCH & FILTER -->
    <div class="card-header bg-transparent border-bottom p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-journal-text text-primary"></i>
                <span>Cash in Hand Register</span>
                <span class="badge bg-primary rounded-pill ms-1" id="visibleCashTxnCount">{{ count($transactions) }}</span>
            </h5>
            <p class="text-muted small mb-0">Live transaction history for opening balance, bank withdrawals, and petty cash expenses.</p>
        </div>

        <!-- SEARCH BAR -->
        <div class="position-relative" style="min-width: 280px; max-width: 380px; flex-grow: 1;">
            <i class="bi bi-search position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%); z-index: 5;"></i>
            <input type="text" 
                   id="cashSearchInput" 
                   class="form-control search-input-custom shadow-sm" 
                   placeholder="Search transactions, category..." 
                   autocomplete="off">
            <button type="button" 
                    id="clearCashSearchBtn" 
                    class="btn btn-sm position-absolute d-none" 
                    style="right: 0.5rem; top: 50%; transform: translateY(-50%); z-index: 5; color: #64748b;"
                    onclick="clearCashSearch()">
                <i class="bi bi-x-circle-fill fs-6"></i>
            </button>
        </div>
    </div>

    <!-- QUICK FILTER PILLS -->
    <div class="px-4 pt-3 pb-2 filter-bar-container border-bottom d-flex flex-wrap align-items-center gap-2">
        <span class="text-muted small fw-bold me-1"><i class="bi bi-funnel me-1"></i> Category:</span>
        <button type="button" class="cash-filter-pill active" data-filter="all" onclick="filterCashTransactions('all', this)">All Transactions</button>
        <button type="button" class="cash-filter-pill" data-filter="in" onclick="filterCashTransactions('in', this)"><i class="bi bi-arrow-down-left text-success"></i> Cash In (+)</button>
        <button type="button" class="cash-filter-pill" data-filter="out" onclick="filterCashTransactions('out', this)"><i class="bi bi-arrow-up-right text-danger"></i> Cash Out (-)</button>
        <button type="button" class="cash-filter-pill" data-filter="opening" onclick="filterCashTransactions('opening', this)">Opening Balance</button>
        <button type="button" class="cash-filter-pill" data-filter="from_bank" onclick="filterCashTransactions('from_bank', this)">From Bank</button>
        <button type="button" class="cash-filter-pill" data-filter="expense" onclick="filterCashTransactions('expense', this)">Expenses</button>
    </div>

    <!-- TABLE BODY -->
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 560px;">
            <table class="table table-hover align-middle mb-0" id="cashTable">
                <thead class="table-light sticky-top" style="z-index: 1;">
                    <tr>
                        <th class="ps-4 py-3" style="width: 18%;">Date & Time</th>
                        <th class="py-3" style="width: 18%;">Category</th>
                        <th class="py-3" style="width: 44%;">Description / Reason</th>
                        <th class="pe-4 py-3 text-end" style="width: 20%;">Amount (Rs)</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($transactions as $txn)
                    <tr class="cash-txn-row" 
                        data-type="{{ strtolower($txn->type) }}" 
                        data-category="{{ strtolower($txn->category) }}">
                        <td class="ps-4 py-3">
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($txn->created_at)->format('Y-m-d') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($txn->created_at)->format('h:i A') }}</small>
                        </td>
                        <td class="py-3">
                            @if($txn->category === 'opening')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                                    <i class="bi bi-sun me-1"></i> OPENING BALANCE
                                </span>
                            @elseif($txn->category === 'from_bank')
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1 fw-bold">
                                    <i class="bi bi-bank me-1"></i> FROM BANK
                                </span>
                            @elseif($txn->category === 'other')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold">
                                    <i class="bi bi-plus-circle me-1"></i> OTHER DEPOSIT
                                </span>
                            @elseif($txn->category === 'expense')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fw-bold">
                                    <i class="bi bi-receipt me-1"></i> CASH EXPENSE
                                </span>
                            @elseif($txn->category === 'to_bank')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1 fw-bold">
                                    <i class="bi bi-arrow-up-right me-1"></i> TO BANK
                                </span>
                            @else
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-1 fw-bold">
                                    {{ strtoupper(str_replace('_', ' ', $txn->category)) }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3">
                            <span class="fw-medium">{{ $txn->description }}</span>
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <span class="fw-bold fs-6 {{ $txn->type == 'in' ? 'text-success' : 'text-danger' }}">
                                {{ $txn->type == 'in' ? '+' : '-' }} Rs. {{ number_format($txn->amount, 2) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyCashRow">
                        <td colspan="4" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-wallet2 display-4 text-muted opacity-50 d-block mb-3"></i>
                                <h5 class="fw-bold text-muted">No Cash Transactions Found</h5>
                                <p class="text-muted small mb-3">Use the quick actions above to set your opening balance or record cash flows.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    <!-- NO MATCH SEARCH RESULT -->
                    <tr id="noCashSearchRow" class="d-none">
                        <td colspan="4" class="text-center py-5">
                            <div class="py-3">
                                <i class="bi bi-search display-6 text-muted opacity-50 d-block mb-2"></i>
                                <h6 class="fw-bold text-muted">No matching cash transactions found</h6>
                                <p class="text-muted small mb-0">Try checking for different keywords or category filter.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==========================================================================
   MODALS - ROUNDED PILL DESIGN & CRISP HEADERS
   ========================================================================== -->

<!-- 1. OPENING BALANCE MODAL -->
<div class="modal fade" id="openingBalanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('cashbook.store') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            @csrf
            <input type="hidden" name="category" value="opening">
            <div class="modal-header bg-primary text-white p-4">
                <h5 class="modal-title text-white fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-sun-fill"></i>
                    <span>Set Morning Opening Balance</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-primary small d-flex align-items-center gap-2 mb-3" style="border-radius: 12px;">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                    <div>This sets your starting cash drawer balance for the morning shift.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Amount (Rs) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control rounded-pill px-3 text-end fw-bold text-primary" placeholder="0.00" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Description / Note <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control rounded-pill px-3" value="Morning Opening Balance" required>
                </div>
            </div>
            <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Save Opening Balance</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. WITHDRAW FROM BANK MODAL -->
<div class="modal fade" id="fromBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('cashbook.store') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            @csrf
            <input type="hidden" name="category" value="from_bank">
            <div class="modal-header bg-info text-dark p-4">
                <h5 class="modal-title fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-bank"></i>
                    <span>Withdraw FROM Bank to Cash Drawer</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info small d-flex align-items-center gap-2 mb-3" style="border-radius: 12px;">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                    <div>Money will be deducted from the selected bank account and added to <b>Cash in Hand</b>.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select Bank Account <span class="text-danger">*</span></label>
                    <select name="bank_account_id" class="form-select rounded-pill px-3 py-2" required>
                        <option value="">-- Choose Bank --</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} (Bal: Rs. {{ number_format($bank->current_balance, 2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Withdrawal Amount (Rs) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control rounded-pill px-3 text-end fw-bold text-info" placeholder="0.00" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Description / Reason <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control rounded-pill px-3" value="Withdrawn from Bank" required>
                </div>
            </div>
            <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-info text-dark rounded-pill px-4 fw-bold shadow-sm">Transfer to Cash Drawer</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. OTHER CASH DEPOSIT MODAL -->
<div class="modal fade" id="otherDepositModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('cashbook.store') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            @csrf
            <input type="hidden" name="category" value="other">
            <div class="modal-header bg-success text-white p-4">
                <h5 class="modal-title text-white fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Other Cash Deposit</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Amount (Rs) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control rounded-pill px-3 text-end fw-bold text-success" placeholder="0.00" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Description / Source <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control rounded-pill px-3" placeholder="E.g. Owner cash contribution..." required>
                </div>
            </div>
            <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">Save Cash Deposit</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. RECORD CASH EXPENSE MODAL -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('cashbook.store') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            @csrf
            <input type="hidden" name="category" value="expense">
            <div class="modal-header bg-warning text-dark p-4">
                <h5 class="modal-title fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-receipt"></i>
                    <span>Record Petty Cash Expense</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning small d-flex align-items-center gap-2 mb-3" style="border-radius: 12px;">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>This amount will be deducted directly from your <b>Cash in Hand</b> drawer balance.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Amount (Rs) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control rounded-pill px-3 text-end fw-bold text-danger" placeholder="0.00" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Description / Purpose <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control rounded-pill px-3" placeholder="E.g. Office tea, minor hardware purchase..." required>
                </div>
            </div>
            <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">Record Expense</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let activeCashFilter = 'all';

    function filterCashTransactions(filter, btnElement) {
        activeCashFilter = filter.toLowerCase();
        
        document.querySelectorAll('.cash-filter-pill').forEach(btn => btn.classList.remove('active'));
        if (btnElement) {
            btnElement.classList.add('active');
        }
        
        applyCashFilters();
    }

    function applyCashFilters() {
        const searchInput = document.getElementById('cashSearchInput');
        const term = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const rows = document.querySelectorAll('.cash-txn-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const type = row.getAttribute('data-type');
            const cat = row.getAttribute('data-category');
            
            let matchesFilter = false;
            if (activeCashFilter === 'all') {
                matchesFilter = true;
            } else if (activeCashFilter === 'in' || activeCashFilter === 'out') {
                matchesFilter = (type === activeCashFilter);
            } else {
                matchesFilter = (cat === activeCashFilter);
            }

            let matchesSearch = term.length === 0 || text.includes(term);

            if (matchesFilter && matchesSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const countBadge = document.getElementById('visibleCashTxnCount');
        if (countBadge) {
            countBadge.innerText = visibleCount;
        }

        const noSearchRow = document.getElementById('noCashSearchRow');
        const emptyRow = document.getElementById('emptyCashRow');
        if (noSearchRow) {
            if (visibleCount === 0 && rows.length > 0) {
                noSearchRow.classList.remove('d-none');
            } else {
                noSearchRow.classList.add('d-none');
            }
        }
        if (emptyRow && rows.length > 0) {
            emptyRow.style.display = 'none';
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('cashSearchInput');
        const clearBtn = document.getElementById('clearCashSearchBtn');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.trim();
                if (term.length > 0) {
                    clearBtn.classList.remove('d-none');
                } else {
                    clearBtn.classList.add('d-none');
                }
                applyCashFilters();
            });
        }
    });

    function clearCashSearch() {
        const searchInput = document.getElementById('cashSearchInput');
        const clearBtn = document.getElementById('clearCashSearchBtn');

        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        }
        if (clearBtn) {
            clearBtn.classList.add('d-none');
        }
        applyCashFilters();
    }
</script>
@endsection
