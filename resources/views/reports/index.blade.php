@extends('layouts.admin')

@section('title', 'Business Reports')
@section('header', 'Comprehensive Business Analysis')

@section('content')
<style>
    :root {
        --empire-blue: #007CEF;
        --empire-dark-bg: #111318;
        --empire-dark-card: #161920;
        --empire-dark-input: #1D212A;
    }

    /* ==========================================================================
       ABSOLUTE LIGHT & DARK MODE OVERRIDES - NEVER RESET PILLS OR PADDING
       ========================================================================== */
    .rounded-pill,
    [data-bs-theme="dark"] .rounded-pill,
    [data-bs-theme="light"] .rounded-pill {
        border-radius: 50rem !important;
    }

    .report-filter-pill,
    [data-bs-theme="dark"] .report-filter-pill,
    [data-bs-theme="light"] .report-filter-pill {
        border-radius: 50rem !important;
        padding: 0.45rem 1.2rem !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.45rem !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        color: #475569 !important;
    }
    .report-filter-pill:hover,
    [data-bs-theme="light"] .report-filter-pill:hover {
        border-color: #94a3b8 !important;
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
    }
    [data-bs-theme="dark"] .report-filter-pill {
        border-color: rgba(255, 255, 255, 0.15) !important;
        background-color: var(--empire-dark-input) !important;
        color: #94a3b8 !important;
    }
    [data-bs-theme="dark"] .report-filter-pill:hover {
        border-color: rgba(255, 255, 255, 0.35) !important;
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #f8fafc !important;
    }
    .report-filter-pill.active,
    [data-bs-theme="dark"] .report-filter-pill.active,
    [data-bs-theme="light"] .report-filter-pill.active {
        background: linear-gradient(135deg, #2A2E39 0%, #161920 100%) !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5) !important;
    }
    [data-bs-theme="light"] .report-filter-pill.active {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
        color: #ffffff !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.25) !important;
    }

    /* Search Input Custom */
    .search-input-custom,
    [data-bs-theme="dark"] .search-input-custom,
    [data-bs-theme="light"] .search-input-custom {
        border-radius: 50rem !important;
        padding-left: 2.75rem !important;
        padding-right: 2rem !important;
        height: 42px !important;
        line-height: 1.5 !important;
        transition: all 0.2s ease !important;
    }
    .search-input-custom {
        border: 2px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        color: #1e293b !important;
    }
    .search-input-custom:focus {
        border-color: #007CEF !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.15) !important;
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

    /* Table Header Styling */
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
        background-color: #1a1e26 !important;
        color: #94a3b8 !important;
        font-weight: 700 !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.12) !important;
        font-size: 0.82rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
    }

    /* Stat Cards Hover Lift */
    .stat-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease !important;
        border-radius: 16px !important;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1) !important;
    }
    [data-bs-theme="dark"] .stat-card:hover {
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.45) !important;
    }

    /* Printable Area adjustments */
    @media print {
        body * { visibility: hidden; }
        #printableArea, #printableArea * { visibility: visible; }
        #printableArea { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print, .btn, .nav-pills, .card-header button, .dataTables_filter, .dataTables_info { display: none !important; }
        .card { border: 1px solid #ddd !important; box-shadow: none !important; margin-bottom: 20px !important; break-inside: avoid; background: white !important; }
        .tab-pane { display: block !important; opacity: 1 !important; visibility: visible !important; margin-bottom: 30px; }
        .table { width: 100% !important; border-collapse: collapse !important; }
        .table th, .table td { border: 1px solid #eee !important; color: black !important; }
        .print-break { page-break-after: always; }
        .progress { border: 1px solid #000; background: #eee !important; }
        .progress-bar { background: #333 !important; }
        body { background: white !important; color: black !important; }
    }
</style>

<!-- PAGE HEADING -->
<div class="page-heading no-print">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-lg-6 order-lg-1 order-last mt-3 mt-lg-0">
            <h4 class="mb-1 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-graph-up-arrow text-primary"></i>
                <span>Business Performance Reports</span>
            </h4>
            <p class="text-subtitle text-muted mb-0">Analyze comprehensive financial, inventory, staff, and return analytics.</p>
        </div>
        <div class="col-12 col-lg-6 order-lg-2 order-first">
            <div class="d-flex flex-wrap justify-content-start justify-content-lg-end gap-2">
                <button type="button" onclick="exportToCSV()" class="btn btn-outline-success fw-bold px-3 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-excel"></i>
                    <span>Export CSV</span>
                </button>
                <button type="button" onclick="exportToPDF()" class="btn btn-outline-danger fw-bold px-3 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf"></i>
                    <span>Export PDF</span>
                </button>
                <button type="button" onclick="window.print()" class="btn btn-dark fw-bold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-printer"></i>
                    <span>Print Report</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- FILTER BAR -->
<div class="row mb-4 no-print">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Start Date</label>
                        <input type="date" name="start_date" class="form-control rounded-pill px-3 fw-bold" value="{{ $startDate ?? '' }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">End Date</label>
                        <input type="date" name="end_date" class="form-control rounded-pill px-3 fw-bold" value="{{ $endDate ?? '' }}" required>
                    </div>
                    <div class="col-md-6 d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-funnel"></i>
                            <span>Filter Date Range</span>
                        </button>
                        <a href="{{ route('reports.index') }}" class="btn btn-light-secondary fw-bold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-arrow-clockwise"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- NAVIGATION PILLS -->
<ul class="nav nav-pills mb-4 no-print gap-2" id="reportTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link report-filter-pill active" data-bs-toggle="pill" data-bs-target="#tab-finance" type="button" role="tab">
            <i class="bi bi-cash-stack"></i>
            <span>Financial & Sales</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link report-filter-pill" data-bs-toggle="pill" data-bs-target="#tab-inventory" type="button" role="tab">
            <i class="bi bi-boxes"></i>
            <span>Inventory & Stock</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link report-filter-pill" data-bs-toggle="pill" data-bs-target="#tab-staff" type="button" role="tab">
            <i class="bi bi-people-fill"></i>
            <span>Staff Performance</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link report-filter-pill" data-bs-toggle="pill" data-bs-target="#tab-returns" type="button" role="tab">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span>Returns</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link report-filter-pill" data-bs-toggle="pill" data-bs-target="#tab-bank" type="button" role="tab">
            <i class="bi bi-bank"></i>
            <span>Cash & Bank</span>
        </button>
    </li>
</ul>

<div id="printableArea">
    
    <div class="row mb-4 print-header d-none d-print-block text-center">
        <h2>Business Performance Report</h2>
        <p class="fw-bold">Period: {{ \Carbon\Carbon::parse($startDate ?? now())->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate ?? now())->format('d M Y') }}</p>
        <hr>
    </div>

    <div class="tab-content" id="reportTabsContent">
        
        <!-- TAB 1: FINANCIAL & SALES OVERVIEW -->
        <div class="tab-pane fade show active" id="tab-finance" role="tabpanel">
            <h4 class="mb-3 d-none d-print-block fw-bold text-decoration-underline">1. Financial & Sales Overview</h4>
            
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm stat-card border-primary border-bottom border-4 h-100 mb-0">
                        <div class="card-body text-center py-4">
                            <h6 class="text-muted fw-bold text-uppercase small">Gross Sales</h6>
                            <h3 class="fw-bold mb-0 text-primary">Rs. {{ number_format($totalSales ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm stat-card border-danger border-bottom border-4 h-100 mb-0">
                        <div class="card-body text-center py-4">
                            <h6 class="text-muted fw-bold text-uppercase small">Cost of Goods (COGS)</h6>
                            <h3 class="fw-bold mb-0 text-danger">Rs. {{ number_format($totalCost ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm stat-card border-warning border-bottom border-4 h-100 mb-0">
                        <div class="card-body text-center py-4">
                            <h6 class="text-muted fw-bold text-uppercase small">Shop Expenses</h6>
                            <h3 class="fw-bold mb-0 text-warning">Rs. {{ number_format($totalExpenses ?? 0, 2) }}</h3>
                            <small class="text-muted" style="font-size: 11px;">(Rent, Bills, Salaries)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm stat-card border-success border-bottom border-4 h-100 mb-0">
                        <div class="card-body text-center py-4">
                            <h6 class="text-success fw-bold text-uppercase small">Net Profit</h6>
                            <h3 class="fw-bold mb-0 text-success">Rs. {{ number_format($netProfit ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-header bg-transparent fw-bold py-3">
                            <i class="bi bi-pie-chart text-primary me-2"></i> Deductions Summary
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                                    <span><i class="bi bi-tags text-warning me-2"></i> Customer Discounts</span>
                                    <span class="fw-bold">Rs. {{ number_format($totalDiscount ?? 0, 2) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                                    <span><i class="bi bi-credit-card text-danger me-2"></i> Bank Charges (3%)</span>
                                    <span class="fw-bold">Rs. {{ number_format($totalBankCharges ?? 0, 2) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                     <div class="card shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-header bg-transparent fw-bold py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <span><i class="bi bi-receipt text-success me-2"></i> Recent Invoices</span>
                            <div class="position-relative no-print" style="min-width: 240px; max-width: 320px;">
                                <i class="bi bi-search position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%); z-index: 5;"></i>
                                <input type="text" 
                                       id="invoiceSearchInput" 
                                       class="form-control search-input-custom shadow-sm" 
                                       placeholder="Search invoice no..." 
                                       onkeyup="filterInvoices()"
                                       autocomplete="off">
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 320px; overflow-y: auto;">
                            <table class="table table-hover mb-0 align-middle" id="invoicesTable">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Inv No</th>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-center no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($salesList ?? [] as $sale)
                                    <tr class="invoice-row">
                                        <td class="fw-bold text-primary">{{ $sale->invoice_no ?? 'N/A' }}</td>
                                        <td>{{ isset($sale->created_at) ? \Carbon\Carbon::parse($sale->created_at)->format('Y-m-d') : 'N/A' }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ ($sale->payment_method ?? '') == 'card' ? 'bg-primary' : 'bg-success' }} px-3 py-1">
                                                {{ strtoupper($sale->payment_method ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold">Rs. {{ number_format($sale->total_amount ?? 0, 2) }}</td>
                                        <td class="text-center no-print">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#viewBillModal-{{ $sale->id }}" title="View Invoice">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button onclick="printBill('{{ route('bill.print', $sale->id) }}')" class="btn btn-sm btn-outline-dark rounded-pill px-2" title="Print Thermal">
                                                <i class="bi bi-printer"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No sales found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                     </div>
                </div>
            </div>
            <div class="print-break"></div>
        </div>

        <!-- TAB 2: INVENTORY & STOCK VALUATION -->
        <div class="tab-pane fade" id="tab-inventory" role="tabpanel">
            <h4 class="mb-3 d-none d-print-block fw-bold text-decoration-underline">2. Inventory & Stock Valuation</h4>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm stat-card border-info border-start border-4 h-100 mb-0">
                        <div class="card-body py-4">
                            <h6 class="text-muted fw-bold text-uppercase small mb-1">Total Stock Value (Cost)</h6>
                            <h3 class="fw-bold mb-0 text-info">Rs. {{ number_format($stockValueCost ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm stat-card border-success border-start border-4 h-100 mb-0">
                        <div class="card-body py-4">
                            <h6 class="text-muted fw-bold text-uppercase small mb-1">Expected Revenue</h6>
                            <h3 class="fw-bold mb-0 text-success">Rs. {{ number_format($stockValueSelling ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm stat-card border-danger border-start border-4 h-100 mb-0">
                        <div class="card-body py-4">
                            <h6 class="text-muted fw-bold text-uppercase small mb-1">Low Stock Items</h6>
                            <h3 class="fw-bold mb-0 text-danger">{{ $lowStockItemsCount ?? 0 }} Items</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-header bg-transparent py-3 border-bottom">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow text-success me-2"></i> Fast Moving Items</h6>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light"><tr><th>Product Name</th><th class="text-center">Qty Sold</th></tr></thead>
                                <tbody>
                                    @forelse($topSellingProducts ?? [] as $item)
                                    <tr>
                                        <td class="fw-bold">{{ $item->product_name ?? 'N/A' }}</td>
                                        <td class="text-center"><span class="badge bg-success rounded-pill px-3 py-1">{{ $item->total_sold ?? 0 }}</span></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2" class="text-center text-muted py-4">No data available</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-header bg-transparent py-3 border-bottom">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle text-danger me-2"></i> Dead / Slow Moving Stock</h6>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light"><tr><th>Product Name</th><th class="text-center">Current Qty</th></tr></thead>
                                <tbody>
                                    @forelse($deadStock ?? [] as $item)
                                    <tr>
                                        <td class="fw-bold">{{ $item->product_name ?? 'N/A' }}</td>
                                        <td class="text-center"><span class="badge bg-danger rounded-pill px-3 py-1">{{ $item->qty ?? 0 }}</span></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2" class="text-center text-muted py-4">No dead stock found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="print-break"></div>
        </div>

        <!-- TAB 3: STAFF PERFORMANCE -->
        <div class="tab-pane fade" id="tab-staff" role="tabpanel">
            <h4 class="mb-3 d-none d-print-block fw-bold text-decoration-underline">3. Staff Performance</h4>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-header bg-transparent py-3 border-bottom">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-person-badge text-primary me-2"></i> Cashier Performance</h6>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-center">Bills Count</th>
                                        <th class="text-end">Revenue Collected</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cashierPerformance ?? [] as $cashier)
                                    <tr>
                                        <td class="fw-bold">{{ $cashier->name ?? 'N/A' }}</td>
                                        <td class="text-center"><span class="badge bg-secondary rounded-pill px-3 py-1">{{ $cashier->total_bills ?? 0 }}</span></td>
                                        <td class="text-end fw-bold text-success">Rs. {{ number_format($cashier->total_collected ?? 0, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">No data available</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                     <div class="card shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-header bg-transparent py-3 border-bottom">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart text-info me-2"></i> Payment Methods Breakdown</h6>
                        </div>
                        <div class="card-body py-4">
                            <div class="mb-4 px-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold"><i class="bi bi-cash text-success me-2"></i> Cash ({{ $cashPercentage ?? 0 }}%)</span>
                                    <b class="text-success fs-6">Rs. {{ number_format($cashTotal ?? 0, 2) }}</b>
                                </div>
                                <div class="progress rounded-pill" style="height: 12px;">
                                    <div class="progress-bar bg-success rounded-pill" style="width: {{ $cashPercentage ?? 0 }}%;"></div>
                                </div>
                            </div>
                            <div class="px-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold"><i class="bi bi-credit-card text-primary me-2"></i> Card ({{ $cardPercentage ?? 0 }}%)</span>
                                    <b class="text-primary fs-6">Rs. {{ number_format($cardTotal ?? 0, 2) }}</b>
                                </div>
                                <div class="progress rounded-pill" style="height: 12px;">
                                    <div class="progress-bar bg-primary rounded-pill" style="width: {{ $cardPercentage ?? 0 }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="print-break"></div>
        </div>

        <!-- TAB 4: RETURNS ANALYSIS -->
        <div class="tab-pane fade" id="tab-returns" role="tabpanel">
            <h4 class="mb-3 d-none d-print-block fw-bold text-decoration-underline">4. Returns Analysis</h4>
            <div class="card shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-return-left text-danger me-2"></i> Returned Items Register</h6>
                    <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">Total Refunds: Rs. {{ number_format($totalReturnValue ?? 0, 2) }}</span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Inv No</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th class="text-end">Refund Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returnsList ?? [] as $return)
                            <tr>
                                <td>{{ isset($return->created_at) ? \Carbon\Carbon::parse($return->created_at)->format('Y-m-d') : 'N/A' }}</td>
                                <td class="fw-bold text-primary">{{ $return->invoice_no ?? 'N/A' }}</td>
                                <td>{{ $return->product_name ?? 'N/A' }}</td>
                                <td><span class="badge bg-info rounded-pill px-3 py-1">{{ strtoupper($return->type ?? 'N/A') }}</span></td>
                                <td class="text-end fw-bold text-danger">Rs. {{ number_format($return->refund_amount ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">No returns recorded for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="print-break"></div>
        </div>

        <!-- TAB 5: CASH & BANK BALANCES -->
        <div class="tab-pane fade" id="tab-bank" role="tabpanel">
            <h4 class="mb-3 d-none d-print-block fw-bold text-decoration-underline">5. Cash & Bank Balances</h4>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm stat-card border-success border-bottom border-4 h-100 mb-0">
                        <div class="card-body text-center py-4">
                            <h6 class="text-success fw-bold text-uppercase small">Current Cash in Hand</h6>
                            <h3 class="fw-bold mb-0 text-success">Rs. {{ number_format($cashInHand ?? 0, 2) }}</h3>
                            <small class="text-muted">(Updated Cash Drawer Total)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                     <div class="card shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-header bg-transparent py-3 border-bottom">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-safe text-primary me-2"></i> Active Bank Accounts</h6>
                        </div>
                        <div class="card-body p-0" style="max-height: 280px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Bank Name</th>
                                        <th>Account Number</th>
                                        <th class="text-end">Current Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($banks ?? [] as $bank)
                                    <tr>
                                        <td class="fw-bold">
                                            {{ $bank->bank_name }}
                                            {!! $bank->is_primary ? '<span class="badge bg-warning text-dark rounded-pill ms-2 small">Primary</span>' : '' !!}
                                        </td>
                                        <td class="font-monospace text-muted">{{ $bank->account_number }}</td>
                                        <td class="text-end fw-bold text-primary fs-5">Rs. {{ number_format($bank->current_balance, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">No bank accounts found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                     </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- INVOICE THERMAL PRINT MODALS -->
@foreach($salesList ?? [] as $sale)
<div class="modal fade" id="viewBillModal-{{ $sale->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Invoice: {{ $sale->invoice_no }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Invoice Date:</span>
                    <b class="fw-bold">{{ $sale->created_at }}</b>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Payment Method:</span>
                    <span class="badge rounded-pill {{ ($sale->payment_method ?? '') == 'card' ? 'bg-primary' : 'bg-success' }}">
                        {{ strtoupper($sale->payment_method ?? 'N/A') }}
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Amount:</span>
                    <b class="text-primary fs-5">Rs. {{ number_format($sale->total_amount, 2) }}</b>
                </div>
                <hr>
                <p class="text-muted small text-center mb-0">Full invoice items breakdown can be viewed on the thermal receipt.</p>
            </div>
            <div class="modal-footer border-top">
                <button onclick="printBill('{{ route('bill.print', $sale->id) }}')" class="btn btn-dark w-100 rounded-pill fw-bold py-2">
                    <i class="bi bi-printer me-2"></i>Print Thermal Receipt
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function printBill(url) {
        let printWindow = window.open(url, '_blank', 'width=800,height=600');
        if (printWindow) {
            printWindow.onload = function() { printWindow.print(); };
        }
    }

    function filterInvoices() {
        const query = (document.getElementById('invoiceSearchInput')?.value || '').toLowerCase().trim();
        const rows = document.querySelectorAll('.invoice-row');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        let hash = window.location.hash;
        if (hash) {
            let tabTrigger = document.querySelector(`.nav-link[data-bs-target="${hash}"]`);
            if (tabTrigger) {
                (new bootstrap.Tab(tabTrigger)).show();
            }
        }
        let form = document.querySelector('form');
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('shown.bs.tab', e => {
                let target = e.target.getAttribute('data-bs-target');
                if (target) {
                    window.location.hash = target;
                    if (form) {
                        form.action = "{{ route('reports.index') }}" + target;
                    }
                }
            });
        });
    });

    function exportToCSV() {
        let activeTab = document.querySelector('.tab-pane.active');
        if (!activeTab) return;
        let tables = activeTab.querySelectorAll('table');
        let csv = [];
        tables.forEach(table => {
            let rows = table.querySelectorAll('tr');
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll('td, th');
                for (let j = 0; j < cols.length; j++) {
                    if (cols[j].classList.contains('no-print')) continue;
                    let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").replace(/"/g, '""').trim();
                    row.push('"' + data + '"');
                }
                csv.push(row.join(','));
            }
            csv.push(""); 
        });
        let csvBlob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
        let link = document.createElement('a');
        link.href = window.URL.createObjectURL(csvBlob);
        link.download = 'Business_Report_' + new Date().getTime() + '.csv';
        link.click();
    }

    function exportToPDF() {
        let allTabs = document.querySelectorAll('.tab-pane');
        allTabs.forEach(tab => {
            tab.classList.add('show', 'active');
            tab.style.display = 'block';
        });

        let element = document.getElementById('printableArea');
        let opt = {
            margin:       [0.3, 0.3, 0.3, 0.3],
            filename:     'Business_Report_' + new Date().toISOString().slice(0, 10) + '.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' },
            pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            allTabs.forEach(tab => {
                tab.classList.remove('show', 'active');
                tab.style.display = '';
            });
            let activeTabHash = window.location.hash || '#tab-finance';
            let activeTab = document.querySelector(activeTabHash);
            if (activeTab) {
                activeTab.classList.add('show', 'active');
            }
        });
    }
</script>
@endsection
