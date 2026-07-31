@extends('layouts.admin')

@section('header', 'Daily Sales Report')

@section('content')
<div class="page-heading">
    <section class="section">
        <style>
            .stat-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
            .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
            
            [data-bs-theme="dark"] .stat-card:hover { box-shadow: 0 12px 30px rgba(0,0,0,0.8) !important; background-color: #171A21 !important; border-color: rgba(255, 255, 255, 0.2) !important; }
            [data-bs-theme="dark"] .table { color: #F8FAFC; border-color: rgba(255, 255, 255, 0.08); }
            [data-bs-theme="dark"] .table-hover tbody tr:hover { color: #fff; background-color: #171A21; }
            [data-bs-theme="dark"] .card { background-color: #111318 !important; border: 1px solid rgba(255, 255, 255, 0.08) !important; }
            [data-bs-theme="dark"] .text-body { color: #F8FAFC !important; }
            [data-bs-theme="dark"] .text-body-secondary { color: #94A3B8 !important; }
        </style>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 bg-body stat-card position-relative overflow-hidden mb-0">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-body-secondary text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.05em;">Total Daily Sales</span>
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(0, 124, 239, 0.15); color: #007CEF;">
                                <i class="bi bi-graph-up-arrow fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="fw-bolder mb-1 text-body">Rs. {{ number_format($dailySaleAmount, 2) }}</h3>
                            <div class="small text-muted d-flex align-items-center gap-1">
                                <i class="bi bi-arrow-up-right text-primary"></i> Today's Gross Revenue
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 bg-body stat-card position-relative overflow-hidden mb-0">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-body-secondary text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.05em;">Cash In Hand</span>
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(32, 201, 151, 0.15); color: #20c997;">
                                <i class="bi bi-cash-stack fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="fw-bolder mb-1 text-body" style="color: #20c997;">Rs. {{ number_format($cashInHandAmount, 2) }}</h3>
                            <div class="small text-muted d-flex align-items-center gap-1">
                                <i class="bi bi-wallet2 text-success"></i> Available Cash Balance
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
            <div class="card-header bg-transparent border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0 text-body"><i class="bi bi-receipt me-2 text-primary"></i>Today's Bills</h5>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">{{ count($dailyBills) }} Bills Today</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 0.95rem;">
                        <thead class="bg-body-secondary text-body-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <tr>
                                <th class="ps-4">Time</th>
                                <th>Invoice No</th>
                                <th>Payment Method</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyBills as $bill)
                            <tr>
                                <td class="ps-4 text-body-secondary">{{ $bill->created_at->format('h:i A') }}</td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-25 text-body border px-3 py-2" id="invoice-{{ $bill->id }}" style="font-size: 0.85rem;">
                                        {{ $bill->invoice_no ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if(strtolower($bill->payment_method) == 'cash')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">Cash</span>
                                    @else
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2">{{ ucfirst($bill->payment_method ?? 'Card') }}</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-body">Rs. {{ number_format($bill->total_amount, 2) }}</td>
                                <td class="text-center pe-4">
                                    <button onclick="copyInvoice('{{ $bill->invoice_no }}')" class="btn btn-sm btn-outline-secondary rounded-pill me-1" title="Copy Invoice No">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                    
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill me-1" data-bs-toggle="modal" data-bs-target="#viewBillModal-{{ $bill->id }}" title="View Bill">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <button onclick="printBill('{{ route('bill.print', $bill->id) }}')" class="btn btn-sm btn-dark rounded-pill" title="Print Bill">
                                        <i class="bi bi-printer"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                    <h6 class="fw-bold">No bills have been issued for today yet.</h6>
                                    <span class="small">Completed sales invoices will appear here</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

@foreach($dailyBills as $bill)
<div class="modal fade text-start" id="viewBillModal-{{ $bill->id }}" tabindex="-1" aria-labelledby="viewBillModalLabel-{{ $bill->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="viewBillModalLabel-{{ $bill->id }}">
                    <i class="bi bi-receipt me-2"></i>Bill Information
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Invoice No:</span>
                    <span class="fw-bold">{{ $bill->invoice_no }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Time:</span>
                    <span>{{ $bill->created_at->format('Y-m-d h:i A') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Payment Method:</span>
                    <span class="badge bg-success">{{ ucfirst($bill->payment_method) }}</span>
                </div>

                <h6 class="fw-bold mt-4 mb-2">Purchased Items:</h6>
                <div class="table-responsive mb-3 border rounded">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="border-bottom">
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($bill->items && $bill->items->count() > 0)
                                @foreach($bill->items as $item)
                                <tr>
                                    <td>{{ $item->product_name ?? ($item->product->name ?? 'Item') }}</td>
                                    <td class="text-center">{{ $item->qty ?? ($item->quantity ?? 1) }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price ?? ($item->price ?? 0), 2) }}</td>
                                    <td class="text-end">{{ number_format($item->total ?? ($item->sub_total ?? 0), 2) }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-2">No items found for this bill.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mb-2 mt-3">
                    <span class="text-muted">Sub Total:</span>
                    <span>Rs. {{ number_format($bill->sub_total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Discount:</span>
                    <span class="text-danger">- Rs. {{ number_format($bill->discount, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h5 class="m-0 fw-bold">Total Amount:</h5>
                    <h4 class="m-0 fw-bold text-primary">Rs. {{ number_format($bill->total_amount, 2) }}</h4>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button onclick="printBill('{{ route('bill.print', $bill->id) }}')" class="btn btn-dark">
                    <i class="bi bi-printer me-2"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>

    function showNotification(message) {
        let notification = document.createElement('div');
        notification.innerText = message;

        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.left = '50%';
        notification.style.transform = 'translateX(-50%)';
        notification.style.backgroundColor = '#198754'; 
        notification.style.color = 'white';
        notification.style.padding = '10px 25px';
        notification.style.borderRadius = '50px'; 
        notification.style.boxShadow = '0 4px 10px rgba(0,0,0,0.2)';
        notification.style.zIndex = '9999';
        notification.style.fontWeight = 'bold';
        notification.style.fontSize = '0.95rem';
        notification.style.transition = 'opacity 0.4s ease, top 0.4s ease';

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.top = '10px';
            setTimeout(() => {
                notification.remove();
            }, 400);
        }, 2500);
    }

    function copyInvoice(invoiceNo) {
        if(!invoiceNo || invoiceNo === '') {
            showNotification('Invoice number not available!');
            return;
        }
        navigator.clipboard.writeText(invoiceNo).then(() => {
            showNotification("Copied : " + invoiceNo);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
            alert('Failed to copy. Please copy manually.');
        });
    }

    function printBill(url) {
        let printWindow = window.open(url, '_blank', 'width=800,height=600');
        printWindow.onload = function() {
            printWindow.print();
        };
    }
</script>
@endsection
