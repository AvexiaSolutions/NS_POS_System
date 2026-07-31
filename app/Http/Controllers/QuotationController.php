<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class QuotationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $productsQuery = DB::table('products');
        if ($user->role !== 'admin') {
            $productsQuery->where('branch_id', $user->branch_id);
        }
        $products = $productsQuery->get();
        
        $query = DB::table('quotations')->orderBy('created_at', 'desc');
        if ($user->role !== 'admin') {
            $query->where('branch_id', $user->branch_id);
        }
        $quotations = $query->get();

        return view('quotations.index', compact('products', 'quotations'));
    }

    public function store(Request $request)
    {
        try {
            $id = (string) Str::uuid();
            $isFake = $request->is_fake_bill ? true : false;
            
            DB::table('quotations')->insert([
                'id' => $id,
                'branch_id' => auth()->user()->branch_id,
                'customer_name' => $request->customer_name,
                'items' => json_encode($request->items),
                'total_amount' => (float) str_replace(',', '', $request->total_amount),
                'is_fake_bill' => $isFake,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $docType = $isFake ? 'Fake Bill' : 'Quotation';
            \App\Models\AuditLog::record('Create Quotation', "A new {$docType} (Rs. {$request->total_amount}) was created for {$request->customer_name}.");

            return response()->json(['status' => 'success', 'id' => $id]);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to save: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $quotation = DB::table('quotations')->where('id', $id)->first();
        
        if ($quotation) {
            $quotation->items = json_decode($quotation->items);
            return response()->json($quotation);
        }
        
        return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);
    }

    public function update(Request $request, $id)
    {
        try {
            $isFake = $request->is_fake_bill ? true : false;

            DB::table('quotations')->where('id', $id)->update([
                'customer_name' => $request->customer_name,
                'items' => json_encode($request->items),
                'total_amount' => (float) str_replace(',', '', $request->total_amount),
                'is_fake_bill' => $isFake,
                'updated_at' => now(),
            ]);

            $docType = $isFake ? 'Fake Bill' : 'Quotation';
            \App\Models\AuditLog::record('Update Quotation', "The {$docType} record for {$request->customer_name} was updated.");

            return response()->json(['status' => 'success', 'id' => $id]);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $quotation = DB::table('quotations')->where('id', $id)->first();
            
            if ($quotation) {
                $customerName = $quotation->customer_name;
                $docType = $quotation->is_fake_bill ? 'Fake Bill' : 'Quotation';
                
                DB::table('quotations')->where('id', $id)->delete();

                \App\Models\AuditLog::record('Delete Quotation', "The {$docType} record for {$customerName} was deleted from the system.");
            }

            return response()->json(['status' => 'success']);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete: ' . $e->getMessage()]);
        }
    }

    public function print($id)
    {
        $quote = DB::table('quotations')->where('id', $id)->first();
        
        if (!$quote) {
            return "Record Not Found!";
        }

        $items = json_decode($quote->items);
        $shop = DB::table('shop_settings')->first();
        
        $docType = $quote->is_fake_bill ? 'Fake Bill' : 'Quotation';
        \App\Models\AuditLog::record('Print Quotation', "The {$docType} record for {$quote->customer_name} was printed.");

        return view('quotations.print', compact('quote', 'items', 'shop'));
    }
}
