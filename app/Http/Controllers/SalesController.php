<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 

class SalesController extends Controller
{
    public function dailySales()
    {
        $today = Carbon::today();

        $dailySaleAmount = Sale::whereDate('created_at', $today)->sum('total_amount');

        $cashSales = DB::table('sales')->where('payment_method', 'cash')->sum('total_amount');
        $cashReturns = DB::table('returns')->sum('refund_amount');
        
        $manualCashIn = DB::table('cash_books')->where('type', 'in')->sum('amount');
        $manualCashOut = DB::table('cash_books')->where('type', 'out')->sum('amount');

        $cashInHandAmount = ($cashSales + $manualCashIn) - ($cashReturns + $manualCashOut);

        $dailyBills = Sale::with('items')->whereDate('created_at', $today)->latest()->get();

        return view('cashier.daily-sales', compact('dailySaleAmount', 'cashInHandAmount', 'dailyBills'));
    }

    public function show($id)
    {
        $sale = Sale::with('items')->findOrFail($id); 
        
        return view('cashier.bill-view', compact('sale'));
    }

    public function print($id)
    {
        $sale = Sale::with('items')->findOrFail($id);
        
        $saleItems = $sale->items; 
        
        $shop = DB::table('shop_settings')->first(); 
        
        return view('pos.receipt', compact('sale', 'saleItems', 'shop'));
    }

    public function store(Request $request)
    {

    }
}
