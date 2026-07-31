<?php
namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankExpenseController extends Controller {
    
    public function index() {
        $banks = BankAccount::orderBy('is_primary', 'desc')->get();
        $expenses = Expense::with('bankAccount')->orderBy('expense_date', 'desc')->get();
        return view('finance.index', compact('banks', 'expenses'));
    }

    public function storeBank(Request $request) {
        $data = $request->all();
        $data['branch_id'] = auth()->user()->branch_id;

        if($request->is_primary) {
            BankAccount::where('is_primary', true)->update(['is_primary' => false]);
        }
        
        BankAccount::create($data);
        return redirect()->back()->with('success', 'Bank account added successfully!');
    }

    public function updateBank(Request $request, $id) {
        $bank = BankAccount::findOrFail($id);

        if($request->is_primary) {
            BankAccount::where('is_primary', true)->where('id', '!=', $id)->update(['is_primary' => false]);
        }

        $bank->update([
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'is_primary' => $request->has('is_primary') ? true : false,
        ]);

        return redirect()->back()->with('success', 'Bank account updated successfully!');
    }

    public function storeDeposit(Request $request) {
        $bank = BankAccount::findOrFail($request->bank_account_id);

        $bank->increment('current_balance', $request->amount);

        DB::table('transactions')->insert([
            'id' => (string) Str::uuid(),
            'branch_id' => auth()->user()->branch_id,
            'type' => 'deposit',
            'category' => $request->deposit_type,
            'amount' => $request->amount,
            'payment_method' => 'bank',
            'bank_account_id' => $request->bank_account_id,
            'description' => $request->note,
            'transaction_date' => $request->deposit_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Amount deposited successfully!');
    }

    public function storeExpense(Request $request) {
        $data = $request->all();
        $data['branch_id'] = auth()->user()->branch_id;
        
        Expense::create($data);

        if($request->bank_account_id) {
            $bank = BankAccount::find($request->bank_account_id);
            $bank->decrement('current_balance', $request->amount);
        }

        DB::table('transactions')->insert([
            'id' => (string) Str::uuid(),
            'branch_id' => auth()->user()->branch_id,
            'type' => 'expense',
            'category' => $request->category,
            'amount' => $request->amount,
            'payment_method' => $request->bank_account_id ? 'bank' : 'cash',
            'bank_account_id' => $request->bank_account_id,
            'description' => $request->description,
            'transaction_date' => $request->expense_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Expense recorded successfully!');
    }
}
