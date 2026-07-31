@extends('layouts.admin')

@section('title', 'Quotations & Loyalty Bills')
@section('header', 'Quotations & Loyalty Bills')

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
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.25) !important;
    }
    [data-bs-theme="dark"] .table {
        color: #e2e8f0;
        border-color: rgba(255, 255, 255, 0.08);
    }
    [data-bs-theme="dark"] .table-light th {
        background-color: #161920 !important;
        color: #fff !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .list-group-item {
        background-color: #111318 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        color: #e2e8f0 !important;
    }
    [data-bs-theme="dark"] .bg-light {
        background-color: #161920 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .modal-content {
        background-color: #111318 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #fff !important;
    }

    /* Light Mode Search Bar Border (Darker & Highly Visible) */
    #productSearch {
        border: 2px solid #64748b !important;
        background-color: #f8fafc !important;
        color: #0f172a !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
        transition: all 0.25s ease;
    }
    #productSearch:focus {
        border-color: #007CEF !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.18) !important;
    }
    [data-bs-theme="dark"] #productSearch {
        background-color: #161920 !important;
        border: 2px solid rgba(255, 255, 255, 0.22) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important;
    }
    [data-bs-theme="dark"] #productSearch:focus {
        background-color: #1D212A !important;
        border-color: #007CEF !important;
        box-shadow: 0 0 0 4px rgba(0, 124, 239, 0.25) !important;
    }

    .search-dropdown { position: absolute; z-index: 1050; width: 100%; max-height: 280px; overflow-y: auto; display: none; }
    [data-bs-theme="dark"] .search-dropdown .list-group-item:hover { background-color: #171A21 !important; cursor: pointer; }
    [data-bs-theme="light"] .search-dropdown .list-group-item:hover { background-color: #f8f9fa !important; cursor: pointer; }

    .qty-input, .price-input { min-width: 80px; font-weight: bold; text-align: center; }

    .recent-records-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 400px;
    }
    .recent-records-body {
        flex-grow: 1;
        overflow-y: auto;
    }
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15) !important;
    }
</style>

