@extends('layouts.admin')

@section('title', 'Returns & Warranty Desk')
@section('header', 'Returns & Warranty Desk')

@section('content')
<style>
    /* ==========================================================================
       EMPIRE HARDWARE POS - RETURNS & WARRANTY TERMINAL UI/UX SYSTEM
       ========================================================================== */

    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
    }

    /* ==========================================================================
       EMPIRE POS OBSIDIAN CARBON DARK MODE AESTHETICS (Matches pos/index.blade.php 100%)
       ========================================================================== */
    [data-bs-theme="dark"] .card,
    [data-bs-theme="dark"] .modal-content {
        background-color: #111318 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #fff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4) !important;
    }
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select,
    [data-bs-theme="dark"] .input-group-text {
        background-color: #161920 !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        color: #fff !important;
    }
    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background-color: #1D212A !important;
        border-color: #007CEF !important;
        color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 124, 239, 0.25) !important;
    }
    [data-bs-theme="dark"] .table {
        color: #e2e2e2;
        border-color: rgba(255, 255, 255, 0.08);
    }
    [data-bs-theme="dark"] .table-hover tbody tr:hover {
        color: #fff;
        background-color: #171A21 !important;
    }
    [data-bs-theme="dark"] .table-light th {
        background-color: #161920 !important;
        color: #fff !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .text-muted {
        color: #94a3b8 !important;
    }
    [data-bs-theme="dark"] .modal-header {
        border-bottom-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] #warrantyDetailsArea {
        background-color: #161920 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] #warrantyDetailsArea span.text-muted {
        color: #94a3b8 !important;
    }
    [data-bs-theme="dark"] #warrantyDetailsArea span.fw-bold {
        color: #fff !important;
    }
    [data-bs-theme="dark"] .reason-chip {
        border-color: rgba(255, 255, 255, 0.12);
        background: #161920;
        color: #cbd5e1;
    }
    [data-bs-theme="dark"] .reason-chip:hover {
        border-color: #007CEF;
        background: rgba(0, 124, 239, 0.2);
        color: #fff;
    }
    [data-bs-theme="dark"] .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    [data-bs-theme="dark"] .badge.bg-light {
        background-color: #161920 !important;
        color: #e2e8f0 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .bg-light {
        background-color: #161920 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    /* Light Mode Tokens */
    .reason-chip {
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
        border: 1px solid #ced4da;
        background: #f8fafc;
        color: #475569;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 50rem;
        padding: 0.35rem 0.85rem;
        display: inline-block;
        margin-right: 0.35rem;
        margin-bottom: 0.35rem;
    }
    [data-bs-theme="dark"] .reason-chip {
        background-color: #232834 !important;
        border: 1px solid rgba(255, 255, 255, 0.16) !important;
        color: #e2e8f0 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
    }
    .reason-chip:hover {
        border-color: #0d6efd;
        background: #eef7ff;
        color: #0d6efd;
        transform: translateY(-1px);
    }
    [data-bs-theme="dark"] .reason-chip:hover {
        background-color: #2c3242 !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
        color: #ffffff !important;
    }

    .qty-control-btn {
        width: 44px;
        height: 44px;
        font-size: 1.25rem;
        font-weight: 700;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes pulseGreen {
        0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.5); }
        70% { box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
        100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
    }
    .warranty-active-pill {
        animation: pulseGreen 2s infinite;
    }

    .search-container {
        max-width: 680px;
        margin: 0 auto;
    }

    /* Light Mode Search Bar Border (Darker & Highly Visible) */
    #invoiceInput {
        border: 2px solid #64748b !important;
        background-color: #f8fafc !important;
        color: #0f172a !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
        transition: all 0.25s ease;
    }
    #invoiceInput:focus {
        border-color: #007CEF !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.18) !important;
    }

    /* Dark Mode Search Bar Border (Obsidian Carbon with Visible Border) */
    [data-bs-theme="dark"] #invoiceInput {
        background-color: #161920 !important;
        border: 2px solid rgba(255, 255, 255, 0.22) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important;
    }
    [data-bs-theme="dark"] #invoiceInput:focus {
        background-color: #1D212A !important;
        border-color: #007CEF !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.25) !important;
    }
</style>

