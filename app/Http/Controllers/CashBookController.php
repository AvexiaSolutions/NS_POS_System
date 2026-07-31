<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashBookController extends Controller
{
    public function index()
    {

        $cashSales = DB::table('sales')->where('payment_method', 'cash')->sum('total_amount');
        $cashReturns = DB::table('returns')->sum('refund_amount');

        $manualCashIn = DB::table('cash_books')->where('type', 'in')->sum('amount');
        $manualCashOut = DB::table('cash_books')->where('type', 'out')->sum('amount');

        $currentCashInHand = ($cashSales + $manualCashIn) - ($cashReturns + $manualCashOut);

        $transactions = DB::table('cash_books')->orderBy('created_at', 'desc')->get();
        $banks = DB::table('bank_accounts')->get();

        return view('cashbook.index', compact('currentCashInHand', 'transactions', 'banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'category' => 'required',
            'description' => 'required'
        ]);

        $type = in_array($request->category, ['opening', 'from_bank', 'other']) ? 'in' : 'out';

        if ($request->category === 'from_bank') {
            DB::table('bank_accounts')->where('id', $request->bank_account_id)->decrement('current_balance', $request->amount);
        }

        DB::table('cash_books')->insert([
            'description' => $request->description,
            'type' => $type,
            'category' => $request->category,
            'amount' => $request->amount,
            'bank_account_id' => $request->bank_account_id ?? null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return back()->with('success', 'Cash transaction recorded successfully!');
    }

    public function transferToBank(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1', 'bank_account_id' => 'required']);

        DB::table('bank_accounts')->where('id', $request->bank_account_id)->increment('current_balance', $request->amount);

        DB::table('cash_books')->insert([
            'description' => 'Deposited to Bank (Cash Transferred)',
            'type' => 'out',
            'category' => 'to_bank',
            'amount' => $request->amount,
            'bank_account_id' => $request->bank_account_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return back()->with('success', 'Cash successfully deposited to the bank!');
    }
}
