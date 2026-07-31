@extends('layouts.admin')

@section('title', 'Manage Items - Products, Categories & Brands')
@section('header', 'Manage Items & Catalog')

@section('content')

<style>
    /* ==========================================================================
       EMPIRE POS OBSIDIAN CARBON DARK MODE AESTHETICS (Matches pos/index.blade.php 100%)
       ========================================================================== */
    [data-bs-theme="dark"] .card {
        background-color: #111318 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #fff !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4) !important;
    }
    [data-bs-theme="dark"] .modal-content {
        background-color: #111318 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #fff !important;
        border-radius: 14px !important;
    }
    [data-bs-theme="dark"] .form-control, 
    [data-bs-theme="dark"] .form-select,
    [data-bs-theme="dark"] .input-group-text {
        background-color: #161920 !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
    }
    [data-bs-theme="dark"] .form-control:focus, 
    [data-bs-theme="dark"] .form-select:focus {
        background-color: #1D212A !important;
        border-color: #007CEF !important;
        color: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.25) !important;
    }
    [data-bs-theme="dark"] .form-control[readonly] {
        background-color: #111318 !important;
        color: #94a3b8 !important;
        cursor: not-allowed;
    }
    [data-bs-theme="dark"] ::placeholder { color: #64748b !important; }

    #sidebar {
        z-index: 1050 !important; 
    }
    @media (max-width: 991px) {
        .sidebar-wrapper { z-index: 1060 !important; }
    }

    .table-scrollable {
        max-height: 65vh; 
        overflow-y: auto;
    }
    
    .table-scrollable thead th {
        position: sticky;
        top: 0;
        z-index: 1; 
        background-color: #f8fafc; 
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }
    
    [data-bs-theme="dark"] .table-scrollable thead th {
        background-color: #161920 !important;
        color: #ffffff !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    }

    .search-results {
        position: absolute;
        background: var(--bs-body-bg);
        border: 1px solid #007CEF;
        max-height: 200px;
        overflow-y: auto;
        width: 95%; 
        z-index: 9999; 
        list-style: none;
        padding: 0;
        margin-top: 5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        display: none;
    }
    [data-bs-theme="dark"] .search-results { background: #161920; border-color: rgba(255, 255, 255, 0.15); }

    .search-results li { padding: 10px; cursor: pointer; border-bottom: 1px solid #ddd; }
    [data-bs-theme="dark"] .search-results li { border-bottom: 1px solid rgba(255, 255, 255, 0.08); color: #fff; }
    .search-results li:hover { background-color: #007CEF; color: white; }
    
    .variant-card { background-color: transparent; border: 1px dashed #007CEF; }

    /* Light Mode Search Bar Border (Darker & Highly Visible) */
    #mainSearchInput {
        border: 2px solid #64748b !important;
        background-color: #f8fafc !important;
        color: #0f172a !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
        transition: all 0.25s ease;
    }
    #mainSearchInput:focus {
        border-color: #007CEF !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.18) !important;
    }
    [data-bs-theme="dark"] #mainSearchInput {
        background-color: #161920 !important;
        border: 2px solid rgba(255, 255, 255, 0.22) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important;
    }
    [data-bs-theme="dark"] #mainSearchInput:focus {
        background-color: #1D212A !important;
        border-color: #007CEF !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.25) !important;
    }

    /* Modern Tabs Styling */
    .nav-pills .nav-link {
        color: #475569;
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.25s ease;
    }
    [data-bs-theme="dark"] .nav-pills .nav-link {
        color: #e2e8f0 !important;
        background-color: #232834 !important;
        border: 1px solid rgba(255, 255, 255, 0.16) !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
    }
    .nav-pills .nav-link:hover {
        background-color: #f1f5f9;
        color: #007CEF;
    }
    [data-bs-theme="dark"] .nav-pills .nav-link:hover {
        background-color: #2c3242 !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
        color: #ffffff !important;
    }
    .nav-pills .nav-link.active,
    [data-bs-theme="dark"] .nav-pills .nav-link.active {
        background-color: #007CEF !important;
        color: #ffffff !important;
        border-color: #007CEF !important;
        box-shadow: 0 4px 14px rgba(0, 124, 239, 0.45) !important;
    }
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15) !important;
    }
</style>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
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