<!-- MAIN INVOICE SEARCH & SCANNER CARD -->
<div class="card shadow-sm mb-4 border-top border-4 border-primary">
    <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
            <h4 class="card-title fw-bold mb-1 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-upc-scan text-primary"></i>
                <span>Scan or Search Invoice</span>
            </h4>
            <p class="text-muted small mb-0">
                Enter the customer's invoice code or scan receipt barcode to process return, damage, or warranty
            </p>
        </div>

        <div class="search-container">
            <div class="d-flex flex-column flex-md-row gap-2 align-items-stretch">
                <div class="position-relative flex-grow-1 d-flex align-items-center">
                    <span class="position-absolute d-flex align-items-center justify-content-center" style="left: 20px; width: 24px; height: 24px; z-index: 5; pointer-events: none;">
                        <i class="bi bi-upc-scan text-primary fs-5"></i>
                    </span>
                    <input type="text" id="invoiceInput" class="form-control form-control-lg shadow-sm fw-bold" placeholder="e.g. INV-EQGOEQAU or scan receipt barcode..." autofocus autocomplete="off" style="padding-left: 56px !important; border-radius: 50px; height: 52px; font-size: 1.05rem;">
                </div>
                <button class="btn btn-primary px-4 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" type="button" onclick="searchBill()" style="border-radius: 50px; height: 52px;">
                    <i class="bi bi-arrow-right-circle-fill fs-5 d-flex align-items-center"></i>
                    <span>Find Invoice</span>
                </button>
            </div>

            <!-- Feature Pills Below Search Bar -->
            <div class="d-flex justify-content-center align-items-center gap-2 gap-md-3 mt-3 flex-wrap small">
                <span class="badge bg-light text-dark border py-2 px-3 rounded-pill d-inline-flex align-items-center gap-1">
                    <i class="bi bi-upc-scan text-primary fs-6"></i>
                    <span>Barcode Scanner Compatible</span>
                </span>
                <span class="badge bg-light text-dark border py-2 px-3 rounded-pill d-inline-flex align-items-center gap-1">
                    <i class="bi bi-arrow-return-left text-primary fs-6"></i>
                    <span>Stock Return & Refund</span>
                </span>
                <span class="badge bg-light text-dark border py-2 px-3 rounded-pill d-inline-flex align-items-center gap-1">
                    <i class="bi bi-shield-check text-info fs-6"></i>
                    <span>Warranty Verification</span>
                </span>
                <span class="badge bg-light text-dark border py-2 px-3 rounded-pill d-inline-flex align-items-center gap-1">
                    <i class="bi bi-exclamation-triangle text-danger fs-6"></i>
                    <span>Damage Logging</span>
                </span>
            </div>
        </div>

        <!-- SEARCH RESULT AREA -->
        <div id="resultArea" class="d-none mt-5 border-top pt-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 bg-light p-3 rounded-3 border">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="badge bg-primary fs-6 py-2 px-3 d-flex align-items-center">
                        <i class="bi bi-receipt me-2"></i><span id="dispInv"></span>
                    </span>
                    <span class="badge bg-secondary fs-6 py-2 px-3 d-flex align-items-center">
                        <i class="bi bi-calendar-check me-2"></i><span id="dispDate"></span>
                    </span>
                    <span id="dispCustomerBadge" class="badge bg-success fs-6 py-2 px-3 d-none">
                        <i class="bi bi-person-fill me-1"></i><span id="dispCustomer"></span>
                    </span>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold d-inline-flex align-items-center gap-1" onclick="resetSearch()">
                        <i class="bi bi-arrow-counterclockwise fs-6"></i>
                        <span>New Search</span>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th>Product & Barcode</th>
                            <th class="text-center">Sold Qty</th>
                            <th class="text-center">Already Returned</th>
                            <th class="text-center">Available to Action</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTable">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ACTION MODAL -->
