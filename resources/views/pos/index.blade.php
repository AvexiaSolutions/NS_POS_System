@extends('layouts.admin')

@section('title', 'POS Terminal')
@section('header', 'POS Terminal')

@section('content')
<style>
    .sidebar-wrapper { z-index: 9999 !important; }
    
    .pos-wrapper { height: calc(100vh - 100px); display: flex; flex-direction: column; } 
    .pos-container { flex: 1; min-height: 0; display: flex; flex-wrap: nowrap !important; }
    
    .products-col { flex: 1; min-width: 0; display: flex; flex-direction: column; height: 100%; overflow: hidden; }
    .cart-col-wrapper { flex: 0 0 390px; margin-left: 1.25rem; height: 100%; display: flex; flex-direction: column; }
    
    .product-grid-container { flex: 1; overflow-y: auto; padding-right: 5px; min-height: 0; }
    
    .product-grid-container::-webkit-scrollbar { width: 6px; }
    .product-grid-container::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    [data-bs-theme="dark"] .product-grid-container::-webkit-scrollbar-thumb { background-color: #4a4a6a; }

    /* Empire POS Terminal Card & Hover Styles */
    .hover-lift {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }

    .product-card {
        cursor: pointer; 
        transition: transform 0.25s ease, box-shadow 0.25s ease !important;
        border: 1px solid #eef2f7; 
        background-color: #fff; 
        position: relative;
        border-radius: 1rem !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .product-card:hover { 
        transform: translateY(-4px); 
        border-color: #007CEF !important; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important; 
    }
    
    .old-price { text-decoration: line-through; color: #adb5bd; font-size: 0.85rem; margin-right: 5px; }
    .new-price { color: #007CEF; font-weight: 800; font-size: 1.15rem; }

    .cart-items-container,
    .cart-summary-box { 
        background-color: #fff; 
        border: 1px solid #eef2f7;
    }
    .cart-items { flex-grow: 1; overflow-y: auto; padding: 4px; }
    .cart-qty-input { width: 65px; text-align: center; padding: 4px; font-size: 0.95rem; border: 1px solid #ced4da; border-radius: 8px; font-weight: bold; }

    @media (min-width: 768px) {
        .mobile-cart-btn { display: none !important; }
        .mobile-cart-close { display: none !important; }
        .cart-overlay { display: none !important; }
        .cart-items .list-group-item { padding-top: 6px; padding-bottom: 6px; }
    }

    @media (max-width: 767px) {
        .mobile-cart-btn {
            display: flex !important; position: fixed; bottom: 20px; right: 20px;
            z-index: 1050; border-radius: 50px; padding: 15px 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3); animation: bounceIn 0.5s;
        }

        .cart-col-wrapper {
            position: fixed; top: 0; right: -100%; width: 85%; height: 100%;
            z-index: 2000; transition: right 0.3s ease-in-out; padding: 1rem !important;
            box-shadow: -5px 0 15px rgba(0,0,0,0.2); background: #fff;
        }
        [data-bs-theme="dark"] .cart-col-wrapper {
            background: #090A0E !important;
        }

        .cart-col-wrapper.active { right: 0; }
        
        .cart-overlay {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1999;
        }
        .cart-overlay.active { display: block; }
        .mobile-cart-close { display: block !important; }
        .pos-wrapper { height: auto; display: block; }
        .product-grid-container { overflow: visible; padding-bottom: 100px; }
    }

    /* Empire POS Seamless Dark Mode Aesthetics (Premium Obsidian Carbon Palette - Zero Bluish/Purple Cast) */
    [data-bs-theme="dark"] .product-card {
        background-color: #111318 !important; 
        border: 1px solid rgba(255, 255, 255, 0.08) !important; 
        color: #fff !important; 
        border-radius: 14px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4) !important;
    }
    [data-bs-theme="dark"] .product-card:hover { 
        background-color: #171A21 !important;
        border-color: rgba(255, 255, 255, 0.25) !important; 
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.8) !important; 
        transform: translateY(-4px);
    }
    [data-bs-theme="dark"] .cart-items-container,
    [data-bs-theme="dark"] .cart-summary-box,
    [data-bs-theme="dark"] .modal-content {
        background-color: #111318 !important; 
        border: 1px solid rgba(255, 255, 255, 0.08) !important; 
        color: #fff !important; 
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6) !important;
    }
    [data-bs-theme="dark"] .cart-items { background-color: transparent !important; }
    [data-bs-theme="dark"] .cart-col-wrapper { background-color: transparent !important; }
    
    [data-bs-theme="dark"] .cart-col-wrapper { height: 100% !important; display: flex !important; flex-direction: column !important; }
    [data-bs-theme="dark"] .cart-qty-input { background-color: #090A0E !important; border-color: rgba(255, 255, 255, 0.18) !important; color: #fff !important; }
    [data-bs-theme="dark"] .new-price { color: #20c997; }
    [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select, [data-bs-theme="dark"] .input-group-text { 
        background-color: #161920 !important; 
        border: 1px solid rgba(255, 255, 255, 0.12) !important; 
        color: #fff !important; 
    }
    [data-bs-theme="dark"] .form-control:focus, [data-bs-theme="dark"] .form-select:focus { 
        background-color: #1D212A !important; 
        border-color: #007CEF !important; 
        color: #fff !important; 
        box-shadow: 0 0 0 0.25rem rgba(0, 124, 239, 0.25) !important; 
    }
    [data-bs-theme="dark"] .list-group-item { background-color: transparent !important; border-color: rgba(255, 255, 255, 0.08) !important; color: #e2e2e2 !important; }
    [data-bs-theme="dark"] .modal-header { border-bottom-color: rgba(255, 255, 255, 0.08) !important; }
    [data-bs-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

    @keyframes bounceIn { 0% { transform: scale(0); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
</style>

<button class="btn btn-primary mobile-cart-btn align-items-center gap-2" onclick="toggleCart()">
    <i class="bi bi-cart-fill fs-4"></i>
    <div class="text-start lh-1">
        <span class="d-block small opacity-75">Total</span>
        <span class="fw-bold" id="mobileBtnTotal">Rs. 0.00</span>
    </div>
    <span class="badge bg-white text-primary rounded-pill ms-2" id="mobileBtnCount">0</span>
</button>

<!-- Sync Badge for Offline Mode -->
<div id="offlineBadge" class="position-fixed bottom-0 start-0 m-3 d-none" style="z-index: 1050;">
    <button class="btn btn-warning shadow rounded-pill px-4 fw-bold" onclick="syncOfflineSales()">
        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Sync <span id="offlineCount">0</span> Offline Sales
    </button>
</div>

<div class="cart-overlay" onclick="toggleCart()"></div>

<div class="pos-wrapper">
    <div class="pos-container">
        
        <div class="products-col pe-lg-4">
            <!-- Search & Filter Area (Empire POS style) -->
            <div class="mb-4">
                @php $isAdmin = (strtolower(auth()->user()->role) == 'admin'); @endphp
                
                <!-- Barcode / Search Input Row -->
                <div class="mb-3">
                    <div class="position-relative">
                        <i class="bi bi-upc-scan position-absolute text-primary" style="left: 20px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 1.3rem;"></i>
                        <input type="text" class="form-control form-control-lg shadow-sm border-0 fw-bold" id="searchBar" placeholder="Scan Barcode or Search items by name/code... [F2]" onkeyup="filterProducts(event)" autofocus autocomplete="off" style="padding-left: 54px !important; border-radius: 50px; height: 48px; font-size: 1rem;">
                    </div>
                </div>

                <!-- Dropdown Filters Row -->
                <div class="row g-2">
                    @if($isAdmin)
                        <div class="col-md-4">
                            <select class="form-select shadow-sm border-0 rounded-pill" id="branchFilter" onchange="filterProducts()" style="height: 42px;">
                                <option value="all">All Branches</option>
                                @foreach(\App\Models\Branch::all() as $branch) 
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select shadow-sm border-0 rounded-pill" id="catFilter" onchange="filterProducts()" style="height: 42px;">
                                <option value="all">All Categories</option>
                                @foreach($categories as $cat) 
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select shadow-sm border-0 rounded-pill" id="brandFilter" onchange="filterProducts()" style="height: 42px;">
                                <option value="all">All Brands</option>
                                @foreach($brands as $brand) 
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option> 
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="col-md-6">
                            <select class="form-select shadow-sm border-0 rounded-pill" id="catFilter" onchange="filterProducts()" style="height: 42px;">
                                <option value="all">All Categories</option>
                                @foreach($categories as $cat) 
                                    @if($cat->branch_id == auth()->user()->branch_id)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option> 
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select shadow-sm border-0 rounded-pill" id="brandFilter" onchange="filterProducts()" style="height: 42px;">
                                <option value="all">All Brands</option>
                                @foreach($brands as $brand) 
                                    @if($brand->branch_id == auth()->user()->branch_id)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option> 
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            <div class="product-grid-container">
                <div class="row g-3" id="productGrid">
                    @foreach($products as $p)
                        @php
                            if(!$isAdmin && $p->branch_id != auth()->user()->branch_id) { continue; }

                            $hasDiscount = ($p->discount_price > 0);
                            $finalPrice = $p->selling_price;
                            $discountPercent = 0;
                            
                            if($hasDiscount) {
                                if($p->discount_type == 'amount') {
                                    $finalPrice = $p->selling_price - $p->discount_price;
                                    $discountPercent = round(($p->discount_price / $p->selling_price) * 100);
                                } else {
                                    $finalPrice = $p->selling_price - ($p->selling_price * ($p->discount_price / 100));
                                    $discountPercent = $p->discount_price;
                                }
                            }
                        @endphp

                    <div class="col-6 col-md-4 col-lg-4 col-xl-3 product-item" 
                         data-name="{{ strtolower($p->product_name) }}"
                         data-cat="{{ $p->category_id }}"
                         data-brand="{{ $p->brand_id }}"
                         data-barcode="{{ $p->barcode ?? '' }}"
                         data-branch="{{ $p->branch_id }}"
                         onclick="openQtyModal('{{ $p->id }}', {{ $finalPrice }})">
                        
                        <div class="card h-100 product-card mb-0 p-3 hover-lift text-start border-0 shadow-sm rounded-4" style="cursor: pointer;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary-subtle text-secondary fw-bold px-2 py-1" style="font-size: 0.75rem;">{{ $p->barcode ?: 'ITEM-'.$p->id }}</span>
                                @if($hasDiscount)
                                    <span class="badge bg-danger text-white rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $discountPercent }}% OFF</span>
                                @endif
                            </div>

                            <div class="d-flex align-items-start gap-2 mb-2">
                                <div class="bg-body-secondary rounded-3 d-flex align-items-center justify-content-center text-primary shadow-sm flex-shrink-0" style="width: 42px; height: 42px;">
                                    @if(in_array(strtolower($p->unit), ['meter', 'feet'])) <i class="bi bi-bezier2 fs-5"></i> @else <i class="bi bi-box-seam fs-5"></i> @endif
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="card-title text-truncate fw-bold mb-1 lh-sm" title="{{ $p->product_name }}" style="font-size: 0.95rem;">{{ $p->product_name }}</h6>
                                    <span class="badge {{ $p->qty > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill" style="font-size: 0.75rem;">
                                        Stock: <span id="stock-qty-{{ $p->id }}">{{ $p->qty + 0 }}</span> {{ $p->unit }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                <div>
                                    @if($hasDiscount)
                                        <span class="old-price d-block lh-1">Rs. {{ number_format($p->selling_price, 2) }}</span>
                                        <span class="new-price">Rs. {{ number_format($finalPrice, 2) }}</span>
                                    @else
                                        <span class="new-price">Rs. {{ number_format($p->selling_price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="btn btn-sm btn-light text-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0;">
                                    <i class="bi bi-plus-lg fw-bold"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="cart-col-wrapper d-flex flex-column h-100">
            <!-- Top Header Row (Sits cleanly on main background matching Left Pane) -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 fw-bolder text-body">Current Order</h5>
                    <span class="badge bg-primary rounded-pill ms-2" id="cartCount">0</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-danger rounded-pill shadow-sm hover-lift px-3 py-1" onclick="clearCart()" title="Shortcut: F4">
                        <i class="bi bi-trash-fill"></i> Clear [F4]
                    </button>
                    <button class="btn btn-sm btn-light text-primary mobile-cart-close" onclick="toggleCart()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Scrollable Cart Items Container (Sleek card matching Product Cards) -->
            <div class="cart-items-container flex-grow-1 rounded-4 shadow-sm p-3 mb-3 d-flex flex-column overflow-hidden">
                <div class="cart-items flex-grow-1 overflow-auto" id="cartContainer">
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted w-100 py-5">
                        <i class="bi bi-cart-x fs-1 opacity-50 mb-2"></i>
                        <h6 class="fw-bold">Cart is empty</h6>
                        <span class="small">Scan a barcode or select items</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Summary & Checkout Box (Sleek card matching Product Cards) -->
            <div class="cart-summary-box rounded-4 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom border-secondary-subtle">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted fw-semibold small">Subtotal:</span>
                        <span class="fw-bold small text-body" id="subTotal">Rs. 0.00</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted fw-semibold small">Discount (Rs):</span>
                        <input type="number" id="cartDiscount" class="form-control form-control-sm text-end fw-bold shadow-sm py-0 px-2" style="width: 80px; border-radius: 6px; font-size: 0.85rem;" value="0" min="0" onkeyup="calculateTotal()">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="small fw-bold text-muted d-block lh-1" style="font-size: 0.75rem;">Net Total</span>
                        <span class="fs-4 fw-bolder text-primary" id="grandTotal">Rs. 0.00</span>
                    </div>
                    <button class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-pill hover-lift d-flex align-items-center gap-2" onclick="openPaymentModal()" style="font-size: 1rem;">
                        <i class="bi bi-credit-card-fill"></i>
                        <span>PAY & PRINT [F1]</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="qtyModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title fw-bold text-white text-truncate" id="modalTitle" style="max-width: 90%;">Product</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div id="rollOptions" class="d-none mb-3">
                    <div class="btn-group w-100 mb-2">
                        <input type="radio" class="btn-check" name="saleType" id="typeLoose" value="loose" checked onchange="toggleRollUI()">
                        <label class="btn btn-outline-primary btn-sm" for="typeLoose">Loose</label>
                        <input type="radio" class="btn-check" name="saleType" id="typeRoll" value="roll" onchange="toggleRollUI()">
                        <label class="btn btn-outline-primary btn-sm" for="typeRoll">Roll</label>
                    </div>
                    <select class="form-select form-select-sm d-none" id="rollSelect" onchange="updateRollPrice()"></select>
                </div>
                <div class="text-center">
                    <label class="form-label small fw-bold text-muted" id="qtyLabel">Quantity</label>
                    <input type="number" id="modalQty" class="form-control form-control-lg text-center fw-bold text-primary" autofocus autocomplete="off" onkeypress="if(event.key === 'Enter') { event.preventDefault(); addToCart(); }">
                </div>
                <button class="btn btn-primary w-100 mt-3 fw-bold shadow-sm" onclick="addToCart()">Add to Bill</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0 text-center">
                <h6 class="text-muted fw-bold mb-1">Total Payable</h6>
                <h2 class="fw-bold text-primary mb-4" id="modalGrandTotal">Rs. 0.00</h2>
                
                <div class="d-grid gap-3">
                    <button class="btn btn-outline-success payment-btn btn-lg fw-bold rounded-3 py-3" id="btnCash" onclick="processPayment('cash')">
                        <i class="bi bi-cash-stack fs-1 d-block mb-2"></i> CASH PAYMENT
                    </button>
                    <button class="btn btn-outline-info payment-btn btn-lg fw-bold rounded-3 py-3" id="btnCard" onclick="processPayment('card')">
                        <i class="bi bi-credit-card fs-1 d-block mb-2"></i> CARD PAYMENT
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<iframe id="receiptFrame" style="display:none;"></iframe>

@endsection

@section('scripts')
<script>
    let cart = [];
    let selectedProduct = null;
    let currentFinalPrice = 0; 
    let qtyModalInstance = null; 
    
    let products = @json($isAdmin ? $products : $products->where('branch_id', auth()->user()->branch_id)->values());

    function updatePosData() {
        fetch("{{ route('pos.index') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            products = data;
            
            const isAdmin = "{{ auth()->user()->role }}" === 'admin';
            const userBranchId = "{{ auth()->user()->branch_id }}";
            const productGrid = document.getElementById('productGrid');
            
            let html = '';
            
            data.forEach(p => {
                if (!isAdmin && p.branch_id != userBranchId) return;

                const hasDiscount = (parseFloat(p.discount_price) > 0);
                let finalPrice = parseFloat(p.selling_price);
                let discountPercent = 0;
                
                if (hasDiscount) {
                    if (p.discount_type === 'amount') {
                        finalPrice = parseFloat(p.selling_price) - parseFloat(p.discount_price);
                        discountPercent = Math.round((parseFloat(p.discount_price) / parseFloat(p.selling_price)) * 100);
                    } else {
                        finalPrice = parseFloat(p.selling_price) - (parseFloat(p.selling_price) * (parseFloat(p.discount_price) / 100));
                        discountPercent = parseFloat(p.discount_price);
                    }
                }

                let unitIcon = ['meter', 'feet'].includes(p.unit.toLowerCase()) ? '<i class="bi bi-bezier2 fs-2"></i>' : '<i class="bi bi-box-seam fs-2"></i>';
                let discountBadge = hasDiscount ? `<div class="discount-badge">${discountPercent}% OFF</div>` : '';
                let priceSection = hasDiscount 
                    ? `<span class="old-price">Rs. ${parseFloat(p.selling_price).toFixed(2)}</span><span class="new-price">Rs. ${finalPrice.toFixed(2)}</span>`
                    : `<span class="new-price">Rs. ${parseFloat(p.selling_price).toFixed(2)}</span>`;

                html += `
                <div class="col-6 col-md-4 col-lg-4 col-xl-3 product-item" 
                     data-name="${p.product_name.toLowerCase()}"
                     data-cat="${p.category_id}"
                     data-brand="${p.brand_id}"
                     data-barcode="${p.barcode || ''}"
                     data-branch="${p.branch_id}"
                     onclick="openQtyModal('${p.id}', ${finalPrice})">
                    <div class="card h-100 text-center product-card mb-0 p-2">
                        ${discountBadge}
                        <div class="card-body p-1">
                            <div class="mb-2 text-primary">${unitIcon}</div>
                            <h6 class="text-truncate fw-bold mb-1" title="${p.product_name}">${p.product_name}</h6>
                            <div class="price-section">${priceSection}</div>
                            <small class="text-muted d-block mt-1">Stock: <span class="fw-bold" id="stock-qty-${p.id}">${(parseFloat(p.qty) + 0)} ${p.unit}</span></small>
                        </div>
                    </div>
                </div>`;
            });
            
            productGrid.innerHTML = html;
            filterProducts();
        })
        .catch(error => console.error('POS Live Update Error:', error));
    }

    // Removed setInterval(updatePosData, 10000) for performance.

    document.addEventListener('keydown', function(e) {
        let searchBar = document.getElementById('searchBar');
        let isModalOpen = document.body.classList.contains('modal-open');
        
        if(!isModalOpen && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
            if(e.key.length === 1 && !e.ctrlKey && !e.altKey && !e.metaKey) {
                searchBar.focus();
            }
        }
    });

    function toggleCart() {
        if(window.innerWidth >= 992) return; 
        const cartCol = document.querySelector('.cart-col-wrapper');
        const overlay = document.querySelector('.cart-overlay');
        cartCol.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    function openQtyModal(id, finalPrice) {
        selectedProduct = products.find(p => p.id == id);
        currentFinalPrice = finalPrice; 
        if(!selectedProduct) { console.error("Product not found"); return; }
        document.getElementById('modalTitle').innerText = selectedProduct.product_name;
        document.getElementById('modalQty').value = '';
        document.getElementById('typeLoose').checked = true;
        
        let isWire = ['meter', 'feet'].includes(selectedProduct.unit.toLowerCase());
        let rollDiv = document.getElementById('rollOptions');
        let rollSelect = document.getElementById('rollSelect');
        
        if(isWire && selectedProduct.rolls && selectedProduct.rolls.length > 0) {
            rollDiv.classList.remove('d-none');
            rollSelect.innerHTML = '';
            selectedProduct.rolls.forEach(r => {
                let opt = document.createElement('option');
                opt.value = r.roll_length;
                opt.dataset.price = r.roll_price;
                opt.text = `${r.roll_length} ${selectedProduct.unit} - Rs. ${r.roll_price}`;
                rollSelect.appendChild(opt);
            });
        } else {
            rollDiv.classList.add('d-none');
        }
        toggleRollUI();
        
        qtyModalInstance = new bootstrap.Modal(document.getElementById('qtyModal'));
        qtyModalInstance.show();
        
        setTimeout(() => document.getElementById('modalQty').focus(), 500);
    }

    function toggleRollUI() {
        if(!selectedProduct) return;
        let isRoll = document.getElementById('typeRoll').checked;
        let select = document.getElementById('rollSelect');
        if(isRoll) {
            select.classList.remove('d-none');
            document.getElementById('qtyLabel').innerText = "Number of Rolls";
        } else {
            select.classList.add('d-none');
            document.getElementById('qtyLabel').innerText = `Quantity (${selectedProduct.unit})`;
        }
    }

    function addToCart() {
        if(!selectedProduct) return;
        let qty = parseFloat(document.getElementById('modalQty').value);
        if(!qty || qty <= 0) return alert("Invalid Quantity");
        let isRoll = document.getElementById('typeRoll').checked;
        let price = currentFinalPrice; 
        let name = selectedProduct.product_name;
        let unitDisplay = selectedProduct.unit;
        let deductAmount = qty; 

        if(isRoll) {
            let select = document.getElementById('rollSelect');
            if(select.selectedIndex === -1) return alert("Please select a roll type");
            let rollLen = parseFloat(select.value);
            let rollPrice = parseFloat(select.options[select.selectedIndex].dataset.price);
            price = rollPrice; 
            deductAmount = qty * rollLen;
            name += ` (Roll ${rollLen}${selectedProduct.unit})`;
            unitDisplay = 'Roll';
        }

        let existing = cart.find(i => i.id === selectedProduct.id && i.isRoll === isRoll);
        let currentCartQty = existing ? existing.deductAmount : 0;
        
        if((currentCartQty + deductAmount) > selectedProduct.qty) {
            alert("Insufficient Stock! Available: " + selectedProduct.qty + " " + selectedProduct.unit);
            return;
        }

        if(existing) { existing.qty += qty; existing.deductAmount += deductAmount; } 
        else {
            cart.push({ id: selectedProduct.id, name: name, price: price, qty: qty, unit: unitDisplay, deductAmount: deductAmount, isRoll: isRoll });
        }
        renderCart();
        
        if(qtyModalInstance) {
            qtyModalInstance.hide();
        } else {
            bootstrap.Modal.getInstance(document.getElementById('qtyModal')).hide();
        }
        
        setTimeout(() => {
            let searchBar = document.getElementById('searchBar');
            searchBar.value = '';
            searchBar.focus();
            filterProducts(); 
        }, 100);
    }

    function renderCart() {
        let html = '';
        let total = 0;
        let count = cart.length;

        if(count === 0) {
            html = `<div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted w-100 py-5">
                <i class="bi bi-cart-x fs-1 opacity-50 mb-2"></i>
                <h6 class="fw-bold">Cart is empty</h6>
                <span class="small">Scan a barcode or select items</span>
            </div>`;
        } else {
            html = '<ul class="list-group list-group-flush p-1">';
            cart.forEach((item, index) => {
                let itemTotal = item.price * item.qty;
                total += itemTotal;
                html += `<li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 mb-1 rounded-3 border shadow-sm hover-lift" style="transition: all 0.2s;">
                    <div style="width: 42%; overflow: hidden;">
                        <div class="fw-bold text-truncate" title="${item.name}">${item.name}</div>
                        <small class="text-primary fw-bold">Rs. ${parseFloat(item.price).toFixed(2)} <span class="text-muted fw-normal">/ ${item.unit}</span></small>
                    </div>
                    <div style="width: 25%" class="d-flex align-items-center">
                        <input type="number" class="cart-qty-input form-control form-control-sm text-center fw-bold shadow-sm" 
                               value="${item.qty}" min="0.01" step="0.01" 
                               onchange="updateCartQty(${index}, this.value)" style="border-radius: 6px;">
                    </div>
                    <div class="fw-bolder text-primary text-end" style="width: 23%">
                        Rs. ${itemTotal.toFixed(2)}
                    </div>
                    <button class="btn btn-sm btn-light text-danger hover-lift ms-1 rounded-3" onclick="removeFromCart(${index})" title="Remove">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </li>`;
            });
            html += '</ul>';
        }
        document.getElementById('cartContainer').innerHTML = html;
        document.getElementById('cartCount').innerText = count;
        document.getElementById('subTotal').innerText = 'Rs. ' + total.toFixed(2);
        document.getElementById('mobileBtnCount').innerText = count;
        calculateTotal();
    }
    
    function updateCartQty(index, newQty) {
        let qty = parseFloat(newQty);
        if(!qty || qty <= 0) {
            alert("Invalid Quantity");
            renderCart(); 
            return;
        }
        let item = cart[index];
        if(!item.isRoll) {
             let p = products.find(prod => prod.id == item.id);
             if(p && qty > p.qty) {
                 alert("Insufficient Stock!");
                 renderCart();
                 return;
             }
             item.deductAmount = qty;
        }
        item.qty = qty;
        renderCart();
    }

    function calculateTotal() {
        let subTotal = parseFloat(document.getElementById('subTotal').innerText.replace('Rs. ', ''));
        let discount = parseFloat(document.getElementById('cartDiscount').value) || 0;
        let grand = subTotal - discount;
        if(grand < 0) grand = 0;
        let formattedTotal = 'Rs. ' + grand.toFixed(2);
        document.getElementById('grandTotal').innerText = formattedTotal;
        document.getElementById('mobileBtnTotal').innerText = formattedTotal; 
    }

    function removeFromCart(index) { cart.splice(index, 1); renderCart(); }
    function clearCart() { cart = []; renderCart(); }

    function filterProducts(event) {
        let q = document.getElementById('searchBar').value.toLowerCase().trim();
        let catFilter = document.getElementById('catFilter');
        let brandFilter = document.getElementById('brandFilter');
        let branchFilter = document.getElementById('branchFilter');

        let cat = catFilter ? catFilter.value : 'all';
        let brand = brandFilter ? brandFilter.value : 'all';
        let branch = branchFilter ? branchFilter.value : 'all';

        let items = document.querySelectorAll('.product-item');
        let visibleItems = [];

        items.forEach(el => {
            let name = el.dataset.name || '';
            let barcode = (el.dataset.barcode || '').toLowerCase().trim();
            let pCat = el.dataset.cat || '';
            let pBrand = el.dataset.brand || '';
            let pBranch = el.dataset.branch || '';

            let words = q.split(' ').filter(w => w.trim() !== '');
            let matchText = words.length === 0 ? true : words.every(w => name.includes(w) || barcode.includes(w));
            let matchCat = (cat === 'all' || pCat == cat);
            let matchBrand = (brand === 'all' || pBrand == brand);
            let matchBranch = (branch === 'all' || pBranch == branch);

            if (matchText && matchCat && matchBrand && matchBranch) {
                el.style.display = ''; 
                visibleItems.push(el);
            } else {
                el.style.display = 'none';
            }
        });

        let isModalOpen = document.body.classList.contains('modal-open');
        
        if (event && event.key === 'Enter' && !isModalOpen) {
            if (visibleItems.length === 1) {
                visibleItems[0].click(); 
                document.getElementById('searchBar').value = ''; 
                filterProducts(); 
            }
        }
    }

    function openPaymentModal() {
        if(cart.length === 0) return alert("Cart is empty!");
        let grandTotal = document.getElementById('grandTotal').innerText;
        document.getElementById('modalGrandTotal').innerText = grandTotal;
        new bootstrap.Modal(document.getElementById('paymentModal')).show();
    }
    
    function processPayment(paymentMethod) {
        let btnCash = document.getElementById('btnCash');
        let btnCard = document.getElementById('btnCard');
        let targetBtn = paymentMethod === 'cash' ? btnCash : btnCard;
        let originalHtml = targetBtn.innerHTML;
        
        targetBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        btnCash.disabled = true;
        btnCard.disabled = true;

        let total = parseFloat(document.getElementById('grandTotal').innerText.replace('Rs. ', ''));
        let discount = parseFloat(document.getElementById('cartDiscount').value) || 0;

        // --- OFFLINE MODE LOGIC ---
        if (!navigator.onLine) {
            let saleId = 'OFFLINE-' + Date.now();
            let offlineSale = {
                id: saleId,
                cart: JSON.parse(JSON.stringify(cart)), // deep copy
                total: total,
                discount: discount,
                payment_method: paymentMethod,
                timestamp: new Date().toISOString()
            };
            
            let offlineSales = JSON.parse(localStorage.getItem('offlineSales') || '[]');
            offlineSales.push(offlineSale);
            localStorage.setItem('offlineSales', JSON.stringify(offlineSales));
            
            cart.forEach(item => {
                let p = products.find(prod => prod.id == item.id);
                if(p) {
                    p.qty -= item.deductAmount; 
                    let stockEl = document.getElementById('stock-qty-' + p.id);
                    if(stockEl) {
                        stockEl.innerText = (p.qty + 0) + ' ' + p.unit;
                        if(p.qty <= 0) stockEl.classList.add('text-danger');
                    }
                }
            });

            bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
            alert("Internet disconnected! Sale saved offline. Please sync when online.");
            
            clearCart();
            document.getElementById('cartDiscount').value = 0;
            if(window.innerWidth < 768) toggleCart();
            document.getElementById('searchBar').focus();
            
            updateOfflineBadge();
            
            targetBtn.innerHTML = originalHtml;
            btnCash.disabled = false;
            btnCard.disabled = false;
            return;
        }
        // --- END OFFLINE MODE LOGIC ---
        
        fetch("{{ route('pos.store') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ 
                cart: cart, 
                total: total, 
                discount: discount,
                payment_method: paymentMethod 
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                cart.forEach(item => {
                    let p = products.find(prod => prod.id == item.id);
                    if(p) {
                        p.qty -= item.deductAmount; 
                        let stockEl = document.getElementById('stock-qty-' + p.id);
                        if(stockEl) {
                            stockEl.innerText = (p.qty + 0) + ' ' + p.unit;
                            if(p.qty <= 0) stockEl.classList.add('text-danger');
                        }
                    }
                });

                bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
                
                let url = "{{ route('pos.print', ':id') }}".replace(':id', data.sale_id);
                
                const width = 800;
                const height = 900;
                const left = (window.screen.width / 2) - (width / 2);
                const top = (window.screen.height / 2) - (height / 2);
                
                const printWin = window.open(url, 'Receipt', `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,status=no,resizable=no`);
                
                printWin.onload = function() {
                    printWin.focus();
                    printWin.print();
                };
                
                clearCart();
                document.getElementById('cartDiscount').value = 0;
                if(window.innerWidth < 768) toggleCart();
                document.getElementById('searchBar').focus();
            } else { 
                alert("Error: " + data.message); 
            }
        })
        .finally(() => {
            targetBtn.innerHTML = originalHtml;
            btnCash.disabled = false;
            btnCard.disabled = false;
            updatePosData();
        });
    }

    // Offline Mode Helper Functions
    function updateOfflineBadge() {
        let offlineSales = JSON.parse(localStorage.getItem('offlineSales') || '[]');
        let badge = document.getElementById('offlineBadge');
        let countEl = document.getElementById('offlineCount');
        
        if (offlineSales.length > 0) {
            badge.classList.remove('d-none');
            countEl.innerText = offlineSales.length;
        } else {
            badge.classList.add('d-none');
        }
    }

    function syncOfflineSales() {
        let offlineSales = JSON.parse(localStorage.getItem('offlineSales') || '[]');
        if (offlineSales.length === 0) return;
        
        if (!navigator.onLine) {
            alert("You are still offline. Please connect to the internet to sync.");
            return;
        }

        let btn = document.querySelector('#offlineBadge button');
        let originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Syncing...';
        btn.disabled = true;

        fetch("{{ route('pos.sync') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ sales: offlineSales })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                localStorage.removeItem('offlineSales');
                updateOfflineBadge();
                alert(`Successfully synced ${data.count} offline sales!`);
                updatePosData(); // refresh products to get real server stock
            } else {
                alert("Sync Error: " + data.message);
            }
        })
        .catch(err => {
            console.error("Sync error", err);
            alert("Failed to sync. Server might be unreachable.");
        })
        .finally(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    }

    // Listeners for network status
    window.addEventListener('online', updateOfflineBadge);
    window.addEventListener('offline', updateOfflineBadge);

    // Initial check
    document.addEventListener("DOMContentLoaded", function() {
        updateOfflineBadge();
    });

    // Empire POS Keyboard Shortcuts (F1, F2, F4, Escape)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F1') {
            e.preventDefault();
            if (cart.length > 0) openPaymentModal();
        } else if (e.key === 'F2') {
            e.preventDefault();
            let search = document.getElementById('searchBar');
            if (search) { search.focus(); search.select(); }
        } else if (e.key === 'F4') {
            e.preventDefault();
            if (cart.length > 0) clearCart();
        } else if (e.key === 'Escape') {
            let activeModal = document.querySelector('.modal.show');
            if (!activeModal) {
                let search = document.getElementById('searchBar');
                if (search && document.activeElement === search) {
                    search.value = '';
                    filterProducts();
                    search.blur();
                }
            }
        }
    });

</script>
@endsection
