@extends('layouts.admin')

@section('title', 'Finance Management')
@section('header', 'Bank & Expenses')

@section('content')
<style>
    :root {
        --empire-blue: #007CEF;
        --empire-dark-bg: #111318;
        --empire-dark-card: #161920;
        --empire-dark-input: #1D212A;
    }

    /* Light Mode Strong Borders */
    .search-input-custom {
        border: 2px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        color: #1e293b !important;
        transition: all 0.25s ease;
    }
    .search-input-custom:focus {
        border-color: #007CEF !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.15) !important;
        background-color: #ffffff !important;
    }

    /* Dark Mode Theme */
    [data-bs-theme="dark"] body {
        background-color: var(--empire-dark-bg) !important;
    }
    [data-bs-theme="dark"] .card,
    [data-bs-theme="dark"] .modal-content {
        background-color: var(--empire-dark-card) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .table {
        --bs-table-bg: transparent;
        --bs-table-color: #e2e8f0;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .search-input-custom {
        background-color: var(--empire-dark-input) !important;
        border: 2px solid rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
        border-radius: 50rem !important;
        padding-left: 2.75rem !important;
    }
    [data-bs-theme="dark"] .search-input-custom:focus {
        border-color: #007CEF !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.25) !important;
    }

    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(0,0,0,0.18) !important;
    }

    .table-scrollable {
        max-height: 60vh;
        overflow-y: auto;
    }
    .table-scrollable thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #f8fafc;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    [data-bs-theme="dark"] .table-scrollable thead th {
        background-color: #1a1e26 !important;
        color: #ffffff !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.35) !important;
    }

    /* Total Balance Banner Card */
    .balance-banner-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
        border: none !important;
        border-radius: 16px;
    }
    [data-bs-theme="dark"] .balance-banner-card {
        background: linear-gradient(135deg, #2A2E39 0%, #161920 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
    }

    /* Bank Account Cards Light & Dark Polish */
    .bank-card-normal {
        background-color: #ffffff;
        border: 1px solid #e2e8f0 !important;
        border-radius: 14px;
        transition: all 0.2s ease;
    }
    [data-bs-theme="dark"] .bank-card-normal {
        background-color: #1a1e26 !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25) !important;
    }

    .bank-card-primary {
        background: #fffbeb !important;
        border: 2px solid #f59e0b !important;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.12) !important;
        transition: all 0.2s ease;
    }
    [data-bs-theme="dark"] .bank-card-primary {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(22, 25, 32, 1) 100%) !important;
        border: 2px solid #ffc107 !important;
        box-shadow: 0 4px 18px rgba(255, 193, 7, 0.15) !important;
    }

    /* Filter Pills & Bar Container */
    .filter-bar-container {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    [data-bs-theme="dark"] .filter-bar-container {
        background-color: #1a1e26 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    .expense-filter-pill {
        border-radius: 50rem !important;
        padding: 0.4rem 1.05rem;
        font-size: 0.82rem;
        font-weight: 600;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .expense-filter-pill:hover {
        background-color: #f1f5f9;
        border-color: #94a3b8;
        color: #1e293b;
    }
    [data-bs-theme="dark"] .expense-filter-pill {
        background-color: #232834 !important;
        border: 1px solid rgba(255, 255, 255, 0.16) !important;
        color: #e2e8f0 !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3) !important;
        border-radius: 50rem !important;
    }
    [data-bs-theme="dark"] .expense-filter-pill:hover {
        background-color: #2c3242 !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
        color: #ffffff !important;
    }
    .expense-filter-pill.active,
    [data-bs-theme="dark"] .expense-filter-pill.active {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(220, 53, 69, 0.45) !important;
    }
</style>

<!-- PAGE HEADING -->
<div class="page-heading">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-lg-6 order-lg-1 order-last mt-3 mt-lg-0">
            <h4 class="mb-1 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-bank text-primary"></i>
                <span>Bank Accounts & Expenses</span>
            </h4>
            <p class="text-subtitle text-muted mb-0">Manage business bank accounts, transfer cash, and track all operating expenses.</p>
        </div>
        <div class="col-12 col-lg-6 order-lg-2 order-first">
            <div class="d-flex flex-wrap justify-content-start justify-content-lg-end gap-2">
                <button type="button" class="btn btn-success fw-bold px-3 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2 hover-lift" data-bs-toggle="modal" data-bs-target="#salesIncomeDepositModal">
                    <i class="bi bi-box-arrow-in-down"></i>
                    <span>Deposit Sales Income</span>
                </button>
                <button type="button" class="btn btn-info text-dark fw-bold px-3 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2 hover-lift" onclick="openDepositModal('Other Deposit')">
                    <i class="bi bi-piggy-bank"></i>
                    <span>Other Deposit</span>
                </button>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- TOP SECTION: BANK BALANCES AND BANK CARDS -->
<div class="row mb-4">
    <!-- Total Balance Card -->
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card balance-banner-card shadow h-100 p-4 d-flex flex-column justify-content-between">
            <div>
                <span class="badge bg-white text-primary fw-bold px-3 py-1 rounded-pill small mb-3 shadow-sm">
                    <i class="bi bi-wallet2 me-1"></i> LIQUIDITY OVERVIEW
                </span>
                <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="letter-spacing: 1px;">Total Bank Balance</h6>
                <h2 class="fw-bolder text-white mb-0" style="font-size: 2.3rem;">
                    Rs. {{ number_format($banks->sum('current_balance'), 2) }}
                </h2>
            </div>
            <div class="d-flex justify-content-between align-items-end mt-4 pt-3 border-top" style="border-color: rgba(255,255,255,0.2) !important;">
                <span class="small text-white-50">{{ count($banks) }} Registered Accounts</span>
                <i class="bi bi-bank fs-1 text-white opacity-25"></i>
            </div>
        </div>
    </div>
    
    <!-- Bank Accounts List -->
    <div class="col-md-8">
        <div class="card shadow-sm border-top border-4 border-primary h-100" style="border-radius: 16px;">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent p-4 border-bottom">
                <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-safe text-primary"></i>
                    <span>Bank Accounts</span>
                    <span class="badge bg-primary rounded-pill ms-1">{{ count($banks) }}</span>
                </h5>
                <button type="button" class="btn btn-primary btn-sm fw-bold px-3 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-1 hover-lift" data-bs-toggle="modal" data-bs-target="#addBankModal">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Add Bank Account</span>
                </button>
            </div>
            <div class="card-body p-4" style="max-height: 280px; overflow-y: auto;">
                <div class="row g-3">
                    @forelse($banks as $bank)
                    <div class="col-md-6">
                        <div class="card {{ $bank->is_primary ? 'bank-card-primary' : 'bank-card-normal' }} mb-0 h-100 hover-lift">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-primary">{{ $bank->bank_name }}</h6>
                                        <span class="text-muted small d-block font-monospace">{{ $bank->account_number }}</span>
                                        <small class="text-muted">{{ $bank->account_name ?? 'N/A' }}</small>
                                    </div>
                                    @if($bank->is_primary)
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1 shadow-sm small">
                                            <i class="bi bi-star-fill"></i> Primary
                                        </span>
                                    @endif
                                </div>
                                <div class="my-2">
                                    <span class="text-muted small d-block">Available Balance</span>
                                    <h5 class="mb-0 text-success fw-bolder">Rs. {{ number_format($bank->current_balance, 2) }}</h5>
                                </div>
                                <div class="text-end border-top pt-2 mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" onclick='editBank(@json($bank))'>
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-4 text-muted">
                        <i class="bi bi-bank fs-2 d-block mb-2 opacity-50"></i>
                        <span>No bank accounts registered yet.</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BOTTOM SECTION: EXPENSES REGISTER WITH SEARCH & FILTERS -->
<div class="card shadow-sm border-top border-4 border-danger" style="border-radius: 16px; overflow: hidden;">
    <div class="card-header bg-transparent border-bottom p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-2">
            <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-cash-stack text-danger"></i>
                <span>Operating Expenses Log</span>
                <span class="badge bg-danger rounded-pill ms-1" id="visibleExpenseCount">{{ count($expenses) }}</span>
            </h5>
        </div>
        
        <div class="d-flex flex-wrap align-items-center gap-3">
            <!-- Search Bar -->
            <div class="position-relative" style="min-width: 260px;">
                <i class="bi bi-search position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #8a8d93; z-index: 3;"></i>
                <input type="text" 
                       id="expenseSearchInput"
                       class="form-control search-input-custom rounded-pill form-control-sm" 
                       placeholder="Search Description, Category, Bank..." 
                       autocomplete="off"
                       style="padding-left: 40px; height: 38px;">
                <button type="button" 
                        id="clearExpenseSearchBtn" 
                        class="btn btn-sm btn-secondary position-absolute rounded-pill d-none" 
                        style="right: 6px; top: 50%; transform: translateY(-50%); padding: 2px 8px; font-size: 0.7rem;" 
                        onclick="clearExpenseSearch()">
                    Clear
                </button>
            </div>

            <!-- Add Expense Button -->
            <button type="button" class="btn btn-danger fw-bold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2 hover-lift" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Add New Expense</span>
            </button>
        </div>
    </div>

    <!-- QUICK FILTER PILLS -->
    <div class="px-4 pt-3 pb-2 filter-bar-container border-bottom d-flex flex-wrap align-items-center gap-2">
        <span class="text-muted small fw-bold me-1"><i class="bi bi-funnel me-1"></i> Category:</span>
        <button type="button" class="expense-filter-pill active" data-category="all" onclick="filterExpenses('all', this)">All Expenses</button>
        <button type="button" class="expense-filter-pill" data-category="electricity" onclick="filterExpenses('Electricity', this)">Electricity</button>
        <button type="button" class="expense-filter-pill" data-category="water" onclick="filterExpenses('Water', this)">Water</button>
        <button type="button" class="expense-filter-pill" data-category="rent" onclick="filterExpenses('Rent', this)">Rent</button>
        <button type="button" class="expense-filter-pill" data-category="staff salary" onclick="filterExpenses('Staff Salary', this)">Staff Salary</button>
        <button type="button" class="expense-filter-pill" data-category="repairs" onclick="filterExpenses('Repairs', this)">Repairs</button>
        <button type="button" class="expense-filter-pill" data-category="transport" onclick="filterExpenses('Transport', this)">Transport</button>
        <button type="button" class="expense-filter-pill" data-category="other" onclick="filterExpenses('Other', this)">Other</button>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive table-scrollable px-3 pb-3 pt-3">
            <table class="table table-hover align-middle mb-0" id="expensesTable">
                <thead>
                    <tr>
                        <th class="ps-3" style="min-width: 130px;">Date</th>
                        <th style="min-width: 150px;">Category</th>
                        <th style="min-width: 240px;">Description</th>
                        <th style="min-width: 190px;">Payment Method</th>
                        <th class="text-end pe-3" style="min-width: 150px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr class="expense-row" data-category="{{ strtolower($expense->category) }}">
                        <td class="ps-3">
                            <span class="d-inline-flex align-items-center gap-1 fw-bold text-primary">
                                <i class="bi bi-calendar-event"></i>
                                <span>{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}</span>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light-danger text-danger border border-danger rounded-pill px-3 py-1 fw-semibold">
                                {{ $expense->category }}
                            </span>
                        </td>
                        <td class="fw-semibold">
                            {{ $expense->description ?? '-' }}
                        </td>
                        <td>
                            @if($expense->bank_account_id)
                                <span class="d-inline-flex align-items-center gap-1 fw-bold text-primary">
                                    <i class="bi bi-bank2"></i>
                                    <span>{{ $expense->bankAccount->bank_name ?? 'Bank Account' }}</span>
                                </span>
                            @else
                                <span class="d-inline-flex align-items-center gap-1 fw-bold text-success">
                                    <i class="bi bi-cash-coin"></i>
                                    <span>Petty Cash</span>
                                </span>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-danger pe-3 fs-6">
                            Rs. {{ number_format($expense->amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyExpenseRow">
                        <td colspan="5" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-journal-x fs-1 mb-2 opacity-50"></i>
                                <span class="fw-bold fs-5 text-muted">No expenses recorded yet</span>
                                <small class="text-muted mt-1">Click 'Add New Expense' above to log an operating expense</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    <tr id="noExpenseSearchRow" class="d-none">
                        <td colspan="5" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-search fs-1 mb-2 opacity-50"></i>
                                <span class="fw-bold fs-5 text-muted">No matching expenses found</span>
                                <small class="text-muted mt-1">Try a different search keyword or category pill</small>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ADD BANK ACCOUNT MODAL -->
<div class="modal fade" id="addBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('finance.bank.store') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            @csrf
            <div class="modal-header bg-primary text-white p-4">
                <h5 class="modal-title text-white fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-safe"></i>
                    <span>Add Bank Account</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Bank Name <span class="text-danger">*</span></label>
                    <input type="text" name="bank_name" class="form-control rounded-pill px-3" required placeholder="e.g. BOC, Sampath, Commercial">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Account Name</label>
                    <input type="text" name="account_name" class="form-control rounded-pill px-3" placeholder="e.g. Current Account / Savings">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Account Number <span class="text-danger">*</span></label>
                    <input type="text" name="account_number" class="form-control rounded-pill px-3 font-monospace" required placeholder="e.g. 100020003000">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Opening Balance (Rs)</label>
                    <input type="number" step="0.01" name="current_balance" class="form-control rounded-pill px-3 text-end fw-bold text-success" value="0.00">
                </div>
                <div class="form-check form-switch mt-4 border-top pt-3">
                    <input class="form-check-input fs-5" type="checkbox" name="is_primary" value="1" id="add_is_primary">
                    <label class="form-check-label fw-bold mt-1 ms-2" for="add_is_primary">Set as Primary Account</label>
                </div>
            </div>
            <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Save Account</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT BANK ACCOUNT MODAL -->
<div class="modal fade" id="editBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editBankForm" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            @csrf
            @method('PUT') 
            <div class="modal-header bg-secondary text-white p-4">
                <h5 class="modal-title text-white fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit Bank Account</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Bank Name <span class="text-danger">*</span></label>
                    <input type="text" name="bank_name" id="edit_bank_name" class="form-control rounded-pill px-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Account Name</label>
                    <input type="text" name="account_name" id="edit_account_name" class="form-control rounded-pill px-3">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Account Number <span class="text-danger">*</span></label>
                    <input type="text" name="account_number" id="edit_account_number" class="form-control rounded-pill px-3 font-monospace" required>
                </div>
                <div class="form-check form-switch mt-4 border-top pt-3">
                    <input class="form-check-input fs-5" type="checkbox" name="is_primary" id="edit_is_primary" value="1">
                    <label class="form-check-label fw-bold mt-1 ms-2" for="edit_is_primary">Set as Primary Account</label>
                </div>
            </div>
            <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm">Update Details</button>
            </div>
        </form>
    </div>
</div>

<!-- RECORD EXPENSE MODAL -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('finance.expense.store') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            @csrf
            } else {
                row.style.display = 'none';
            }
        });

        const badgeCount = document.getElementById('visibleExpenseCount');
        if (badgeCount) {
            badgeCount.innerText = visibleCount;
        }

        const noResultRow = document.getElementById('noExpenseSearchRow');
        const emptyRow = document.getElementById('emptyExpenseRow');
        if (noResultRow) {
            if (visibleCount === 0 && rows.length > 0) {
                noResultRow.classList.remove('d-none');
            } else {
                noResultRow.classList.add('d-none');
            }
        }
        if (emptyRow && rows.length > 0) {
            emptyRow.style.display = 'none';
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('expenseSearchInput');
        const clearBtn = document.getElementById('clearExpenseSearchBtn');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.trim();
                if (term.length > 0) {
                    clearBtn.classList.remove('d-none');
                } else {
                    clearBtn.classList.add('d-none');
                }
                applyExpenseFilters();
            });
        }
    });

    function clearExpenseSearch() {
        const searchInput = document.getElementById('expenseSearchInput');
        const clearBtn = document.getElementById('clearExpenseSearchBtn');

        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        }
        if (clearBtn) {
            clearBtn.classList.add('d-none');
        }
        applyExpenseFilters();
    }
</script>
@endsection
