<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\AuditLog;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $branchId = auth()->user()->role !== 'admin' ? auth()->user()->branch_id : null;
        $cacheKey = 'pos_data_' . ($branchId ?? 'all');

        $data = Cache::remember($cacheKey, 10, function () {
            return [
                'products' => Product::with(['rolls', 'category', 'brand', 'branch'])
                                ->where('qty', '>', 0)
                                ->get(),
                'categories' => Category::all(),
                'brands' => Brand::all(),
            ];
        });

        if ($request->ajax()) {
            return response()->json($data['products']);
        }

        return view('pos.index', [
            'products' => $data['products'],
            'categories' => $data['categories'],
            'brands' => $data['brands']
        ]);
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $saleId = (string) Str::uuid();
            $paymentMethod = $request->payment_method ?? 'cash';
            
            $totalAmount = $request->total;
            $bankCharge = 0;
            $netToBank = $totalAmount;

            $invoiceNo = 'INV-' . strtoupper(Str::random(8));

            if ($paymentMethod === 'card') {
                $bankCharge = $totalAmount * 0.03;
                $netToBank = $totalAmount - $bankCharge;
            }

            DB::table('sales')->insert([
                'id' => $saleId,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->id(),
                'invoice_no' => $invoiceNo,
                'sub_total' => $request->total + ($request->discount ?? 0),
                'discount' => $request->discount ?? 0,
                'total_amount' => $totalAmount,
                'cash_received' => $totalAmount,
                'payment_method' => $paymentMethod,
                'bank_charge' => $bankCharge, 
                'balance' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($paymentMethod === 'card') {
                $primaryBank = DB::table('bank_accounts')->where('is_primary', true)->first();
                
                if ($primaryBank) {
                    DB::table('bank_accounts')
                        ->where('id', $primaryBank->id)
                        ->increment('current_balance', $netToBank);

                    DB::table('transactions')->insert([
                        'id' => (string) Str::uuid(),
                        'branch_id' => auth()->user()->branch_id,
                        'type' => 'deposit',
                        'category' => 'Sales (Card)',
                        'amount' => $netToBank,
                        'payment_method' => 'bank',
                        'bank_account_id' => $primaryBank->id,
                        'description' => "Sales via Card (INV Total: Rs.{$totalAmount}, 3% Bank Fee: Rs.{$bankCharge})",
                        'transaction_date' => now()->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            foreach ($request->cart as $item) {
                $deduct = isset($item['deductAmount']) ? $item['deductAmount'] : $item['qty'];
                DB::table('products')->where('id', $item['id'])->decrement('qty', $deduct);

                DB::table('sale_items')->insert([
                    'id' => (string) Str::uuid(),
                    'sale_id' => $saleId,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['qty'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $userBranchId = auth()->user()->branch_id;
            Cache::forget('pos_data_' . $userBranchId);
            Cache::forget('pos_data_all');
            Cache::forget('dashboard_data_' . $userBranchId);
            Cache::forget('dashboard_data_all');

            AuditLog::record('POS Sale', "A new sale of Rs. {$totalAmount} was made under invoice number {$invoiceNo} (Payment method: {$paymentMethod}).");

            return response()->json(['status' => 'success', 'sale_id' => $saleId]);
        });
    }

    public function print($id)
    {
        $sale = DB::table('sales')->where('id', $id)->first();
        if (!$sale) return "Bill Not Found!";

        $saleItems = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('sale_items.*', 'products.selling_price as original_price')
            ->where('sale_items.sale_id', $id)
            ->get();

        $shop = DB::table('shop_settings')->first();

        return view('pos.receipt', compact('sale', 'saleItems', 'shop'));
    }

    public function sync(Request $request)
    {
        $sales = $request->sales;
        if (!$sales || !is_array($sales)) {
            return response()->json(['status' => 'error', 'message' => 'No sales data provided.']);
        }

        return DB::transaction(function () use ($sales) {
            $syncedCount = 0;
            foreach ($sales as $offlineSale) {
                $saleId = (string) Str::uuid();
                $paymentMethod = $offlineSale['payment_method'] ?? 'cash';
                $totalAmount = $offlineSale['total'];
                $bankCharge = 0;
                $netToBank = $totalAmount;

                $invoiceNo = 'INV-' . strtoupper(Str::random(8)) . '-SYNC';

                if ($paymentMethod === 'card') {
                    $bankCharge = $totalAmount * 0.03;
                    $netToBank = $totalAmount - $bankCharge;
                }

                DB::table('sales')->insert([
                    'id' => $saleId,
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->id(),
                    'invoice_no' => $invoiceNo,
                    'sub_total' => $offlineSale['total'] + ($offlineSale['discount'] ?? 0),
                    'discount' => $offlineSale['discount'] ?? 0,
                    'total_amount' => $totalAmount,
                    'cash_received' => $totalAmount,
                    'payment_method' => $paymentMethod,
                    'bank_charge' => $bankCharge, 
                    'balance' => 0,
                    'created_at' => $offlineSale['timestamp'] ?? now(),
                    'updated_at' => now(),
                ]);

                if ($paymentMethod === 'card') {
                    $primaryBank = DB::table('bank_accounts')->where('is_primary', true)->first();
                    if ($primaryBank) {
                        DB::table('bank_accounts')->where('id', $primaryBank->id)->increment('current_balance', $netToBank);
                        DB::table('transactions')->insert([
                            'id' => (string) Str::uuid(),
                            'branch_id' => auth()->user()->branch_id,
                            'type' => 'deposit',
                            'category' => 'Sales (Card)',
                            'amount' => $netToBank,
                            'payment_method' => 'bank',
                            'bank_account_id' => $primaryBank->id,
                            'description' => "Offline Sync Sales via Card (INV: {$invoiceNo})",
                            'transaction_date' => now()->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                foreach ($offlineSale['cart'] as $item) {
                    $deduct = isset($item['deductAmount']) ? $item['deductAmount'] : $item['qty'];
                    DB::table('products')->where('id', $item['id'])->decrement('qty', $deduct);

                    DB::table('sale_items')->insert([
                        'id' => (string) Str::uuid(),
                        'sale_id' => $saleId,
                        'product_id' => $item['id'],
                        'product_name' => $item['name'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'total' => $item['price'] * $item['qty'],
                        'created_at' => $offlineSale['timestamp'] ?? now(),
                        'updated_at' => now(),
                    ]);
                }
                $syncedCount++;
            }

            $userBranchId = auth()->user()->branch_id;
            Cache::forget('pos_data_' . $userBranchId);
            Cache::forget('pos_data_all');
            Cache::forget('dashboard_data_' . $userBranchId);
            Cache::forget('dashboard_data_all');

            AuditLog::record('POS Offline Sync', "Synced {$syncedCount} offline sales to the database.");

            return response()->json(['status' => 'success', 'count' => $syncedCount]);
        });
    }
}
