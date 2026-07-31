<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use App\Models\Supplier;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChequeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Cheque::with('supplier');

        if ($user->role !== 'admin') {
            $query->where('branch_id', $user->branch_id);
        }
        
        $cheques = $query->orderBy('cheque_date', 'asc')->get();
        $suppliers = Supplier::all(); 
        
        $banksQuery = BankAccount::query();
        if ($user->role !== 'admin') {
            $banksQuery->where('branch_id', $user->branch_id);
        }
        $banks = $banksQuery->get();
        
        return view('cheques.index', compact('cheques', 'suppliers', 'banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'cheque_number' => 'required',
            'amount' => 'required|numeric',
            'cheque_date' => 'required|date',
            'bank_name' => 'required'
        ]);

        $data = $request->all();
        $data['id'] = (string) Str::uuid();
        $data['branch_id'] = auth()->user()->branch_id;
        $data['status'] = 'pending';
        $data['realization_date'] = null;

        if ($request->is_supplier == '1' && $request->supplier_id) {
            $supplier = Supplier::find($request->supplier_id);
            $data['customer_name'] = $supplier->company_name;
        }

        Cheque::create($data);

        return redirect()->back()->with('success', 'Cheque added successfully!');
    }

    public function realize(Request $request)
    {
        $request->validate([
            'cheque_id' => 'required',
            'bank_account_id' => 'required'
        ]);

        return DB::transaction(function () use ($request) {
            $cheque = Cheque::findOrFail($request->cheque_id);
            if ($cheque->status === 'realized') {
                return redirect()->back()->with('error', 'Cheque is already realized!');
            }
            $bank = BankAccount::findOrFail($request->bank_account_id);

            $cheque->status = 'realized';
            $cheque->realization_date = now();
            $cheque->save();

            if ($cheque->type === 'received') {
                $bank->increment('current_balance', $cheque->amount);
            } else {
                $bank->decrement('current_balance', $cheque->amount);
            }

            DB::table('transactions')->insert([
                'id' => (string) Str::uuid(),
                'branch_id' => auth()->user()->branch_id,
                'type' => $cheque->type === 'received' ? 'deposit' : 'expense',
                'category' => 'Cheque Realization',
                'amount' => $cheque->amount,
                'payment_method' => 'bank',
                'bank_account_id' => $bank->id,
                'description' => "Cheque realized: #{$cheque->cheque_number} to {$bank->bank_name}",
                'transaction_date' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('cheques.index')->with('success', 'Cheque realized successfully!');
        });
    }
}
