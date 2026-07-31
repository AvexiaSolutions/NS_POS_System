@extends('layouts.admin')

@section('title', 'Manage Suppliers')
@section('header', 'Suppliers List')

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
    [data-bs-theme="dark"] .card {
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
        max-height: 72vh;
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
</style>

<div class="page-heading">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-5 order-md-1 order-last mt-3 mt-md-0">
            <h4 class="mb-1 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-truck text-primary"></i>
                <span>Supplier Management</span>
            </h4>
            <p class="text-subtitle text-muted mb-0">Manage supplier companies, contact persons & outstanding credit balances.</p>
        </div>
        <div class="col-12 col-md-4 order-md-2 order-2 mt-3 mt-md-0">
            <div class="position-relative mx-auto" style="max-width: 400px;">
                <i class="bi bi-search position-absolute" style="left: 16px; top: 50%; transform: translateY(-50%); color: #8a8d93; z-index: 3;"></i>
                <input type="text" 
                       id="supplierSearchInput"
                       class="form-control search-input-custom rounded-pill" 
                       placeholder="Search Company, Contact, Phone..." 
                       autocomplete="off"
                       style="padding-left: 45px; height: 42px;">
                <button type="button" 
                        id="clearSearchBtn" 
                        class="btn btn-sm btn-secondary position-absolute rounded-pill d-none" 
                        style="right: 6px; top: 50%; transform: translateY(-50%); padding: 3px 10px; font-size: 0.75rem;" 
                        onclick="clearSupplierSearch()">
                    Clear
                </button>
            </div>
        </div>
        <div class="col-12 col-md-3 order-md-3 order-first">
            <div class="float-start float-md-end">
                <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-2 hover-lift" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Add New Supplier</span>
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

<div class="card shadow-sm border-top border-4 border-primary" style="border-radius: 14px; overflow: hidden;">
    <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
            <i class="bi bi-buildings text-primary"></i>
            <span>Registered Suppliers</span>
            <span class="badge bg-primary rounded-pill ms-1">{{ count($suppliers) }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive table-scrollable px-3 pb-3 pt-3">
            <table class="table table-hover align-middle mb-0" id="suppliersTable">
                <thead>
                    <tr>
                        <th class="ps-3" style="min-width: 220px;">Company Name</th>
                        <th style="min-width: 170px;">Contact Person</th>
                        <th style="min-width: 180px;">Contact Info</th>
                        <th style="min-width: 150px;">Branch</th>
                        <th style="min-width: 140px;">Credit Balance</th>
                        <th class="text-end pe-3" style="min-width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr class="supplier-row">
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar bg-light-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="bi bi-building text-primary fs-5"></i>
                                </div>
                                <div>
                                    <span class="fw-bold fs-6 d-block">{{ $supplier->company_name }}</span>
                                    @if($supplier->address)
                                        <small class="text-muted d-block text-truncate" style="max-width: 220px;" title="{{ $supplier->address }}">
                                            <i class="bi bi-geo-alt"></i> {{ $supplier->address }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($supplier->contact_person)
                                <span class="d-inline-flex align-items-center gap-1 fw-semibold">
                                    <i class="bi bi-person-badge text-muted"></i>
                                    <span>{{ $supplier->contact_person }}</span>
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                @if($supplier->phone)
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-telephone text-primary"></i>
                                        <span class="fw-medium">{{ $supplier->phone }}</span>
                                    </span>
                                @endif
                                @if($supplier->email)
                                    <span class="d-inline-flex align-items-center gap-2 text-muted small">
                                        <i class="bi bi-envelope"></i>
                                        <span>{{ $supplier->email }}</span>
                                    </span>
                                @endif
                                @if(!$supplier->phone && !$supplier->email)
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($supplier->branch)
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 shadow-sm">
                                    <i class="bi bi-shop"></i>
                                    <span>{{ $supplier->branch->name }}</span>
                                </span>
                            @else
                                <span class="badge bg-info text-dark rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 shadow-sm">
                                    <i class="bi bi-globe"></i>
                                    <span>All Branches</span>
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($supplier->credit_balance > 0)
                                <span class="badge bg-danger rounded-pill px-3 py-2 fs-6 shadow-sm">
                                    Rs. {{ number_format($supplier->credit_balance, 2) }}
                                </span>
                            @else
                                <span class="badge bg-success rounded-pill px-3 py-2 fs-6 shadow-sm">
                                    Rs. 0.00
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 hover-lift" data-bs-toggle="modal" data-bs-target="#editModal{{ $supplier->id }}">
                                <i class="bi bi-pencil-square"></i>
                                <span>Edit</span>
                            </button>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 hover-lift">
                                    <i class="bi bi-trash"></i>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-truck fs-1 mb-2 opacity-50"></i>
                                <span class="fw-bold fs-5 text-muted">No suppliers registered yet</span>
                                <small class="text-muted mt-1">Click 'Add New Supplier' to get started</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ADD SUPPLIER MODAL -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            <div class="modal-header bg-primary text-white p-4">
                <h5 class="modal-title text-white fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-building-add"></i>
                    <span>Add New Supplier</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control rounded-pill px-3" required placeholder="e.g. ACL Cables PLC">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control rounded-pill px-3" placeholder="Name of supplier agent">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-pill px-3" placeholder="07x xxxxxxx">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-pill px-3" placeholder="supplier@company.com">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Assign to Branch (Optional)</label>
                        <select name="branch_id" class="form-select rounded-pill px-3">
                            <option value="">-- All Branches --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control rounded-4 p-3" rows="2" placeholder="Street Address, City"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT SUPPLIER MODALS -->
@foreach($suppliers as $supplier)
<div class="modal fade" id="editModal{{ $supplier->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0" style="border-radius: 16px;">
            <div class="modal-header bg-primary text-white p-4">
                <h5 class="modal-title text-white fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit Supplier Details</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control rounded-pill px-3" value="{{ $supplier->company_name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control rounded-pill px-3" value="{{ $supplier->contact_person }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-pill px-3" value="{{ $supplier->phone }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-pill px-3" value="{{ $supplier->email }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Assign to Branch (Optional)</label>
                        <select name="branch_id" class="form-select rounded-pill px-3">
                            <option value="">-- All Branches --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $supplier->branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control rounded-4 p-3" rows="2">{{ $supplier->address }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Update Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('supplierSearchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const rows = document.querySelectorAll('.supplier-row');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                if (term.length > 0) {
                    clearBtn.classList.remove('d-none');
                } else {
                    clearBtn.classList.add('d-none');
                }

                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    if (text.includes(term)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });

    function clearSupplierSearch() {
        const searchInput = document.getElementById('supplierSearchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const rows = document.querySelectorAll('.supplier-row');

        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        }
        if (clearBtn) {
            clearBtn.classList.add('d-none');
        }
        rows.forEach(row => {
            row.style.display = '';
        });
    }
</script>
@endsection
