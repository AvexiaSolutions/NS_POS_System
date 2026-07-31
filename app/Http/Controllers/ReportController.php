<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        $totalReturnValue = 0;
        $returnsCost = 0;

        if (Schema::hasTable('returns')) {
            $totalReturnValue = DB::table('returns')
                ->whereBetween('created_at', [$startDate, $endDateTime])
                ->sum('refund_amount');

            $returnsCost = DB::table('returns')
                ->join('products', 'returns.product_id', '=', 'products.id')
                ->whereBetween('returns.created_at', [$startDate, $endDateTime])
                ->where('returns.type', 'return')
                ->sum(DB::raw('products.cost_price * returns.qty'));
        }

        $salesQuery = DB::table('sales')->whereBetween('created_at', [$startDate, $endDateTime]);

        $grossSales = (clone $salesQuery)->sum('total_amount');
        $totalDiscount = (clone $salesQuery)->sum('discount');
        $totalBankCharges = (clone $salesQuery)->sum('bank_charge');

        $totalSales = $grossSales - $totalReturnValue;

        $grossCost = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.created_at', [$startDate, $endDateTime])
            ->sum(DB::raw('products.cost_price * sale_items.qty'));

        $totalCost = $grossCost - $returnsCost;

        $totalExpenses = 0;
        if (Schema::hasTable('expenses')) {
            $totalExpenses += DB::table('expenses')
                ->whereBetween('expense_date', [$startDate, $endDateTime])
                ->sum('amount');
        }

        if (Schema::hasTable('cash_books')) {
            $totalExpenses += DB::table('cash_books')
                ->where('category', 'expense')
                ->where('type', 'out')
                ->whereBetween('created_at', [$startDate, $endDateTime])
                ->sum('amount');
        }

        $netProfit = $totalSales - $totalCost - $totalExpenses - $totalBankCharges;

        $salesList = DB::table('sales')
            ->whereBetween('created_at', [$startDate, $endDateTime])
            ->orderBy('created_at', 'desc')
            ->limit(50) 
            ->get();

        $lowStockItemsCount = DB::table('products')->whereColumn('qty', '<=', 'alert_qty')->count();

        $stockValueCost = DB::table('products')->sum(DB::raw('cost_price * qty'));
        $stockValueSelling = DB::table('products')->sum(DB::raw('selling_price * qty'));

        $topSellingProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select('sale_items.product_name', DB::raw('SUM(sale_items.qty) as total_sold'))
            ->whereBetween('sales.created_at', [$startDate, $endDateTime])
            ->groupBy('sale_items.product_name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $soldProductIds = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDateTime])
            ->pluck('sale_items.product_id')
            ->toArray();

        $deadStock = DB::table('products')
            ->whereNotIn('id', $soldProductIds)
            ->where('qty', '>', 0)
            ->select('product_name', 'qty') 
            ->orderByDesc('qty')
            ->limit(10)
            ->get();

        $cashierPerformance = DB::table('sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->select(
                'users.name',
                DB::raw('COUNT(sales.id) as total_bills'),
                DB::raw('SUM(sales.total_amount) as total_collected')
            )
            ->whereBetween('sales.created_at', [$startDate, $endDateTime])
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_collected')
            ->get();

        $grossCashTotal = (clone $salesQuery)->where('payment_method', 'cash')->sum('total_amount');
        $cashTotal = $grossCashTotal - $totalReturnValue;
        $cardTotal = (clone $salesQuery)->where('payment_method', 'card')->sum('total_amount');
        
        $adjustedTotalForPercentages = $cashTotal + $cardTotal;
        $cashPercentage = $adjustedTotalForPercentages > 0 ? round(($cashTotal / $adjustedTotalForPercentages) * 100) : 0;
        $cardPercentage = $adjustedTotalForPercentages > 0 ? round(($cardTotal / $adjustedTotalForPercentages) * 100) : 0;

        $banks = Schema::hasTable('bank_accounts') ? DB::table('bank_accounts')->get() : [];
        
        $totalCashSalesAllTime = DB::table('sales')->where('payment_method', 'cash')->sum('total_amount');
        $totalCashReturnsAllTime = Schema::hasTable('returns') ? DB::table('returns')->sum('refund_amount') : 0;
        $manualCashIn = Schema::hasTable('cash_books') ? DB::table('cash_books')->where('type', 'in')->sum('amount') : 0;
        $manualCashOut = Schema::hasTable('cash_books') ? DB::table('cash_books')->where('type', 'out')->sum('amount') : 0;
        $cashInHand = ($totalCashSalesAllTime + $manualCashIn) - ($totalCashReturnsAllTime + $manualCashOut);

        $returnsList = [];
        if (Schema::hasTable('returns')) {
            $returnsList = DB::table('returns')
                ->leftJoin('sales', 'returns.sale_id', '=', 'sales.id')
                ->leftJoin('products', 'returns.product_id', '=', 'products.id')
                ->select('returns.*', 'sales.invoice_no', 'products.product_name')
                ->whereBetween('returns.created_at', [$startDate, $endDateTime])
                ->orderBy('returns.created_at', 'desc')
                ->get();
        }

        return view('reports.index', compact(
            'startDate', 'endDate',
            'totalSales', 'totalCost', 'totalExpenses', 'totalBankCharges', 'totalDiscount', 'netProfit', 'salesList',
            'stockValueCost', 'stockValueSelling', 'lowStockItemsCount', 'topSellingProducts', 'deadStock',
            'cashierPerformance', 'cashTotal', 'cardTotal', 'cashPercentage', 'cardPercentage',
            'returnsList', 'totalReturnValue',
            'banks', 'cashInHand'
        ));
    }
}
