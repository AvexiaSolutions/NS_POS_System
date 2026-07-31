<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        $query = DB::table('returns')
            ->join('sales', 'returns.sale_id', '=', 'sales.id')
            ->join('products', 'returns.product_id', '=', 'products.id')
            ->select(
                'returns.created_at',
                'sales.invoice_no',
                'products.product_name',
                'returns.type',
                'returns.qty',
                'returns.refund_amount',
                'returns.reason'
            )
            ->whereDate('returns.created_at', '>=', $startDate)
            ->whereDate('returns.created_at', '<=', $endDate);

        if (auth()->user()->role !== 'admin') {
            $query->where('returns.branch_id', auth()->user()->branch_id);
        }

        $history = $query->orderBy('returns.created_at', 'desc')->get();

        return view('returns.index', compact('history'));
    }

    public function search(Request $request)
    {
        try {
            $invoice = trim($request->invoice_no);

            $sale = DB::table('sales')->whereRaw('UPPER(invoice_no) = ?', [strtoupper($invoice)])->first();

            if (!$sale) {
                return response()->json(['status' => 'error', 'message' => 'Invoice not found! Please check the code.']);
            }

            $user = DB::table('users')->select('name')->where('id', $sale->user_id)->first();
            $sale->user = $user;

            $items = DB::table('sale_items')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->select(
                    'sale_items.*', 
                    'products.product_name', 
                    'products.unit',
                    'products.warranty_months',
                    'products.has_warranty',
                    'products.barcode'
                )
                ->where('sale_items.sale_id', $sale->id)
                ->get();

            foreach($items as $item) {
                $returnedQty = DB::table('returns')
                    ->where('sale_id', $sale->id)
                    ->where('product_id', $item->product_id)
                    ->sum('qty');
                
                $item->returned_qty = $returnedQty ? (float) $returnedQty : 0;
                $item->available_qty = $item->qty - $item->returned_qty;
            }

            return response()->json([
                'status' => 'success', 
                'sale' => $sale, 
                'items' => $items,
                'sale_date' => date('Y-m-d h:i A', strtotime($sale->created_at))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'System Error: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')'
            ]);
        }
    }

    public function process(Request $request)
    {
        DB::beginTransaction();
        try {
            $saleId = $request->sale_id;
            $items = $request->items; 
            $reason = $request->reason;
            $branchId = auth()->user()->branch_id; 

            $sale = DB::table('sales')->where('id', $saleId)->first();
            $invoiceNo = $sale ? $sale->invoice_no : 'Unknown Invoice';

            foreach ($items as $item) {
                if ($item['qty'] > 0) {
                    $saleItem = DB::table('sale_items')
                        ->where('sale_id', $saleId)
                        ->where('product_id', $item['product_id'])
                        ->first();

                        $refundAmount = 0;

                    if ($item['type'] == 'return' || $item['type'] == 'damage') {
                        $refundAmount = $saleItem->price * $item['qty'];
                    }

                    DB::table('returns')->insert([
                        'id' => (string) Str::uuid(),
                        'branch_id' => $branchId,
                        'sale_id' => $saleId,
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'refund_amount' => $refundAmount,
                        'reason' => $reason,
                        'type' => $item['type'], 
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($item['type'] == 'return') {
                        DB::table('products')->where('id', $item['product_id'])->increment('qty', $item['qty']);
                    }
                }
            }

            \App\Models\AuditLog::record('Process Return/Warranty', "Return/warranty processed for invoice {$invoiceNo}. (Reason: {$reason})");

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Action Processed Successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