<div class="row g-4 align-items-stretch">
    <div class="col-lg-7 d-flex flex-column">
        <div class="card shadow-sm flex-grow-1 mb-0 border-top border-4 border-primary" style="border-radius: 14px;">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-cart-plus-fill text-primary"></i>
                    <span>Add Items</span>
                </h5>
                <button class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3 d-inline-flex align-items-center gap-1" onclick="openCustomModal()">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Custom Item</span>
                </button>
            </div>
            <div class="card-body p-4 d-flex flex-column">
                <div class="position-relative mb-3">
                    <div class="position-relative d-flex align-items-center">
                        <span class="position-absolute d-flex align-items-center justify-content-center" style="left: 20px; width: 24px; height: 24px; z-index: 5; pointer-events: none;">
                            <i class="bi bi-search text-primary fs-5"></i>
                        </span>
                        <input type="text" id="productSearch" class="form-control form-control-lg shadow-sm fw-bold" placeholder="Search or scan product..." oninput="searchProducts(this.value)" onkeydown="if(event.key==='Enter') addFirstProduct()" autocomplete="off" autofocus style="padding-left: 56px !important; border-radius: 50px; height: 50px; font-size: 1.05rem;">
                    </div>
                    <div id="searchDropdown" class="list-group search-dropdown shadow-lg rounded-4 overflow-hidden mt-1" style="border: 1px solid rgba(255,255,255,0.1);">
                    </div>
                </div>

                <div class="table-responsive flex-grow-1" style="max-height: 60vh;">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light position-sticky top-0" style="z-index: 1;">
                            <tr>
                                <th>Item Name</th>
                                <th style="width: 100px;">Qty</th>
                                <th style="width: 130px;">Unit Price (Rs)</th>
                                <th class="text-end" style="width: 120px;">Total</th>
                                <th class="text-center" style="width: 50px;"><i class="bi bi-gear"></i></th>
                            </tr>
                        </thead>
                        <tbody id="quoteItems">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center text-center py-3">
                                        <i class="bi bi-basket fs-1 mb-2 opacity-50 d-inline-block"></i>
                                        <span class="fw-bold d-block fs-6">No items added yet</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5 d-flex flex-column">
        <div class="card shadow-sm mb-4 border-top border-4 border-primary flex-shrink-0" style="border-radius: 14px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-sliders text-primary"></i>
                        <span>Document Settings</span>
                    </h5>
                    <div class="form-check form-switch fs-5 d-flex align-items-center gap-2 mb-0">
                        <input class="form-check-input mt-0" type="checkbox" id="is_fake_bill">
                        <label class="form-check-label small fw-bold text-info" for="is_fake_bill" style="cursor: pointer;">Loyalty Bill Mode</label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Customer Name (Optional)</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" id="customer_name" class="form-control" placeholder="Enter Customer Name">
                    </div>
                </div>

                <div class="bg-light p-4 rounded-4 mb-4 border d-flex justify-content-between align-items-center shadow-sm">
                    <span class="fs-6 fw-bold text-muted text-uppercase">Total Amount</span>
                    <h2 class="text-primary fw-bolder mb-0" id="grandTotal">Rs. 0.00</h2>
                </div>
                
                <input type="hidden" id="editingQuoteId" value="">
                
                <button class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm rounded-pill d-flex align-items-center justify-content-center gap-2 hover-lift" id="saveBtn" onclick="saveQuotation()">
                    <i class="bi bi-printer-fill fs-5 d-flex align-items-center"></i>
                    <span>SAVE & PRINT</span>
                </button>
                <button class="btn btn-outline-danger w-100 py-2 mt-2 fw-bold rounded-pill d-flex align-items-center justify-content-center gap-2 d-none" id="cancelEditBtn" onclick="cancelEdit()">
                    <i class="bi bi-x-circle fs-5 d-flex align-items-center"></i>
                    <span>Cancel Edit</span>
                </button>
            </div>
        </div>
        
        <div class="card shadow-sm recent-records-card" style="border-radius: 14px;">
            <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-primary"></i>
                <h6 class="mb-0 fw-bold">Recent Records</h6>
            </div>
            <div class="card-body p-0 recent-records-body">
                <ul class="list-group list-group-flush" id="recentQuotesList">
                    @forelse($quotations as $q)
                        <li class="list-group-item p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $q->is_fake_bill ? 'bg-info text-dark' : 'bg-primary' }} rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1">
                                        <i class="bi {{ $q->is_fake_bill ? 'bi-shield-check' : 'bi-file-earmark-text' }}"></i>
                                        <span>{{ $q->is_fake_bill ? 'Loyalty Bill' : 'Quotation' }}</span>
                                    </span>
                                    <strong class="ms-1">{{ $q->customer_name ?? 'Guest' }}</strong>
                                </div>
                                <span class="fw-bold text-success fs-6">Rs. {{ number_format($q->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>{{ $q->created_at }}</span>
                                </small>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" onclick="editQuote('{{ $q->id }}')" title="Edit"><i class="bi bi-pencil-square"></i></button>
                                    <a href="{{ route('quotations.print', $q->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center" title="Print"><i class="bi bi-printer"></i></a>
                                    <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" onclick="deleteQuote('{{ $q->id }}', this)" title="Delete"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center text-center py-3">
                                <i class="bi bi-journal-x fs-1 mb-2 opacity-50 d-inline-block"></i>
                                <span class="fw-bold d-block fs-6">No records found</span>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="customItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 14px;">
            <div class="modal-header bg-transparent border-bottom py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle-fill text-primary"></i>
                    <span>Add Custom Item</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Item Name</label>
                    <input type="text" id="customName" class="form-control" placeholder="E.g. Labor Charge">
                </div>
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Qty</label>
                        <input type="number" id="customQty" class="form-control text-center" value="1" min="0.01" step="0.01">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Price (Rs)</label>
                        <input type="number" id="customPrice" class="form-control text-center" value="0" min="0">
                    </div>
                </div>
                <button class="btn btn-primary w-100 fw-bold shadow-sm rounded-pill py-2 d-flex align-items-center justify-content-center gap-2" onclick="addCustomToTable()">
                    <i class="bi bi-check2-circle fs-5 d-flex align-items-center"></i>
                    <span>Add to List</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let quoteList = [];
    let systemProducts = @json($products ?? []); 
    let currentSearchResults = [];

    function searchProducts(query) {
        let dropdown = document.getElementById('searchDropdown');
        dropdown.innerHTML = '';
        currentSearchResults = [];
        
        if (!query.trim()) {
            dropdown.style.display = 'none';
            return;
        }

        let q = query.toLowerCase();
        let matches = systemProducts.filter(p => {
            let nameMatch = p.product_name && p.product_name.toLowerCase().includes(q);
            let barcodeMatch = p.barcode && p.barcode.toLowerCase().includes(q);
            return nameMatch || barcodeMatch;
        }).slice(0, 8); 

        currentSearchResults = matches;

        if (matches.length > 0) {
            matches.forEach(p => {
                dropdown.innerHTML += `
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" style="cursor: pointer;" onclick="addSystemProduct('${p.id}')">
                        <div>
                            <span class="fw-bold d-block">${p.product_name}</span>
                            <small class="text-muted">Stock: ${parseFloat(p.qty)} ${p.unit || ''}</small>
                        </div>
                        <span class="fw-bold text-success">Rs. ${parseFloat(p.selling_price).toFixed(2)}</span>
                    </div>
                `;
            });
            dropdown.style.display = 'block';
        } else {
            dropdown.innerHTML = '<div class="list-group-item text-muted text-center py-2">No product found</div>';
            dropdown.style.display = 'block';
        }
    }

    function addFirstProduct() {
        if(currentSearchResults.length > 0) {
            addSystemProduct(currentSearchResults[0].id);
        }
    }

    document.addEventListener('click', function(e) {
        if(!document.getElementById('searchDropdown').contains(e.target) && e.target.id !== 'productSearch') {
            document.getElementById('searchDropdown').style.display = 'none';
        }
    });

    function addSystemProduct(id) {
        let product = systemProducts.find(p => p.id == id);
        if(product) {
            let existing = quoteList.find(i => i.product_id == product.id);
            if(existing) {
                existing.qty += 1;
            } else {
                quoteList.push({ 
                    product_id: product.id, 
                    name: product.product_name, 
                    qty: 1, 
                    price: parseFloat(product.selling_price) 
                });
            }
            renderTable();
            let searchInput = document.getElementById('productSearch');
            searchInput.value = '';
            document.getElementById('searchDropdown').style.display = 'none';
            searchInput.focus();
        }
    }

    function openCustomModal() {
        document.getElementById('customName').value = '';
        document.getElementById('customQty').value = '1';
        document.getElementById('customPrice').value = '0';
        new bootstrap.Modal(document.getElementById('customItemModal')).show();
        setTimeout(() => document.getElementById('customName').focus(), 500);
    }

    function addCustomToTable() {
        let name = document.getElementById('customName').value.trim();
        let qty = parseFloat(document.getElementById('customQty').value);
        let price = parseFloat(document.getElementById('customPrice').value);

        if(!name) return alert("Please enter item name.");
        if(qty <= 0) return alert("Invalid quantity.");

        quoteList.push({ product_id: null, name: name, qty: qty, price: price });
        renderTable();
        bootstrap.Modal.getInstance(document.getElementById('customItemModal')).hide();
        document.getElementById('productSearch').focus();
    }

    function renderTable() {
        let html = '';
        let total = 0;
        
        if (quoteList.length === 0) {
            html = '<tr><td colspan="5" class="text-center text-muted py-5"><div class="d-flex flex-column align-items-center justify-content-center text-center py-3"><i class="bi bi-basket fs-1 mb-2 opacity-50 d-inline-block"></i><span class="fw-bold d-block fs-6">No items added yet</span></div></td></tr>';
        } else {
            quoteList.forEach((item, index) => {
                let itemTotal = item.qty * item.price;
                total += itemTotal;
                html += `<tr>
                    <td class="fw-bold">${item.name}</td>
                    <td><input type="number" class="form-control form-control-sm qty-input" value="${item.qty}" min="0.01" step="0.01" onchange="updateRow(${index}, 'qty', this.value)"></td>
                    <td><input type="number" class="form-control form-control-sm price-input" value="${item.price}" min="0" onchange="updateRow(${index}, 'price', this.value)"></td>
                    <td class="fw-bold text-end">Rs. ${itemTotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm text-danger" onclick="removeRow(${index})"><i class="bi bi-trash-fill"></i></button>
                    </td>
                </tr>`;
            });
        }
        
        document.getElementById('quoteItems').innerHTML = html;
        document.getElementById('grandTotal').innerText = total.toFixed(2);
    }

    function updateRow(index, field, val) {
        let numericVal = parseFloat(val);
        if(!numericVal || numericVal < 0) numericVal = 0;
        quoteList[index][field] = numericVal;
        renderTable();
    }

    function removeRow(index) {
        quoteList.splice(index, 1);
        renderTable();
    }

    function saveQuotation() {
        if(quoteList.length === 0) return alert("Please add at least one item!");
        
        let editingId = document.getElementById('editingQuoteId').value;
        let url = editingId ? `{{ url('quotations') }}/${editingId}` : "{{ route('quotations.store') }}";
        let method = "POST";
        
        let data = {
            customer_name: document.getElementById('customer_name').value,
            total_amount: document.getElementById('grandTotal').innerText,
            is_fake_bill: document.getElementById('is_fake_bill').checked ? 1 : 0,
            items: quoteList,
            _token: "{{ csrf_token() }}"
        };

        if(editingId) {
            data._method = "PUT";
        }

        let btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        fetch(url, {
            method: method,
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success' || res.id) {
                let printId = res.id || editingId;
                window.open("{{ url('quotations/print') }}/" + printId, '_blank', 'width=400,height=600');
                location.reload();
            } else {
                alert("Error saving document.");
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-printer-fill"></i> SAVE & PRINT';
            }
        }).catch(e => {
            console.error(e);
            alert("System Error! Check console.");
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-printer-fill"></i> SAVE & PRINT';
        });
    }

    function editQuote(id) {
        fetch(`{{ url('quotations') }}/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            if(data.status === 'error') {
                return alert(data.message);
            }
            
            document.getElementById('editingQuoteId').value = data.id;
            document.getElementById('customer_name').value = data.customer_name;
            document.getElementById('is_fake_bill').checked = data.is_fake_bill == 1;
            
            quoteList = typeof data.items === 'string' ? JSON.parse(data.items) : data.items;
            
            renderTable();

            document.getElementById('saveBtn').innerHTML = '<i class="bi bi-pencil-square fs-5 d-flex align-items-center"></i> <span>UPDATE & PRINT</span>';
            document.getElementById('saveBtn').classList.replace('btn-primary', 'btn-warning');
            document.getElementById('saveBtn').classList.replace('text-white', 'text-dark');
            document.getElementById('cancelEditBtn').classList.remove('d-none');
            window.scrollTo(0, 0); 
        }).catch(e => {
            console.error(e);
            alert("Error loading data.");
        });
    }

    function cancelEdit() {
        document.getElementById('editingQuoteId').value = '';
        document.getElementById('customer_name').value = '';
        document.getElementById('is_fake_bill').checked = false;
        quoteList = [];
        renderTable();
        
        let saveBtn = document.getElementById('saveBtn');
        saveBtn.innerHTML = '<i class="bi bi-printer-fill fs-5 d-flex align-items-center"></i> <span>SAVE & PRINT</span>';
        saveBtn.classList.replace('btn-warning', 'btn-primary');
        saveBtn.classList.add('text-white');
        
        document.getElementById('cancelEditBtn').classList.add('d-none');
    }

    function deleteQuote(id, btnElement) {
        let originalHtml = btnElement.innerHTML;
        btnElement.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        btnElement.disabled = true;

        fetch(`{{ url('quotations') }}/${id}`, {
            method: 'DELETE',
            headers: { 
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}" 
            }
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                location.reload();
            } else {
                alert("Failed to delete.");
                btnElement.innerHTML = originalHtml;
                btnElement.disabled = false;
            }
        }).catch(e => {
            console.error(e);
            alert("Delete failed.");
            btnElement.innerHTML = originalHtml;
            btnElement.disabled = false;
        });
    }
</script>
@endsection