<div class="modal fade" id="actionModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3" id="modalHeader">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center" id="actionModalTitle">
                    <i class="bi bi-gear-fill me-2"></i> Action
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Selected Product Pill -->
                <div class="bg-light border rounded-3 p-3 text-center mb-4">
                    <span class="small text-muted fw-bold text-uppercase d-block mb-1">Selected Item</span>
                    <h5 id="modalProductName" class="fw-bold mb-1 text-primary"></h5>
                    <span class="badge bg-secondary">Max allowed: <span id="maxQtyDisp" class="fw-bold">0</span></span>
                </div>

                <input type="hidden" id="actionType">
                <input type="hidden" id="actionItemIndex">

                <!-- Quantity Control -->
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase d-block text-center">Quantity to Action</label>
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                        <button type="button" class="btn btn-outline-secondary qty-control-btn" onclick="adjustQty(-1)" title="Decrease Quantity">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" id="actionQty" class="form-control form-control-lg text-center fw-bold" style="max-width: 130px;" min="0.01" step="0.01">
                        <button type="button" class="btn btn-outline-secondary qty-control-btn" onclick="adjustQty(1)" title="Increase Quantity">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    <!-- Quick Qty Presets -->
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setQty('one')">1 Unit</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setQty('half')">Half Qty</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setQty('max')">Max Qty</button>
                    </div>
                </div>

                <!-- Warranty Verification Card -->
                <div id="warrantyDetailsArea" class="d-none bg-light p-3 rounded-3 mb-4 border">
                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                        <span class="small fw-bold text-uppercase text-primary"><i class="bi bi-shield-check me-1"></i> Warranty Timeline</span>
                        <span id="wStatusText"></span>
                    </div>
                    <div class="row g-2 text-center small mt-1">
                        <div class="col-4 border-end">
                            <span class="text-muted d-block">Purchase Date</span>
                            <span id="wPurchaseDate" class="fw-bold"></span>
                        </div>
                        <div class="col-4 border-end">
                            <span class="text-muted d-block">Warranty Period</span>
                            <span id="wPeriod" class="fw-bold text-info"></span>
                        </div>
                        <div class="col-4">
                            <span class="text-muted d-block">Expiry Date</span>
                            <span id="wEndDate" class="fw-bold"></span>
                        </div>
                    </div>
                </div>

                <!-- Reason / Note Field -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-0">Reason / Note</label>
                        <span class="small text-muted" style="font-size:0.75rem;">Click a chip or type</span>
                    </div>
                    <div class="mb-2">
                        <span class="reason-chip" onclick="addReason('Customer Changed Mind')">Customer Changed Mind</span>
                        <span class="reason-chip" onclick="addReason('Defective Product')">Defective Product</span>
                        <span class="reason-chip" onclick="addReason('Damaged in Transit')">Damaged in Transit</span>
                        <span class="reason-chip" onclick="addReason('Wrong Item Sold')">Wrong Item Sold</span>
                        <span class="reason-chip" onclick="addReason('Warranty Replacement')">Warranty Replacement</span>
                    </div>
                    <textarea class="form-control" id="actionReason" rows="2" placeholder="Explain the reason for this action..."></textarea>
                </div>

                <!-- Estimated Refund Card -->
                <div class="card bg-danger-subtle bg-opacity-25 border-danger mb-4" id="refundArea">
                    <div class="card-body text-center py-3">
                        <span class="text-danger small fw-bold text-uppercase d-block">Estimated Customer Refund</span>
                        <h3 class="mb-0 text-danger fw-bold">Rs. <span id="actionRefundAmount">0.00</span></h3>
                    </div>
                </div>

                <button class="btn btn-lg w-100 fw-bold text-white shadow-sm rounded-3 py-3 d-flex align-items-center justify-content-center gap-2" id="confirmActionBtn" onclick="submitAction()">
                    <i class="bi bi-check-circle-fill fs-4 d-flex align-items-center"></i>
                    <span>Confirm Action</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- RETURN & WARRANTY HISTORY SECTION -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-transparent border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3 py-3">
        <h5 class="mb-0 fw-bold d-flex align-items-center">
            <i class="bi bi-clock-history text-primary me-2"></i> Return & Warranty History
        </h5>
        <!-- Quick Date Filter Buttons -->
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setQuickFilter('today')">Today</button>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setQuickFilter('month')">This Month</button>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setQuickFilter('all')">All Time</button>
        </div>
    </div>
    <div class="card-body p-4">
        <form id="historyFilterForm" action="{{ route('returns.index') }}" method="GET" class="row g-3 mb-4 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted fw-bold">Start Date</label>
                <input type="date" id="filterStartDate" name="start_date" class="form-control" value="{{ request('start_date', date('Y-m-01')) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted fw-bold">End Date</label>
                <input type="date" id="filterEndDate" name="end_date" class="form-control" value="{{ request('end_date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-filter fs-5 d-flex align-items-center"></i>
                    <span>Filter History</span>
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>Invoice</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Refund (Rs)</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history ?? [] as $log)
                    <tr>
                        <td class="small text-muted">
                            <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d h:i A') }}
                        </td>
                        <td class="fw-bold text-primary">{{ $log->invoice_no }}</td>
                        <td class="fw-bold">{{ $log->product_name }}</td>
                        <td>
                            @if($log->type == 'return') 
                                <span class="badge bg-primary px-3 py-2">
                                    <i class="bi bi-arrow-return-left me-1"></i> Return
                                </span>
                            @elseif($log->type == 'damage') 
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Damage
                                </span>
                            @elseif($log->type == 'warranty') 
                                <span class="badge bg-info text-dark px-3 py-2">
                                    <i class="bi bi-shield-check me-1"></i> Warranty
                                </span>
                            @endif
                        </td>
                        <td class="text-center fw-bold">{{ $log->qty }}</td>
                        <td class="text-end fw-bold text-danger">{{ number_format($log->refund_amount, 2) }}</td>
                        <td class="small text-muted">{{ $log->reason ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center justify-content-center text-center py-4">
                                <i class="bi bi-inbox fs-1 mb-2 opacity-50 d-inline-block"></i>
                                <span class="fw-bold d-block fs-6">No return or warranty logs found</span>
                                <small class="text-muted mt-1">Select a date range and click Filter History to view previous transactions.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentSaleId = null;
    let currentItems = [];
    let currentSaleData = null; 

    document.addEventListener("DOMContentLoaded", function() {
        const inputField = document.getElementById("invoiceInput");
        if (inputField) {
            inputField.focus();
            inputField.addEventListener("keydown", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault(); 
                    if (this.value.trim() !== '') {
                        searchBill();
                    }
                }
            });
        }

        document.getElementById('actionQty').addEventListener('input', calculateModalRefund);
    });

    function searchBill() {
        let inv = document.getElementById('invoiceInput').value.trim();
        if(!inv) return alert("Please enter invoice code");

        let btn = document.querySelector('button[onclick="searchBill()"]');
        let originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Searching...';
        btn.disabled = true;

        fetch("{{ route('returns.search') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ invoice_no: inv })
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;

            if(data.status === 'error') {
                alert(data.message);
                document.getElementById('resultArea').classList.add('d-none');

                let inputField = document.getElementById('invoiceInput');
                inputField.value = '';
                inputField.focus();
            } else {
                renderItems(data);
            }
        })
        .catch(err => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            console.error(err);
            alert("System Error. Please check console or server connection.");
        });
    }

    function renderItems(data) {
        currentSaleId = data.sale.id;
        currentSaleData = data.sale; 
        
        document.getElementById('dispInv').innerText = data.sale.invoice_no;
        document.getElementById('dispDate').innerText = data.sale_date;

        // Display customer name if available
        let custBadge = document.getElementById('dispCustomerBadge');
        let custEl = document.getElementById('dispCustomer');
        if (data.sale.user && data.sale.user.name) {
            custEl.innerText = data.sale.user.name;
            custBadge.classList.remove('d-none');
        } else if (data.sale.customer_name) {
            custEl.innerText = data.sale.customer_name;
            custBadge.classList.remove('d-none');
        } else {
            custBadge.classList.add('d-none');
        }

        document.getElementById('resultArea').classList.remove('d-none');
        
        let html = '';
        currentItems = data.items;

        data.items.forEach((item, index) => {
            let disabled = (item.available_qty <= 0) ? 'disabled' : '';
            let rowClass = (item.available_qty <= 0) ? 'table-active opacity-50' : '';

            let buttonsHtml = '';
            if (item.available_qty <= 0) {
                buttonsHtml = `<span class="badge bg-secondary py-2 px-3"><i class="bi bi-check2-all me-1"></i> Fully Actioned</span>`;
            } else {
                buttonsHtml = `
                    <div class="d-flex flex-wrap justify-content-center gap-1 action-btn-group">
                        <button class="btn btn-sm btn-primary fw-bold hover-lift d-inline-flex align-items-center gap-1" ${disabled} onclick="openActionModal(${index}, 'return')">
                            <i class="bi bi-arrow-return-left"></i><span>Return</span>
                        </button>
                        <button class="btn btn-sm btn-danger fw-bold hover-lift d-inline-flex align-items-center gap-1" ${disabled} onclick="openActionModal(${index}, 'damage')">
                            <i class="bi bi-exclamation-triangle"></i><span>Damage</span>
                        </button>
                `;

                if(item.has_warranty == 1) {
                    buttonsHtml += `
                        <button class="btn btn-sm btn-info text-dark fw-bold hover-lift d-inline-flex align-items-center gap-1" ${disabled} onclick="openActionModal(${index}, 'warranty')">
                            <i class="bi bi-shield-check"></i><span>Warranty</span>
                        </button>`;
                }
                buttonsHtml += `</div>`;
            }

            let returnedBadge = (item.returned_qty > 0)
                ? `<span class="badge bg-danger px-2 py-1">${parseFloat(item.returned_qty)}</span>`
                : `<span class="text-muted">0</span>`;

            let availableBadge = (item.available_qty > 0)
                ? `<span class="badge bg-success fs-6 px-3 py-1">${parseFloat(item.available_qty)}</span>`
                : `<span class="badge bg-secondary px-2 py-1">0</span>`;

            html += `
            <tr class="${rowClass}">
                <td>
                    <div class="fw-bold text-primary">${item.product_name}</div>
                    <small class="text-muted"><i class="bi bi-upc me-1"></i> ${item.barcode ?? 'N/A'}</small>
                </td>
                <td class="text-center fw-bold">${parseFloat(item.qty)} <span class="small text-muted">${item.unit}</span></td>
                <td class="text-center">${returnedBadge}</td>
                <td class="text-center">${availableBadge}</td>
                <td class="text-end fw-bold">Rs. ${parseFloat(item.price).toFixed(2)}</td>
                <td class="text-center">${buttonsHtml}</td>
            </tr>`;
        });

        document.getElementById('itemsTable').innerHTML = html;
        document.getElementById('invoiceInput').select();
    }

    function openActionModal(index, type) {
        let item = currentItems[index];
        let maxQty = parseFloat(item.available_qty);

        document.getElementById('actionItemIndex').value = index;
        document.getElementById('actionType').value = type;
        document.getElementById('modalProductName').innerText = item.product_name + ' (' + item.unit + ')';
        document.getElementById('actionQty').value = maxQty;
        document.getElementById('actionQty').max = maxQty;
        document.getElementById('maxQtyDisp').innerText = maxQty + ' ' + item.unit;
        document.getElementById('actionReason').value = '';

        let header = document.getElementById('modalHeader');
        let title = document.getElementById('actionModalTitle');
        let btn = document.getElementById('confirmActionBtn');
        let refundArea = document.getElementById('refundArea');
        let warrantyArea = document.getElementById('warrantyDetailsArea');

        warrantyArea.classList.add('d-none');
        header.classList.remove('bg-primary', 'bg-danger', 'bg-info');
        btn.classList.remove('btn-primary', 'btn-danger', 'btn-info');
        btn.disabled = false;

        if (type === 'return') {
            header.classList.add('bg-primary');
            title.innerHTML = '<i class="bi bi-arrow-return-left me-2"></i> Process Return (Add to Stock)';
            btn.classList.add('btn-primary');
            refundArea.style.display = 'block';
        } 
        else if (type === 'damage') {
            header.classList.add('bg-danger');
            title.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Mark as Damaged (No Stock Restock)';
            btn.classList.add('btn-danger');
            refundArea.style.display = 'block';
        } 
        else if (type === 'warranty') {
            header.classList.add('bg-info');
            title.innerHTML = '<i class="bi bi-shield-check me-2"></i> Process Warranty Claim';
            btn.classList.add('btn-info');
            refundArea.style.display = 'none';

            warrantyArea.classList.remove('d-none');
            let saleDateObj = new Date(currentSaleData.created_at);
            document.getElementById('wPurchaseDate').innerText = saleDateObj.toLocaleDateString('en-GB');
            document.getElementById('wPeriod').innerText = item.warranty_months + " Months";
            
            let endDateObj = new Date(saleDateObj);
            endDateObj.setMonth(endDateObj.getMonth() + parseInt(item.warranty_months));
            document.getElementById('wEndDate').innerText = endDateObj.toLocaleDateString('en-GB');

            let now = new Date();
            let statusEl = document.getElementById('wStatusText');
            if(now <= endDateObj) {
                statusEl.innerHTML = '<span class="badge bg-success text-white py-2 px-3 fw-bold warranty-active-pill"><i class="bi bi-check-circle-fill me-1"></i> WARRANTY ACTIVE</span>';
            } else {
                statusEl.innerHTML = '<span class="badge bg-danger text-white py-2 px-3 fw-bold"><i class="bi bi-x-circle-fill me-1"></i> WARRANTY EXPIRED</span>';
                btn.disabled = true;
            }
        }

        calculateModalRefund();
        
        let modalElement = document.getElementById('actionModal');
        let bsModal = new bootstrap.Modal(modalElement);
        bsModal.show();

        modalElement.addEventListener('shown.bs.modal', function () {
            document.getElementById('actionReason').focus();
        }, { once: true });
    }

    function adjustQty(delta) {
        let input = document.getElementById('actionQty');
        let current = parseFloat(input.value) || 0;
        let max = parseFloat(input.max) || 0;
        let next = Math.max(0.01, Math.min(max, current + delta));
        input.value = next;
        calculateModalRefund();
    }

    function setQty(type) {
        let input = document.getElementById('actionQty');
        let max = parseFloat(input.max) || 0;
        if (type === 'one') {
            input.value = Math.min(1, max);
        } else if (type === 'half') {
            input.value = Math.max(1, Math.floor(max / 2));
        } else if (type === 'max') {
            input.value = max;
        }
        calculateModalRefund();
    }

    function addReason(text) {
        let reasonEl = document.getElementById('actionReason');
        if (!reasonEl.value.trim()) {
            reasonEl.value = text;
        } else if (!reasonEl.value.includes(text)) {
            reasonEl.value = reasonEl.value.trim() + " - " + text;
        }
        reasonEl.focus();
    }

    function calculateModalRefund() {
        let index = document.getElementById('actionItemIndex').value;
        let item = currentItems[index];
        let qty = parseFloat(document.getElementById('actionQty').value) || 0;
        let type = document.getElementById('actionType').value;

        if(qty > item.available_qty) {
            document.getElementById('actionQty').value = item.available_qty;
            qty = item.available_qty;
        }

        let refund = 0;
        if(type !== 'warranty') {
            refund = qty * item.price;
        }
        
        document.getElementById('actionRefundAmount').innerText = refund.toFixed(2);
    }

    function submitAction() {
        let index = document.getElementById('actionItemIndex').value;
        let item = currentItems[index];
        let type = document.getElementById('actionType').value;
        let qty = parseFloat(document.getElementById('actionQty').value);
        let reason = document.getElementById('actionReason').value;

        if(!qty || qty <= 0) return alert("Please enter a valid quantity.");
        if(qty > item.available_qty) return alert("Quantity exceeds available limit.");
        if(!reason) return alert("Please provide a reason.");

        let payload = [{
            product_id: item.product_id,
            qty: qty,
            type: type
        }];

        let btn = document.getElementById('confirmActionBtn');
        let originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

        fetch("{{ route('returns.process') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({
                sale_id: currentSaleId,
                invoice_no: currentSaleData.invoice_no,
                items: payload,
                reason: reason
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert("Action completed successfully!");
                location.reload();
            } else {
                alert("Error: " + data.message);
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            console.error(err);
            alert("System Error during processing. Please try again.");
        });
    }

    function resetSearch() {
        document.getElementById('resultArea').classList.add('d-none');
        document.getElementById('itemsTable').innerHTML = '';
        let inputField = document.getElementById('invoiceInput');
        inputField.value = '';
        inputField.focus();
        currentSaleId = null;
        currentItems = [];
        currentSaleData = null;
    }

    function setQuickFilter(period) {
        let startEl = document.getElementById('filterStartDate');
        let endEl = document.getElementById('filterEndDate');
        let now = new Date();

        if (period === 'today') {
            let todayStr = now.toISOString().split('T')[0];
            startEl.value = todayStr;
            endEl.value = todayStr;
        } else if (period === 'month') {
            let year = now.getFullYear();
            let month = String(now.getMonth() + 1).padStart(2, '0');
            startEl.value = `${year}-${month}-01`;
            endEl.value = now.toISOString().split('T')[0];
        } else if (period === 'all') {
            startEl.value = '2020-01-01';
            endEl.value = now.toISOString().split('T')[0];
        }

        document.getElementById('historyFilterForm').submit();
    }
</script>
@endsection