<div class="card shadow-sm border-top border-4 border-primary" style="border-radius: 14px;">
    <div class="card-header bg-transparent border-bottom p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <ul class="nav nav-pills gap-2" id="inventoryTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active fw-bold px-4 py-2 rounded-pill d-inline-flex align-items-center gap-2 shadow-sm" id="products-tab" data-bs-toggle="tab" href="#products-pane" role="tab" onclick="setTab('products')">
                    <i class="bi bi-box-seam-fill"></i>
                    <span>All Products</span>
                    <span class="badge bg-white text-primary ms-1 rounded-pill">{{ count($products) }}</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-bold px-4 py-2 rounded-pill d-inline-flex align-items-center gap-2" id="categories-tab" data-bs-toggle="tab" href="#categories-pane" role="tab" onclick="setTab('categories')">
                    <i class="bi bi-tags-fill"></i>
                    <span>Product Categories</span>
                    <span class="badge bg-light text-dark ms-1 rounded-pill">{{ count($categories) }}</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-bold px-4 py-2 rounded-pill d-inline-flex align-items-center gap-2" id="brands-tab" data-bs-toggle="tab" href="#brands-pane" role="tab" onclick="setTab('brands')">
                    <i class="bi bi-award-fill"></i>
                    <span>Company / Brands</span>
                    <span class="badge bg-light text-dark ms-1 rounded-pill">{{ count($brands) }}</span>
                </a>
            </li>
        </ul>

        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="position-relative" id="productSearchContainer" style="min-width: 280px; max-width: 350px;">
                <form action="{{ route('products.index') }}" method="GET" id="searchForm" onsubmit="return false;">
                    <i class="bi bi-search position-absolute" style="left: 16px; top: 50%; transform: translateY(-50%); color: #8a8d93; z-index: 5;"></i>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search Name, Barcode, Brand..." 
                           value="{{ request('search') }}"
                           id="mainSearchInput"
                           autocomplete="off"
                           style="padding-left: 45px; border-radius: 50px; height: 42px; padding-right: {{ request('search') ? '70px' : '15px' }};">
                    @if(request('search'))
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary position-absolute" style="right: 5px; top: 50%; transform: translateY(-50%); border-radius: 50px; font-size: 0.75rem; padding: 4px 10px;">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-2 hover-lift" id="btn-add-product" onclick="openAddModal()">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Add New Product</span>
            </button>
            <button type="button" class="btn btn-info text-white fw-bold px-4 py-2 rounded-pill shadow-sm d-none align-items-center justify-content-center gap-2 hover-lift" id="btn-add-category" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Add New Category</span>
            </button>
            <button type="button" class="btn btn-info text-white fw-bold px-4 py-2 rounded-pill shadow-sm d-none align-items-center justify-content-center gap-2 hover-lift" id="btn-add-brand" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Add New Brand</span>
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="tab-content" id="inventoryTabsContent">
            <!-- TAB 1: PRODUCTS -->
            <div class="tab-pane fade show active" id="products-pane" role="tabpanel">
                <div class="table-responsive table-scrollable px-3 pb-3 pt-3">
                    <table class="table table-hover align-middle mb-0" id="table1">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Details</th>
                        <th>Price Info</th>
                        <th>Stock</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="fw-bold text-primary">{{ $product->product_name }}</div>
                            <small class="text-muted"><i class="bi bi-upc-scan"></i> {{ $product->barcode }}</small>
                            <br>
                            <span class="badge bg-light-secondary text-dark border">{{ strtoupper($product->unit) }}</span>
                        </td>
                        <td>
                            <small><i class="bi bi-shop"></i> {{ $product->branch->name ?? 'All' }}</small><br>
                            <small><i class="bi bi-tags"></i> {{ $product->category->name ?? '-' }}</small><br>
                            @if($product->has_warranty)
                                <span class="badge bg-info text-dark" style="font-size: 0.7rem"><i class="bi bi-shield-check"></i> {{ $product->warranty_months }}M</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-muted" style="font-size: 0.85rem">Cost: {{ number_format($product->cost_price, 2) }}</div>
                            <div class="fw-bold text-success text-body">Sell: {{ number_format($product->selling_price, 2) }}</div>
                        </td>
                        <td>
                            @if($product->qty <= $product->alert_qty)
                                <span class="badge bg-danger" id="product-stock-{{ $product->id }}">{{ $product->qty }}</span>
                            @else
                                <span class="badge bg-success" id="product-stock-{{ $product->id }}">{{ $product->qty }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if(auth()->user()->role === 'admin')
                                <button class="btn btn-sm btn-outline-info" onclick='openEditModal(@json($product->load("rolls")))' title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="openBarcodeModal({{ json_encode($product) }})" title="Print">
                                    <i class="bi bi-printer"></i>
                                </button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>

                            @elseif(auth()->user()->role === 'cashier')
                                <button class="btn btn-sm btn-success fw-bold" onclick="openAddStockModal({{ json_encode($product) }})" title="Add Stock">
                                    <i class="bi bi-plus-circle me-1"></i> Add Stock
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
                </div>
            </div> <!-- End products-pane -->

            <!-- TAB 2: CATEGORIES -->
            <div class="tab-pane fade" id="categories-pane" role="tabpanel">
                <div class="table-responsive table-scrollable px-3 pb-3 pt-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Shop</th>
                                <th>Category Name</th>
                                <th>Code</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>
                                    <span class="badge bg-primary rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-shop"></i>
                                        <span>{{ $category->branch->name ?? 'N/A' }}</span>
                                    </span>
                                </td>
                                <td class="fw-bold fs-6">{{ $category->name }}</td>
                                <td><span class="badge bg-secondary rounded-pill px-2 py-1">{{ $category->code ?? '-' }}</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-info rounded-pill px-3 d-inline-flex align-items-center gap-1 hover-lift" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 d-inline-flex align-items-center gap-1 hover-lift">
                                            <i class="bi bi-trash"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-5"><div class="d-flex flex-column align-items-center justify-content-center py-3"><i class="bi bi-tags fs-1 mb-2 opacity-50"></i><span class="fw-bold fs-6">No categories found</span></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div> <!-- End categories-pane -->

            <!-- TAB 3: BRANDS -->
            <div class="tab-pane fade" id="brands-pane" role="tabpanel">
                <div class="table-responsive table-scrollable px-3 pb-3 pt-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Shop</th>
                                <th>Company / Brand Name</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                            <tr>
                                <td>
                                    <span class="badge bg-info text-dark rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-shop"></i>
                                        <span>{{ $brand->branch->name ?? 'All' }}</span>
                                    </span>
                                </td>
                                <td class="fw-bold fs-6">{{ $brand->name }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-info rounded-pill px-3 d-inline-flex align-items-center gap-1 hover-lift" data-bs-toggle="modal" data-bs-target="#editBrandModal{{ $brand->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this brand?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 d-inline-flex align-items-center gap-1 hover-lift">
                                            <i class="bi bi-trash"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-5"><div class="d-flex flex-column align-items-center justify-content-center py-3"><i class="bi bi-award fs-1 mb-2 opacity-50"></i><span class="fw-bold fs-6">No brands found</span></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div> <!-- End brands-pane -->
        </div> <!-- End tab-content -->
    </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white"><i class="bi bi-box-seam"></i> Add New Product</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('products.store') }}" method="POST" id="addForm" data-locked="false">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        @if(auth()->user()->role == 'admin')
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Shop / Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" id="branch_id" class="form-select" onchange="filterCategoriesAndBrands(this.value); generateBarcode(this.value);" required>
                                <option value="" disabled selected>Select Shop</option>
                                @foreach($branches as $branch) 
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option> 
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category_id" class="form-select" id="add_category">
                                <option value="">No Category</option>
                                @foreach($categories as $cat) 
                                    <option value="{{ $cat->id }}" data-branch="{{ $cat->branch_id }}">{{ $cat->name }}</option> 
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Brand</label>
                            <select name="brand_id" class="form-select" id="add_brand">
                                <option value="">No Brand</option>
                                @foreach($brands as $brand) 
                                    <option value="{{ $brand->id }}" data-branch="{{ $brand->branch_id }}">{{ $brand->name }}</option> 
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3 position-relative">
                            <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" id="add_product_name" class="form-control" autocomplete="off" required>
                            <ul class="search-results" id="name_search_results"></ul>
                        </div>

                        <div class="col-md-6 mb-3 position-relative">
                            <label class="form-label fw-bold">Barcode</label>
                            <input type="text" name="barcode" id="add_barcode" class="form-control" autocomplete="off">
                            <small class="text-muted" id="barcode_help">Scan or type barcode. Auto-generated if left empty.</small>
                             <ul class="search-results" id="barcode_search_results"></ul>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Cost Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="cost_price" id="add_cost_price" class="form-control" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold text-success">Selling Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="selling_price" id="add_selling_price" class="form-control" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Discount Val</label>
                            <input type="number" step="0.01" name="discount_price" id="add_discount_price" class="form-control" value="0">
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Discount Type</label>
                            <select name="discount_type" class="form-select">
                                <option value="amount">Fixed Amount (Rs)</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Quantity (Stock)</label>
                            <input type="number" step="0.01" name="qty" id="add_qty" class="form-control" value="0">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Unit</label>
                            <select name="unit" id="unitSelect" class="form-select" onchange="updateVariantLabels('add')">
                                <option value="pcs">Pieces (Pcs)</option>
                                <option value="kg">Kilogram (Kg)</option>
                                <option value="gram">Gram (g)</option>
                                <option value="l">Liter (L)</option>
                                <option value="ml">Milliliter (ml)</option>
                                <option value="meter">Meter (Wire)</option>
                                <option value="feet">Feet</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Alert Qty</label>
                            <input type="number" name="alert_qty" class="form-control" value="5">
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="has_warranty" id="warrantyCheck" value="1" onchange="toggleWarranty('add')">
                                <label class="form-check-label fw-bold" for="warrantyCheck">Product Has Warranty?</label>
                            </div>
                            <div class="mt-2" id="warrantyInput" style="display: none;">
                                <label class="fw-bold text-info">Warranty Months</label>
                                <input type="number" name="warranty_months" class="form-control border-info" placeholder="e.g. 12" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="card variant-card"> 
                                <div class="card-body">
                                    <h6 class="card-title text-primary fw-bold" id="variantTitle">Product Variants / Packs / Rolls</h6>
                                    <p class="text-secondary small mb-2" id="variantHelp">Add different sizes like 500ml bottle, 100g pack, 50m roll, etc.</p>
                                    
                                    <table class="table table-bordered mb-0" id="rollsTable">
                                        <thead>
                                            <tr>
                                                <th id="sizeLabel">Size / Length (e.g. 500ml)</th>
                                                <th>Price (Rs)</th>
                                                <th width="50px"><button type="button" class="btn btn-sm btn-success" onclick="addRollRow('rollsTable')"><i class="bi bi-plus"></i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary fw-bold ms-1"><i class="bi bi-save"></i> Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-dark fw-bold"><i class="bi bi-pencil-square"></i> Edit Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Product Name</label>
                            <input type="text" name="product_name" id="edit_product_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Barcode</label>
                            <input type="text" name="barcode" id="edit_barcode" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Cost Price</label>
                            <input type="number" step="0.01" name="cost_price" id="edit_cost_price" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-success">Selling Price</label>
                            <input type="number" step="0.01" name="selling_price" id="edit_selling_price" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-danger">Stock Quantity</label>
                            <input type="number" step="0.01" name="qty" id="edit_qty" class="form-control border-danger">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Discount Val</label>
                            <input type="number" step="0.01" name="discount_price" id="edit_discount_price" class="form-control" value="0">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Discount Type</label>
                            <select name="discount_type" id="edit_discount_type" class="form-select">
                                <option value="amount">Fixed Amount (Rs)</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="has_warranty" id="edit_warrantyCheck" value="1" onchange="toggleWarranty('edit')">
                                <label class="form-check-label fw-bold">Has Warranty?</label>
                            </div>
                            <div class="mt-2" id="edit_warrantyInput" style="display: none;">
                                <label class="fw-bold text-info">Warranty Months</label>
                                <input type="number" name="warranty_months" id="edit_warranty_months" class="form-control border-info" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Unit</label>
                            <select name="unit" id="edit_unitSelect" class="form-select" onchange="updateVariantLabels('edit')">
                                <option value="pcs">Pieces (Pcs)</option>
                                <option value="kg">Kilogram (Kg)</option>
                                <option value="gram">Gram (g)</option>
                                <option value="l">Liter (L)</option>
                                <option value="ml">Milliliter (ml)</option>
                                <option value="meter">Meter (Wire)</option>
                                <option value="feet">Feet</option>
                            </select>
                        </div>

                        <div class="col-12 mt-3" id="editRollSection" style="display: none;">
                            <div class="card variant-card"> 
                                <div class="card-body">
                                    <h6 class="card-title text-primary fw-bold" id="editVariantTitle">Product Variants / Packs / Rolls</h6>
                                    
                                    <table class="table table-bordered mb-0" id="editRollsTable">
                                        <thead>
                                            <tr>
                                                <th id="editSizeLabel">Size / Length (e.g. 500ml)</th>
                                                <th>Price (Rs)</th>
                                                <th width="50px"><button type="button" class="btn btn-sm btn-success" onclick="addRollRow('editRollsTable')"><i class="bi bi-plus"></i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info fw-bold text-dark ms-1">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="barcodeModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title"><i class="bi bi-printer"></i> Print Barcodes</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('products.print_barcode') }}" method="POST" target="_blank">
                @csrf
                <div class="modal-body p-4 text-center">
                    <input type="hidden" name="product_id" id="barcode_product_id">
                    <p id="barcode_product_name" class="fw-bold text-primary mb-3"></p>
                    
                    <label class="fw-bold mb-2">How many labels?</label>
                    <input type="number" name="print_qty" id="barcode_qty" class="form-control text-center fs-5" min="1" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark w-100 fw-bold">Print Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'cashier')
<div class="modal fade" id="addStockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form id="addStockForm" method="POST" class="modal-content border-success">
            @csrf
            @method('PATCH')
            <div class="modal-header bg-success text-white py-2">
                <h6 class="modal-title">Add Stock</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <h6 id="stock_p_name" class="fw-bold text-primary mb-1"></h6>
                <small class="text-muted d-block mb-3">Current Stock: <span id="current_stock_display" class="fw-bold"></span></small>
                
                <label class="form-label small fw-bold">Quantity to Add</label>
                <input type="number" name="added_qty" class="form-control form-control-lg text-center fw-bold text-success" placeholder="0" min="1" required>
            </div>
            <div class="modal-footer p-2">
                <button type="submit" class="btn btn-success w-100 fw-bold">Confirm Update</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- ADD CATEGORY MODAL -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-info border-bottom p-3">
                <h5 class="modal-title text-dark fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-tags-fill"></i>
                    <span>Add New Category</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    @if(auth()->user()->role == 'admin')
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Shop <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select rounded-pill" required>
                            <option value="" disabled selected>Choose a Shop...</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-pill px-3" placeholder="e.g. Electronics, Hardware" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Code</label>
                        <input type="text" name="code" class="form-control rounded-pill px-3" placeholder="e.g. ELEC">
                    </div>
                </div>
                <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info text-dark rounded-pill px-4 fw-bold shadow-sm">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT CATEGORY MODALS -->
@foreach($categories as $category)
<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-info border-bottom p-3">
                <h5 class="modal-title text-dark fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit Category</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    @if(auth()->user()->role == 'admin')
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Shop</label>
                        <select name="branch_id" class="form-select rounded-pill" required>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $category->branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-pill px-3" value="{{ $category->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Code</label>
                        <input type="text" name="code" class="form-control rounded-pill px-3" value="{{ $category->code }}">
                    </div>
                </div>
                <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info text-dark rounded-pill px-4 fw-bold shadow-sm">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- ADD BRAND MODAL -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-warning border-bottom p-3">
                <h5 class="modal-title text-dark fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-award-fill"></i>
                    <span>Add Company / Brand</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('brands.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    @if(auth()->user()->role == 'admin')
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Shop <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select rounded-pill" required>
                            <option value="" disabled selected>Choose a Shop...</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-bold">Company / Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-pill px-3" placeholder="e.g. Sony, Samsung, Hayleys" required>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">Save Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT BRAND MODALS -->
@foreach($brands as $brand)
<div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-warning border-bottom p-3">
                <h5 class="modal-title text-dark fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit Company / Brand</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('brands.update', $brand->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    @if(auth()->user()->role == 'admin')
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Shop</label>
                        <select name="branch_id" class="form-select rounded-pill" required>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $brand->branch_id == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-bold">Company / Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-pill px-3" value="{{ $brand->name }}" required>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">Update Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
    function setTab(tabName) {
        const btnAddProd = document.getElementById('btn-add-product');
        const btnAddCat = document.getElementById('btn-add-category');
        const btnAddBrand = document.getElementById('btn-add-brand');
        const searchContainer = document.getElementById('productSearchContainer');
        
        if (btnAddProd) { btnAddProd.classList.remove('d-inline-flex'); btnAddProd.classList.add('d-none'); }
        if (btnAddCat) { btnAddCat.classList.remove('d-inline-flex'); btnAddCat.classList.add('d-none'); }
        if (btnAddBrand) { btnAddBrand.classList.remove('d-inline-flex'); btnAddBrand.classList.add('d-none'); }
        if (searchContainer) { searchContainer.style.display = (tabName === 'products') ? 'block' : 'none'; }

        if (tabName === 'products' && btnAddProd) {
            btnAddProd.classList.remove('d-none');
            btnAddProd.classList.add('d-inline-flex');
        } else if (tabName === 'categories' && btnAddCat) {
            btnAddCat.classList.remove('d-none');
            btnAddCat.classList.add('d-inline-flex');
        } else if (tabName === 'brands' && btnAddBrand) {
            btnAddBrand.classList.remove('d-none');
            btnAddBrand.classList.add('d-inline-flex');
        }
        
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || window.location.hash.replace('#', '');
        if (activeTab === 'categories') {
            const catTabBtn = document.getElementById('categories-tab');
            if (catTabBtn) {
                const tab = new bootstrap.Tab(catTabBtn);
                tab.show();
                setTab('categories');
            }
        } else if (activeTab === 'brands') {
            const brandTabBtn = document.getElementById('brands-tab');
            if (brandTabBtn) {
                const tab = new bootstrap.Tab(brandTabBtn);
                tab.show();
                setTab('brands');
            }
        } else {
            setTab('products');
        }
    });

    const branchBarcodes = @json($branchNextBarcodes ?? []);
    const userRole = "{{ auth()->user()->role }}";
    const userBranchId = "{{ auth()->user()->branch_id }}";
    const isAdmin = "{{ auth()->user()->role }}" === 'admin';
    const isCashier = "{{ auth()->user()->role }}" === 'cashier';
    const rollUnits = ['meter', 'feet', 'l', 'ml', 'kg', 'gram', 'bottle'];

    function openAddStockModal(product) {
        document.getElementById('addStockForm').action = "/products/" + product.id + "/add-stock";
        document.getElementById('stock_p_name').innerText = product.product_name;
        document.getElementById('current_stock_display').innerText = product.qty + ' ' + (product.unit ? product.unit.toUpperCase() : '');
        
        var myModal = new bootstrap.Modal(document.getElementById('addStockModal'));
        myModal.show();
    }

    function updateProductsLive() {
        let query = document.getElementById('mainSearchInput').value;
        if(query.trim() !== '') return; 

        fetch("{{ route('products.index') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(!data.products) return;

            data.products.forEach(p => {
                let badge = document.getElementById('product-stock-' + p.id);
                if(badge) {
                    if(badge.innerText != p.qty) {
                        badge.innerText = p.qty;
                        if(p.qty <= p.alert_qty) {
                            badge.className = 'badge bg-danger';
                        } else {
                            badge.className = 'badge bg-success';
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Product Live Update Error:', error));
    }

    setInterval(updateProductsLive, 10000);

    document.addEventListener("DOMContentLoaded", function() {
        
        const mainSearchInput = document.getElementById('mainSearchInput');
        const productTableBody = document.getElementById('productTableBody');

        if (mainSearchInput) {
            mainSearchInput.addEventListener('input', function() {
                let query = this.value;
                
                fetch(`{{ route('products.index') }}?search=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    productTableBody.innerHTML = ''; 

                    if (data.products.length === 0) {
                        productTableBody.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-muted">No products found.</td></tr>';
                        return;
                    }

                    data.products.forEach(product => {
                        let costPrice = parseFloat(product.cost_price).toLocaleString('en-US', {minimumFractionDigits: 2});
                        let sellingPrice = parseFloat(product.selling_price).toLocaleString('en-US', {minimumFractionDigits: 2});
                        let unit = product.unit ? product.unit.toUpperCase() : '';
                        let branchName = product.branch ? product.branch.name : 'All';
                        let categoryName = product.category ? product.category.name : '-';
                        
                        let warrantyBadge = product.has_warranty 
                            ? `<span class="badge bg-info text-dark" style="font-size: 0.7rem"><i class="bi bi-shield-check"></i> ${product.warranty_months}M</span>` 
                            : '';
                        
                        let qtyClass = product.qty <= product.alert_qty ? 'bg-danger' : 'bg-success';
                        let qtyBadge = `<span class="badge ${qtyClass}" id="product-stock-${product.id}">${product.qty}</span>`;

                        let productJson = JSON.stringify(product).replace(/'/g, "&apos;").replace(/"/g, '&quot;');

                        let actionButtons = '';
                        if (isAdmin) {
                            actionButtons = `
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-info" onclick="openEditModal(${productJson})" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="openBarcodeModal(${productJson})" title="Print">
                                        <i class="bi bi-printer"></i>
                                    </button>
                                    <form action="/products/${product.id}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>`;
                        } else if (isCashier) {
                            actionButtons = `
                                <td class="text-end">
                                    <button class="btn btn-sm btn-success fw-bold" onclick='openAddStockModal(${productJson})' title="Add Stock">
                                        <i class="bi bi-plus-circle me-1"></i> Add Stock
                                    </button>
                                </td>`;
                        } else {
                            actionButtons = `<td></td>`;
                        }

                        let tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <div class="fw-bold text-primary">${product.product_name}</div>
                                <small class="text-muted"><i class="bi bi-upc-scan"></i> ${product.barcode || ''}</small>
                                <br>
                                <span class="badge bg-light-secondary text-dark border">${unit}</span>
                            </td>
                            <td>
                                <small><i class="bi bi-shop"></i> ${branchName}</small><br>
                                <small><i class="bi bi-tags"></i> ${categoryName}</small><br>
                                ${warrantyBadge}
                            </td>
                            <td>
                                <div class="text-muted" style="font-size: 0.85rem">Cost: ${costPrice}</div>
                                <div class="fw-bold text-success text-body">Sell: ${sellingPrice}</div>
                            </td>
                            <td>
                                ${qtyBadge}
                            </td>
                            ${actionButtons}
                        `;
                        productTableBody.appendChild(tr);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
            });
        }

        const nameInput = document.getElementById('add_product_name');
        const barcodeInput = document.getElementById('add_barcode');
        const nameResults = document.getElementById('name_search_results');
        const barcodeResults = document.getElementById('barcode_search_results');

        function setupSearch(input, resultBox) {
            if (!input) return;

            input.addEventListener('input', function() {
                let query = this.value;
                
                if(query.length < 2) {
                    resultBox.style.display = 'none';
                    return;
                }

                fetch("{{ route('products.search_ajax') }}?query=" + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        resultBox.innerHTML = '';
                        if(data.length > 0) {
                            resultBox.style.display = 'block';
                            data.forEach(item => {
                                let li = document.createElement('li');
                                li.innerHTML = `<strong>${item.product_name}</strong> <small>(${item.barcode || ''})</small>`;
                                li.onclick = function() {
                                    fillAndLock(item);
                                    resultBox.style.display = 'none';
                                };
                                resultBox.appendChild(li);
                            });
                        } else {
                            resultBox.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            });
        }

        setupSearch(nameInput, nameResults);
        setupSearch(barcodeInput, barcodeResults);

        document.addEventListener('click', function(e) {
            if (e.target !== nameInput && nameResults) nameResults.style.display = 'none';
            if (e.target !== barcodeInput && barcodeResults) barcodeResults.style.display = 'none';
        });

        [nameInput, barcodeInput].forEach(input => {
            input.addEventListener('input', function() {
                if (document.getElementById('addForm').getAttribute('data-locked') === 'true') {
                    unlockForm(); 
                }
            });
        });

        if (userRole !== 'admin') {
            filterCategoriesAndBrands(userBranchId);
            generateBarcode(userBranchId); 
        } else {
            filterCategoriesAndBrands('');
        }
    });

    function generateBarcode(branchId) {
        let barcodeInput = document.getElementById('add_barcode');
        let helpText = document.getElementById('barcode_help');
        
        if (document.getElementById('addForm').getAttribute('data-locked') !== 'true') {
            if (branchId && branchBarcodes[branchId]) {
                barcodeInput.value = branchBarcodes[branchId];
                helpText.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Auto-Generated for Branch</span>';
            } else {
                barcodeInput.value = '';
                helpText.innerText = 'Scan or type barcode. Auto-generated if left empty.';
            }
        }
    }

    function filterCategoriesAndBrands(branchId) {
        let cats = document.querySelectorAll('#add_category option[data-branch]');
        cats.forEach(opt => {
            if(opt.dataset.branch == branchId || !branchId && branchId !== '') {
                opt.hidden = false; opt.disabled = false;
            } else {
                opt.hidden = true; opt.disabled = true;
            }
        });
        document.getElementById('add_category').value = '';

        let brands = document.querySelectorAll('#add_brand option[data-branch]');
        brands.forEach(opt => {
            if(opt.dataset.branch == branchId || !branchId && branchId !== '') {
                opt.hidden = false; opt.disabled = false;
            } else {
                opt.hidden = true; opt.disabled = true;
            }
        });
        document.getElementById('add_brand').value = '';
    }

    function fillAndLock(product) {
        document.getElementById('add_product_name').value = product.product_name;
        document.getElementById('add_barcode').value = product.barcode || '';
        document.getElementById('add_cost_price').value = product.cost_price;
        document.getElementById('add_selling_price').value = product.selling_price;
        document.getElementById('add_discount_price').value = product.discount_price || 0;
        
        if(product.category_id) document.getElementById('add_category').value = product.category_id;
        if(product.brand_id) document.getElementById('add_brand').value = product.brand_id;
        if(product.unit) {
            document.getElementById('unitSelect').value = product.unit;
            updateVariantLabels('add');
        }

        document.getElementById('add_cost_price').readOnly = true;
        document.getElementById('add_selling_price').readOnly = true;
        document.getElementById('add_discount_price').readOnly = true;
        
        ['add_category', 'add_brand', 'unitSelect'].forEach(id => {
            let el = document.getElementById(id);
            if(el) { el.style.pointerEvents = 'none'; el.style.opacity = '0.6'; }
        });

        document.getElementById('addForm').setAttribute('data-locked', 'true');
        
        document.getElementById('barcode_help').innerHTML = '<span class="text-warning"><i class="bi bi-info-circle"></i> Existing Product Selected. Add stock qty. (Edit name/barcode to add as new)</span>';
        
        document.getElementById('add_qty').value = '';
        document.getElementById('add_qty').focus();
    }

    function unlockForm() {
        document.getElementById('add_cost_price').readOnly = false;
        document.getElementById('add_selling_price').readOnly = false;
        document.getElementById('add_discount_price').readOnly = false;
        
        ['add_category', 'add_brand', 'unitSelect'].forEach(id => {
            let el = document.getElementById(id);
            if(el) { el.style.pointerEvents = 'auto'; el.style.opacity = '1'; }
        });

        if (document.getElementById('addForm').getAttribute('data-locked') === 'true') {
            document.getElementById('add_cost_price').value = '';
            document.getElementById('add_selling_price').value = '';
            document.getElementById('add_discount_price').value = '0';
            document.getElementById('add_qty').value = '0';
            
            document.getElementById('barcode_help').innerText = 'Scan or type barcode. Auto-generated if left empty.';
            document.getElementById('addForm').setAttribute('data-locked', 'false');
        }
    }

    function openAddModal() {
        document.getElementById('addForm').reset();
        document.getElementById('rollsTable').getElementsByTagName('tbody')[0].innerHTML = '';
        document.getElementById('addForm').setAttribute('data-locked', 'true'); 
        unlockForm(); 
        
        let currentBranch = document.getElementById('branch_id') ? document.getElementById('branch_id').value : userBranchId;
        generateBarcode(currentBranch);

        if(document.getElementById('name_search_results')) 
            document.getElementById('name_search_results').style.display = 'none';

        var myModal = new bootstrap.Modal(document.getElementById('addProductModal'));
        myModal.show();
    }

    function openEditModal(product) {
        document.getElementById('editForm').action = "/products/" + product.id;
        document.getElementById('edit_product_name').value = product.product_name;
        document.getElementById('edit_barcode').value = product.barcode;
        document.getElementById('edit_cost_price').value = product.cost_price;
        document.getElementById('edit_selling_price').value = product.selling_price;
        document.getElementById('edit_qty').value = product.qty;

        document.getElementById('edit_discount_price').value = product.discount_price || 0;
        document.getElementById('edit_discount_type').value = product.discount_type || 'amount';

        if(product.unit) {
            document.getElementById('edit_unitSelect').value = product.unit;
        }

        var wCheck = document.getElementById('edit_warrantyCheck');
        var wInput = document.getElementById('edit_warrantyInput');
        var wMonths = document.getElementById('edit_warranty_months');

        if(product.has_warranty == 1) {
            wCheck.checked = true; wInput.style.display = 'block'; wMonths.value = product.warranty_months;
        } else {
            wCheck.checked = false; wInput.style.display = 'none'; wMonths.value = '';
        }

        let editRollSection = document.getElementById('editRollSection');
        let editRollsTbody = document.getElementById('editRollsTable').getElementsByTagName('tbody')[0];
        editRollsTbody.innerHTML = '';

        if (rollUnits.includes(product.unit)) {
            editRollSection.style.display = 'block';
            if (product.rolls && product.rolls.length > 0) {
                product.rolls.forEach(roll => {
                    let row = editRollsTbody.insertRow();
                    row.innerHTML = `
                        <td><input type="text" name="roll_length[]" class="form-control" value="${roll.roll_length}" required></td>
                        <td><input type="number" step="0.01" name="roll_price[]" class="form-control" value="${roll.roll_price}" required></td>
                        <td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="removeRollRow(this)"><i class="bi bi-x-lg"></i></button></td>
                    `;
                });
            }
            updateVariantLabels('edit', product.unit);
        } else {
            editRollSection.style.display = 'none';
        }

        var myModal = new bootstrap.Modal(document.getElementById('editProductModal'));
        myModal.show();
    }

    function openBarcodeModal(product) {
        document.getElementById('barcode_product_id').value = product.id;
        document.getElementById('barcode_product_name').innerText = product.product_name;
        document.getElementById('barcode_qty').value = product.qty;
        
        var myModal = new bootstrap.Modal(document.getElementById('barcodeModal'));
        myModal.show();
    }

    function toggleWarranty(mode) {
        var checkBox = document.getElementById(mode === 'add' ? "warrantyCheck" : "edit_warrantyCheck");
        var inputDiv = document.getElementById(mode === 'add' ? "warrantyInput" : "edit_warrantyInput");
        inputDiv.style.display = checkBox.checked ? "block" : "none";
    }

    function updateVariantLabels(mode, customUnit = null) {
        var unitId = mode === 'add' ? "unitSelect" : "edit_unitSelect";
        var unit = customUnit || document.getElementById(unitId).value;
        var titleId = mode === 'add' ? "variantTitle" : "editVariantTitle";
        var labelId = mode === 'add' ? "sizeLabel" : "editSizeLabel";
        var helpId = "variantHelp";
        
        var title = document.getElementById(titleId);
        var help = document.getElementById(helpId);
        var label = document.getElementById(labelId);
        
        if (mode === 'edit') {
             let editRollSection = document.getElementById('editRollSection');
             if (rollUnits.includes(unit)) {
                 editRollSection.style.display = 'block';
             } else {
                 editRollSection.style.display = 'none';
             }
        }

        if(unit === 'meter' || unit === 'feet') {
            title.innerText = "Wire/Cable Rolls"; 
            if(help && mode === 'add') help.innerText = "Add prices for rolls."; 
            label.innerText = "Roll Length (e.g. 50m)";
        } else if (unit === 'l' || unit === 'ml' || unit === 'bottle') {
            title.innerText = "Bottle Sizes"; 
            if(help && mode === 'add') help.innerText = "Add bottle sizes."; 
            label.innerText = "Bottle Size (e.g. 500ml)";
        } else if (unit === 'kg' || unit === 'gram') {
            title.innerText = "Pack Sizes"; 
            if(help && mode === 'add') help.innerText = "Add pack sizes."; 
            label.innerText = "Pack Weight (e.g. 250g)";
        } else {
            title.innerText = "Variants"; 
            if(help && mode === 'add') help.innerText = "Add variations."; 
            label.innerText = "Variant Name";
        }
    }

    function addRollRow(tableId) {
        var table = document.getElementById(tableId).getElementsByTagName('tbody')[0];
        var row = table.insertRow();
        row.innerHTML = `
            <td><input type="text" name="roll_length[]" class="form-control" placeholder="Size/Length" required></td>
            <td><input type="number" step="0.01" name="roll_price[]" class="form-control" placeholder="Price" required></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="removeRollRow(this)"><i class="bi bi-x-lg"></i></button></td>
        `;
    }
    function removeRollRow(btn) { btn.parentNode.parentNode.remove(); }
</script>
@endsection
