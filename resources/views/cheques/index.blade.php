@extends('layouts.admin')

@section('title', 'Cheque Management')
@section('header', 'Manage Cheques')

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
    }
    [data-bs-theme="dark"] .search-input-custom:focus {
        border-color: #007CEF !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.25) !important;
    }

    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15) !important;
    }

    .table-scrollable {
        max-height: 68vh;
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
        background-color: #161920 !important;
        color: #ffffff !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
    }

    .badge-clickable { 
        cursor: pointer; 
        transition: transform 0.2s ease, opacity 0.2s ease; 
    }
    .badge-clickable:hover { 
        opacity: 0.9; 
        transform: scale(1.05); 
    }

    /* Filter Pills */
    .cheque-filter-pill {
        border-radius: 50rem !important;
        padding: 0.4rem 1.05rem;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .cheque-filter-pill:hover {
        background-color: #f1f5f9;
        border-color: #94a3b8;
        color: #1e293b;
    }
    [data-bs-theme="dark"] .cheque-filter-pill {
        background-color: #232834 !important;
        border: 1px solid rgba(255, 255, 255, 0.16) !important;
        color: #e2e8f0 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
    }
    [data-bs-theme="dark"] .cheque-filter-pill:hover {
        background-color: #2c3242 !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
        color: #ffffff !important;
    }
    .cheque-filter-pill.active,
    [data-bs-theme="dark"] .cheque-filter-pill.active {
        background-color: #007CEF !important;
        border-color: #007CEF !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(0, 124, 239, 0.45) !important;
    }
</style>

<div class="page-heading">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-lg-5 order-lg-1 order-last mt-3 mt-lg-0">
            <h4 class="mb-1 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-bank text-primary"></i>
                <span>Cheque Management</span>
            </h4>
            <p class="text-subtitle text-muted mb-0">Record, track, and realize customer received and supplier issued cheques.</p>
        </div>
        <div class="col-12 col-lg-4 order-lg-2 order-2 mt-3 mt-lg-0">
            <div class="position-relative mx-auto" style="max-width: 400px;">
                <i class="bi bi-search position-absolute" style="left: 16px; top: 50%; transform: translateY(-50%); color: #8a8d93; z-index: 3;"></i>
                <input type="text" 
                       id="chequeSearchInput"
                       class="form-control search-input-custom rounded-pill" 
                       placeholder="Search Cheque No, Bank, Payee/Payer..." 
                       autocomplete="off"
                       style="padding-left: 45px; height: 42px;">
                <button type="button" 
                        id="clearChequeSearchBtn" 
                        class="btn btn-sm btn-secondary position-absolute rounded-pill d-none" 
                        style="right: 6px; top: 50%; transform: translateY(-50%); padding: 3px 10px; font-size: 0.75rem;" 
                        onclick="clearChequeSearch()">
                    Clear
                </button>
            </div>
        </div>
        <div class="col-12 col-lg-3 order-lg-3 order-first">
            <div class="d-flex justify-content-start justify-content-lg-end gap-2">
                <button type="button" class="btn btn-success fw-bold px-3 py-2 rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-2 hover-lift" data-bs-toggle="modal" data-bs-target="#chequeModal" onclick="setType('received')">
                    <i class="bi bi-arrow-down-left-circle-fill"></i>
                    <span>Cheque Received</span>
                </button>
                <button type="button" class="btn btn-danger fw-bold px-3 py-2 rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-2 hover-lift" data-bs-toggle="modal" data-bs-target="#chequeModal" onclick="setType('issued')">
                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                    <span>Cheque Issued</span>
                </button>
            </div>
        </div>
    </div>

    <!-- QUICK FILTER PILLS -->
    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <span class="text-muted small fw-bold me-1"><i class="bi bi-funnel me-1"></i> Filter By:</span>
        <button type="button" class="cheque-filter-pill active" data-filter="all" onclick="filterCheques('all', this)">All Cheques</button>
        <button type="button" class="cheque-filter-pill" data-filter="received" onclick="filterCheques('received', this)">Received (In)</button>
        <button type="button" class="cheque-filter-pill" data-filter="issued" onclick="filterCheques('issued', this)">Issued (Out)</button>
        <button type="button" class="cheque-filter-pill" data-filter="pending" onclick="filterCheques('pending', this)">Pending Only</button>
        <button type="button" class="cheque-filter-pill" data-filter="realized" onclick="filterCheques('realized', this)">Realized Only</button>
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

<div class="card shadow-sm border-top border-4 border-primary" style="border-radius: 14px; overflow: hidden;">
    <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
            <i class="bi bi-card-checklist text-primary"></i>
            <span>Cheques Register</span>
            <span class="badge bg-primary rounded-pill ms-1" id="visibleChequeCount">{{ count($cheques) }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive table-scrollable px-3 pb-3 pt-3">
            <table class="table table-hover align-middle mb-0" id="chequesTable">
                <thead>
                    <tr>
                        <th class="ps-3" style="min-width: 130px;">Due Date</th>
                        <th style="min-width: 130px;">Type</th>
                        <th style="min-width: 190px;">Bank / Acc No</th>
                        <th style="min-width: 140px;">Cheque No</th>
                        <th style="min-width: 200px;">Payee / Payer</th>
                        <th class="text-end" style="min-width: 150px;">Amount</th>
                        <th class="text-center pe-3" style="min-width: 140px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cheques as $cheque)
                    <tr class="cheque-row" 
                        data-type="{{ strtolower($cheque->type) }}" 
                        data-status="{{ strtolower($cheque->status) }}">
                        <td class="ps-3">
                            <span class="d-inline-flex align-items-center gap-1 fw-bold text-primary">
                                <i class="bi bi-calendar-event"></i>
                                <span>{{ \Carbon\Carbon::parse($cheque->cheque_date)->format('Y-m-d') }}</span>
                            </span>
                        </td>
                        <td>
                            @if($cheque->type == 'issued')
                                <span class="badge bg-danger text-white rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 shadow-sm">
                                    <i class="bi bi-arrow-up-right"></i>
                                    <span>ISSUED</span>
                                </span>
                            @else
                                <span class="badge bg-success text-white rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 shadow-sm">
                                    <i class="bi bi-arrow-down-left"></i>
                                    <span>RECEIVED</span>
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar bg-light-secondary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-bank2 text-primary fs-6"></i>
                                </div>
                                <div>
                                    <span class="fw-bold d-block">{{ $cheque->bank_name }}</span>
                                    <small class="text-muted d-block">{{ $cheque->account_no ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light-primary text-primary fw-bold font-monospace px-3 py-2" style="font-size: 0.9rem;">
                                #{{ $cheque->cheque_number }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-semibold d-inline-flex align-items-center gap-1">
                                <i class="bi bi-person-badge text-muted"></i>
                                <span>{{ $cheque->customer_name }}</span>
                            </span>
                        </td>
                        <td class="fw-bold text-end fs-6">
                            Rs. {{ number_format($cheque->amount, 2) }}
                        </td>
                        <td class="text-center pe-3">
                            @if($cheque->status == 'pending')
                                <button type="button"
                                      class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 py-1 d-inline-flex align-items-center justify-content-center gap-1 shadow-sm badge-clickable"
                                      onclick="openRealizeModal('{{ $cheque->id }}', '{{ $cheque->type }}', '{{ $cheque->amount }}')"
                                      title="Click to Realize Cheque">
                                    <i class="bi bi-clock-history"></i>
                                    <span>PENDING</span>
                                </button>
                            @else
                                <span class="badge bg-primary text-white rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1 shadow-sm">
                                    <i class="bi bi-check2-all"></i>
                                    <span>REALIZED</span>
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyChequeRow">
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-card-checklist fs-1 mb-2 opacity-50"></i>
                                <span class="fw-bold fs-5 text-muted">No cheque records found</span>
                                <small class="text-muted mt-1">Use 'Cheque Received' or 'Cheque Issued' above to get started</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    <tr id="noSearchResultRow" class="d-none">
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-search fs-1 mb-2 opacity-50"></i>
                                <span class="fw-bold fs-5 text-muted">No matching cheques found</span>
                                <small class="text-muted mt-1">Try a different search term or filter pill</small>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- RECORD CHEQUE MODAL -->
<div class="modal fade" id="chequeModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('cheques.store') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            @csrf
            <input type="hidden" name="type" id="cheque_type">
            
            <div class="modal-header bg-primary text-white p-4" id="chequeModalHeader">
                <h5 class="modal-title fw-bold text-white d-inline-flex align-items-center gap-2" id="modalTitle">
                    <i class="bi bi-card-checklist"></i>
                    <span>Record Cheque</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Bank Name <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" class="form-control rounded-pill px-3" placeholder="e.g. BOC, Commercial" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Account Number</label>
                        <input type="text" name="account_no" class="form-control rounded-pill px-3" placeholder="Acc number (optional)">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Cheque Number <span class="text-danger">*</span></label>
                        <input type="text" name="cheque_number" class="form-control rounded-pill px-3 font-monospace" placeholder="e.g. 0012345" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Amount (Rs) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control rounded-pill px-3 text-end fw-bold" placeholder="0.00" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Due Date <span class="text-danger">*</span></label>
                        <input type="date" name="cheque_date" class="form-control rounded-pill px-3" required>
                    </div>
                    
                    <div class="col-md-12 mt-4 border-top pt-3">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input fs-5" type="checkbox" name="is_supplier" id="is_supplier" value="1" onchange="toggleSupplier()">
                            <label class="form-check-label fw-bold mt-1 ms-2">Is this a Supplier?</label>
                        </div>
                        
                        <div id="supplier_div" style="display: none;">
                            <label class="form-label small fw-bold">Select Supplier</label>
                            <select name="supplier_id" class="form-select rounded-pill px-3">
                                <option value="">-- Choose Supplier --</option>
                                @foreach($suppliers as $s) 
                                    <option value="{{ $s->id }}">{{ $s->company_name }}</option> 
                                @endforeach
                            </select>
                        </div>
                        
                        <div id="other_div">
                            <label class="form-label small fw-bold">Name (Payer / Payee) <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control rounded-pill px-3" placeholder="Enter full name of payer or payee">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Additional Notes</label>
                        <textarea name="note" class="form-control rounded-4 p-3" rows="2" placeholder="Note or description..."></textarea>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Save Record</button>
            </div>
        </form>
    </div>
</div>

<!-- REALIZE CHEQUE MODAL -->
<div class="modal fade" id="realizeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('cheques.realize') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            @csrf
            <input type="hidden" name="cheque_id" id="realize_cheque_id">
            
            <div class="modal-header bg-primary text-white p-4">
                <h5 class="modal-title fw-bold text-white d-inline-flex align-items-center gap-2">
                    <i class="bi bi-check2-circle"></i>
                    <span>Realize Cheque</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <p id="realizeMsg" class="fs-6 text-muted mb-2"></p>
                    <h3 class="fw-bold text-primary mb-3" id="realizeAmountDisplay"></h3>
                </div>
                
                <div class="text-start">
                    <label class="form-label fw-bold small" id="bankLabel">Select Bank Account</label>
                    <select name="bank_account_id" class="form-select rounded-pill px-3 py-2" required>
                        <option value="">-- Choose Bank Account --</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">
                                {{ $bank->bank_name }} ({{ $bank->account_number }}) — Bal: Rs. {{ number_format($bank->current_balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-2 ps-2" id="bankHelp"></small>
                </div>
            </div>
            
            <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Confirm & Update Balance</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let activeFilter = 'all';

    function setType(type) {
        document.getElementById('cheque_type').value = type;
        let title = document.getElementById('modalTitle');
        let header = document.getElementById('chequeModalHeader');
        
        if(type === 'received') {
            title.innerHTML = '<i class="bi bi-arrow-down-left-circle-fill"></i> Record Received Cheque';
            header.className = 'modal-header bg-success text-white p-4';
        } else {
            title.innerHTML = '<i class="bi bi-arrow-up-right-circle-fill"></i> Record Issued Cheque';
            header.className = 'modal-header bg-danger text-white p-4';
        }
    }

    function toggleSupplier() {
        let isSup = document.getElementById('is_supplier').checked;
        document.getElementById('supplier_div').style.display = isSup ? 'block' : 'none';
        document.getElementById('other_div').style.display = isSup ? 'none' : 'block';
    }

    function openRealizeModal(id, type, amount) {
        document.getElementById('realize_cheque_id').value = id;
        let formattedAmount = parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2 });
        document.getElementById('realizeAmountDisplay').innerText = 'Rs. ' + formattedAmount;
        
        let msg = document.getElementById('realizeMsg');
        let label = document.getElementById('bankLabel');
        let help = document.getElementById('bankHelp');

        if(type === 'received') {
            msg.innerText = "To which bank account should this amount be deposited?";
            label.innerText = "Deposit To Account";
            help.innerText = "The amount will be ADDED to this bank's balance.";
        } else {
            msg.innerText = "From which bank account will this amount be deducted?";
            label.innerText = "Deduct From Account";
            help.innerText = "The amount will be DEDUCTED from this bank's balance.";
        }
        
        new bootstrap.Modal(document.getElementById('realizeModal')).show();
    }

    function filterCheques(filter, btnElement) {
        activeFilter = filter;
        
        // Update active class on pills
        document.querySelectorAll('.cheque-filter-pill').forEach(btn => btn.classList.remove('active'));
        if (btnElement) {
            btnElement.classList.add('active');
        }
        
        applyChequeFilters();
    }

    function applyChequeFilters() {
        const searchInput = document.getElementById('chequeSearchInput');
        const term = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const rows = document.querySelectorAll('.cheque-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const type = row.getAttribute('data-type');
            const status = row.getAttribute('data-status');
            
            let matchesFilter = true;
            if (activeFilter === 'received' && type !== 'received') matchesFilter = false;
            if (activeFilter === 'issued' && type !== 'issued') matchesFilter = false;
            if (activeFilter === 'pending' && status !== 'pending') matchesFilter = false;
            if (activeFilter === 'realized' && status !== 'realized') matchesFilter = false;

            let matchesSearch = term.length === 0 || text.includes(term);

            if (matchesFilter && matchesSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const badgeCount = document.getElementById('visibleChequeCount');
        if (badgeCount) {
            badgeCount.innerText = visibleCount;
        }

        const noResultRow = document.getElementById('noSearchResultRow');
        const emptyRow = document.getElementById('emptyChequeRow');
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
        toggleSupplier();

        const searchInput = document.getElementById('chequeSearchInput');
        const clearBtn = document.getElementById('clearChequeSearchBtn');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.trim();
                if (term.length > 0) {
                    clearBtn.classList.remove('d-none');
                } else {
                    clearBtn.classList.add('d-none');
                }
                applyChequeFilters();
            });
        }
    });

    function clearChequeSearch() {
        const searchInput = document.getElementById('chequeSearchInput');
        const clearBtn = document.getElementById('clearChequeSearchBtn');

        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        }
        if (clearBtn) {
            clearBtn.classList.add('d-none');
        }
        applyChequeFilters();
    }
</script>
@endsection
 