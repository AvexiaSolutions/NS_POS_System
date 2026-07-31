<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'cashier') {
            return redirect()->route('pos.index');
        }

        $user = auth()->user();
        $branchId = $user->role !== 'admin' ? $user->branch_id : null;

        $cacheKey = 'dashboard_data_' . ($branchId ?? 'all');

        $data = Cache::remember($cacheKey, 10, function () use ($branchId) {
            $today = Carbon::today();
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $todayGrossSalesQuery = DB::table('sales')->whereDate('created_at', $today);
            if ($branchId) $todayGrossSalesQuery->where('branch_id', $branchId);
            $todayGrossSales = $todayGrossSalesQuery->sum('total_amount');

            $todayReturnsQuery = DB::table('returns')->whereDate('created_at', $today);
            if ($branchId) $todayReturnsQuery->where('branch_id', $branchId);
            $todayReturns = $todayReturnsQuery->sum('refund_amount');

            $todaySales = $todayGrossSales - $todayReturns;

            $todayItemProfitQuery = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->whereDate('sales.created_at', $today);
            if ($branchId) $todayItemProfitQuery->where('sales.branch_id', $branchId);
            $todayItemProfit = $todayItemProfitQuery->sum(DB::raw('(sale_items.price - products.cost_price) * sale_items.qty'));

            $todayCartDiscountQuery = DB::table('sales')->whereDate('created_at', $today);
            if ($branchId) $todayCartDiscountQuery->where('branch_id', $branchId);
            $todayCartDiscount = $todayCartDiscountQuery->sum('discount');

            $todayBankChargesQuery = DB::table('sales')->whereDate('created_at', $today);
            if ($branchId) $todayBankChargesQuery->where('branch_id', $branchId);
            $todayBankCharges = $todayBankChargesQuery->sum('bank_charge');

            $todayGrossProfit = $todayItemProfit - $todayCartDiscount - $todayBankCharges;

            $todayReturnsProfitLossQuery = DB::table('returns')
                ->join('products', 'returns.product_id', '=', 'products.id')
                ->whereDate('returns.created_at', $today);
            if ($branchId) $todayReturnsProfitLossQuery->where('returns.branch_id', $branchId);
            $todayReturnsProfitLoss = $todayReturnsProfitLossQuery->sum(DB::raw('CASE WHEN returns.type = "return" THEN returns.refund_amount - (products.cost_price * returns.qty) ELSE returns.refund_amount END'));

            $todayProfit = $todayGrossProfit - $todayReturnsProfitLoss;

            $monthlyGrossSalesQuery = DB::table('sales')->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            if ($branchId) $monthlyGrossSalesQuery->where('branch_id', $branchId);
            $monthlyGrossSales = $monthlyGrossSalesQuery->sum('total_amount');

            $monthlyReturnsQuery = DB::table('returns')->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            if ($branchId) $monthlyReturnsQuery->where('branch_id', $branchId);
            $monthlyReturns = $monthlyReturnsQuery->sum('refund_amount');

            $monthlySales = $monthlyGrossSales - $monthlyReturns;

            $monthlyItemProfitQuery = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->whereBetween('sales.created_at', [$startOfMonth, $endOfMonth]);
            if ($branchId) $monthlyItemProfitQuery->where('sales.branch_id', $branchId);
            $monthlyItemProfit = $monthlyItemProfitQuery->sum(DB::raw('(sale_items.price - products.cost_price) * sale_items.qty'));

            $monthlyCartDiscountQuery = DB::table('sales')->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            if ($branchId) $monthlyCartDiscountQuery->where('branch_id', $branchId);
            $monthlyCartDiscount = $monthlyCartDiscountQuery->sum('discount');

            $monthlyBankChargesQuery = DB::table('sales')->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            if ($branchId) $monthlyBankChargesQuery->where('branch_id', $branchId);
            $monthlyBankCharges = $monthlyBankChargesQuery->sum('bank_charge');

            $monthlyGrossProfit = $monthlyItemProfit - $monthlyCartDiscount - $monthlyBankCharges;

            $monthlyReturnsProfitLossQuery = DB::table('returns')
                ->join('products', 'returns.product_id', '=', 'products.id')
                ->whereBetween('returns.created_at', [$startOfMonth, $endOfMonth]);
            if ($branchId) $monthlyReturnsProfitLossQuery->where('returns.branch_id', $branchId);
            $monthlyReturnsProfitLoss = $monthlyReturnsProfitLossQuery->sum(DB::raw('CASE WHEN returns.type = "return" THEN returns.refund_amount - (products.cost_price * returns.qty) ELSE returns.refund_amount END'));

            $monthlyProfit = $monthlyGrossProfit - $monthlyReturnsProfitLoss;

            $totalCashSalesQuery = DB::table('sales')->where('payment_method', 'cash');
            if ($branchId) $totalCashSalesQuery->where('branch_id', $branchId);
            $totalCashSales = $totalCashSalesQuery->sum('total_amount');

            $totalCashReturnsQuery = DB::table('returns');
            if ($branchId) $totalCashReturnsQuery->where('branch_id', $branchId);
            $totalCashReturns = $totalCashReturnsQuery->sum('refund_amount');

            $manualCashIn = 0;
            $manualCashOut = 0;

            if (Schema::hasTable('cash_books')) {
                $manualCashInQuery = DB::table('cash_books')->where('type', 'in');
                if ($branchId) $manualCashInQuery->where('branch_id', $branchId);
                $manualCashIn = $manualCashInQuery->sum('amount');

                $manualCashOutQuery = DB::table('cash_books')->where('type', 'out');
                if ($branchId) $manualCashOutQuery->where('branch_id', $branchId);
                $manualCashOut = $manualCashOutQuery->sum('amount');
            }

            $cashInHand = ($totalCashSales + $manualCashIn) - ($totalCashReturns + $manualCashOut);

            $totalBankBalance = DB::table('bank_accounts')->sum('current_balance');

            $pendingChequesCount = DB::table('cheques')
                ->where('status', 'pending')
                ->whereBetween('cheque_date', [Carbon::today()->toDateString(), Carbon::today()->addDays(3)->toDateString()])
                ->count();

            $lowStockQuery = DB::table('products')->whereColumn('qty', '<=', 'alert_qty');
            if ($branchId) $lowStockQuery->where('branch_id', $branchId);
            $lowStockCount = $lowStockQuery->count();

            $salesDataQuery = DB::table('sales')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
                ->whereBetween('created_at', [Carbon::today()->subDays(29), Carbon::today()->endOfDay()])
                ->groupBy('date');
            if ($branchId) $salesDataQuery->where('branch_id', $branchId);
            $salesData = $salesDataQuery->get();

            $chartDates = [];
            $chartTotals = [];

            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i)->toDateString();
                $chartDates[] = $date; 
                
                $sale = $salesData->firstWhere('date', $date);
                $chartTotals[] = $sale ? $sale->total : 0;
            }

            $topProductsQuery = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->select('product_name', DB::raw('SUM(qty) as total_qty'))
                ->groupBy('product_name')
                ->orderByDesc('total_qty')
                ->limit(5);
            if ($branchId) $topProductsQuery->where('sales.branch_id', $branchId);
            $topProducts = $topProductsQuery->get();

            $recentSalesQuery = DB::table('sales')
                ->join('users', 'sales.user_id', '=', 'users.id')
                ->select('sales.*', 'users.name as cashier_name')
                ->orderBy('sales.created_at', 'desc')
                ->limit(6);
            if ($branchId) $recentSalesQuery->where('sales.branch_id', $branchId);
            $recentSales = $recentSalesQuery->get();

            // =========================================================
            // Empire Hardware POS Dashboard Features
            // =========================================================
            $productsQuery = DB::table('products');
            if ($branchId) $productsQuery->where('branch_id', $branchId);
            $totalProducts = $productsQuery->count();

            $currentStockQuery = DB::table('products')->where('qty', '>', 0);
            if ($branchId) $currentStockQuery->where('branch_id', $branchId);
            $currentStockItemsCount = $currentStockQuery->count();

            $outOfStockQuery = DB::table('products')->where('qty', '<=', 0);
            if ($branchId) $outOfStockQuery->where('branch_id', $branchId);
            $outOfStockItemsCount = $outOfStockQuery->count();

            $currentStockPercentage = $totalProducts > 0 ? round(($currentStockItemsCount / $totalProducts) * 100) : 0;
            $outOfStockPercentage = $totalProducts > 0 ? round(($outOfStockItemsCount / $totalProducts) * 100) : 0;

            $alertsQuery = DB::table('products')
                ->select('id', 'product_name as name', 'barcode as code', 'qty as batches_sum_quantity', 'alert_qty')
                ->whereColumn('qty', '<=', 'alert_qty')
                ->orderBy('qty', 'asc')
                ->limit(5);
            if ($branchId) $alertsQuery->where('branch_id', $branchId);
            $outOfStockAlerts = $alertsQuery->get();

            $monthlyInvoicesQuery = DB::table('sales')->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            if ($branchId) $monthlyInvoicesQuery->where('branch_id', $branchId);
            $totalInvoices = $monthlyInvoicesQuery->count();

            $totalEmployees = DB::table('users')->count();
            $attendanceCount = DB::table('users')->where('is_active', 1)->count();

            $pendingReminders = DB::table('cheques')
                ->where('status', 'pending')
                ->whereBetween('cheque_date', [Carbon::today()->toDateString(), Carbon::today()->addDays(7)->toDateString()])
                ->get()
                ->map(function ($cheque) {
                    return (object)[
                        'type' => 'Cheque',
                        'invoice_no' => $cheque->cheque_number,
                        'name' => $cheque->customer_name ?: ($cheque->bank_name ?: 'Customer'),
                        'amount' => $cheque->amount,
                    ];
                });

            $fastMovingQuery = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->select('product_name as name', DB::raw('SUM(qty) as total_sold'))
                ->whereBetween('sales.created_at', [$startOfMonth, $endOfMonth]);
            if ($branchId) $fastMovingQuery->where('sales.branch_id', $branchId);
            $fastMovingItems = $fastMovingQuery->groupBy('product_name')->orderByDesc('total_sold')->limit(5)->get();

            $slowMovingQuery = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->select('product_name as name', DB::raw('SUM(qty) as total_sold'))
                ->whereBetween('sales.created_at', [$startOfMonth, $endOfMonth]);
            if ($branchId) $slowMovingQuery->where('sales.branch_id', $branchId);
            $slowMovingItems = $slowMovingQuery->groupBy('product_name')->orderBy('total_sold', 'asc')->limit(5)->get();

            if ($slowMovingItems->isEmpty()) {
                $slowMovingItems = DB::table('products')
                    ->select('product_name as name', DB::raw('0 as total_sold'))
                    ->limit(5)
                    ->get();
            }

            $monthlySalesLabels = [];
            $monthlySalesData = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $monthlySalesLabels[] = $month->format('M Y');
                $mQuery = DB::table('sales')
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year);
                if ($branchId) $mQuery->where('branch_id', $branchId);
                $monthlySalesData[] = $mQuery->sum('total_amount');
            }

            // 7-Day AI Sales Forecast (Linear Regression over last 30 days)
            $days = 30;
            $startDate = Carbon::now()->subDays($days);
            $dailySalesQuery = DB::table('sales')
                ->where('created_at', '>=', $startDate->format('Y-m-d 00:00:00'))
                ->select(DB::raw('DATE(created_at) as sale_date'), DB::raw('SUM(total_amount) as daily_total'))
                ->groupBy('sale_date')
                ->orderBy('sale_date');
            if ($branchId) $dailySalesQuery->where('branch_id', $branchId);
            $dailySales = $dailySalesQuery->get()->keyBy('sale_date');

            $sumX = 0; $sumY = 0; $sumXY = 0; $sumXX = 0;
            $n = $days;
            for ($i = 0; $i < $n; $i++) {
                $dateStr = $startDate->copy()->addDays($i)->format('Y-m-d');
                $x = $i + 1;
                $y = isset($dailySales[$dateStr]) ? $dailySales[$dateStr]->daily_total : 0;
                $sumX += $x;
                $sumY += $y;
                $sumXY += ($x * $y);
                $sumXX += ($x * $x);
            }
            $denominator = ($n * $sumXX) - ($sumX * $sumX);
            $m = $denominator == 0 ? 0 : (($n * $sumXY) - ($sumX * $sumY)) / $denominator;
            $b = ($sumY - ($m * $sumX)) / $n;

            $forecastLabels = [];
            $forecastData = [];
            for ($i = 1; $i <= 7; $i++) {
                $targetX = $n + $i;
                $predictedY = ($m * $targetX) + $b;
                $forecastLabels[] = Carbon::now()->addDays($i)->format('D, M d');
                $forecastData[] = round(max(0, $predictedY), 2);
            }

            return [
                'todaySales' => $todaySales,
                'todayProfit' => $todayProfit,
                'monthlySales' => $monthlySales,
                'monthlyProfit' => $monthlyProfit,
                'cashInHand' => $cashInHand,
                'totalBankBalance' => $totalBankBalance,
                'pendingChequesCount' => $pendingChequesCount,
                'lowStockCount' => $lowStockCount,
                'chartDates' => $chartDates,
                'chartTotals' => $chartTotals,
                'topProducts' => $topProducts,
                'recentSales' => $recentSales,
                // Empire POS variables
                'totalProducts' => $totalProducts,
                'currentStockItemsCount' => $currentStockItemsCount,
                'outOfStockItemsCount' => $outOfStockItemsCount,
                'currentStockPercentage' => $currentStockPercentage,
                'outOfStockPercentage' => $outOfStockPercentage,
                'outOfStockAlerts' => $outOfStockAlerts,
                'totalInvoices' => $totalInvoices,
                'totalEmployees' => $totalEmployees,
                'attendanceCount' => $attendanceCount,
                'pendingReminders' => $pendingReminders,
                'fastMovingItems' => $fastMovingItems,
                'slowMovingItems' => $slowMovingItems,
                'monthlySalesLabels' => $monthlySalesLabels,
                'monthlySalesData' => $monthlySalesData,
                'forecastLabels' => $forecastLabels,
                'forecastData' => $forecastData,
            ];
        });

        if ($request->ajax()) {
            return response()->json($data);
        }

        return view('home', $data);
    }

    public function updateLocation(Request $request)
    {
        if (auth()->check()) {
            DB::table('sessions')
                ->where('id', session()->getId())
                ->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'User not logged in']);
    }
}
