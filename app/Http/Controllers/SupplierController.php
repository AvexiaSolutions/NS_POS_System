<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Branch;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('branch')->orderBy('created_at', 'desc')->get();
        $branches = Branch::all();
        return view('suppliers.index', compact('suppliers', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        Supplier::create($request->all());

        return redirect()->back()->with('success', 'Supplier added successfully!');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $request->validate([
            'company_name' => 'required|string|max:255',
        ]);

        $supplier->update($request->all());

        return redirect()->back()->with('success', 'Supplier updated successfully!');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        if($supplier->credit_balance > 0) {
            return redirect()->back()->with('error', 'Cannot delete. Outstanding credit balance exists!');
        }
        
        $supplier->delete();
        return redirect()->back()->with('success', 'Supplier deleted successfully!');
    }
}
