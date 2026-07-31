<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Supplier;
use App\Models\ProductRoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Product::with(['branch', 'category', 'brand', 'supplier', 'rolls']);

        if ($user->role !== 'admin') {
            $query->where('branch_id', $user->branch_id);
        }

        if ($request->has('search') && !empty(trim($request->search))) {
            $searchTerms = array_filter(explode(' ', trim($request->search)));
            foreach ($searchTerms as $search) {
                $query->where(function($q) use ($search) {
                    $q->where('product_name', 'LIKE', "%{$search}%")
                      ->orWhere('barcode', 'LIKE', "%{$search}%")
                      ->orWhereHas('category', function($cat) use ($search) {
                          $cat->where('name', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('brand', function($br) use ($search) {
                          $br->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return response()->json(['products' => $products]);
        }
        
        $categories = Category::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();

        if ($user->role == 'admin') {
            $branches = Branch::all();
        } else {
            $branches = Branch::where('id', $user->branch_id)->get();
        }

        $branchNextBarcodes = [];
        foreach ($branches as $branch) {
            if ($branch->prefix) {
                $prefix = $branch->prefix;
                $lastProduct = Product::where('barcode', 'like', $prefix . '%')
                    ->orderByRaw("LENGTH(barcode) DESC")
                    ->orderBy('barcode', 'desc')
                    ->first();
                    
                if ($lastProduct) {
                    $lastNumber = (int) str_replace($prefix, '', $lastProduct->barcode);
                    $branchNextBarcodes[$branch->id] = $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $branchNextBarcodes[$branch->id] = $prefix . '0001';
                }
            } else {
                $branchNextBarcodes[$branch->id] = ''; 
            }
        }

        return view('inventory.products.index', compact('products', 'categories', 'brands', 'branches', 'suppliers', 'branchNextBarcodes'));
    }

    public function searchAjax(Request $request)
    {
        $query = $request->get('query');
        if(empty($query)) return response()->json([]);

        $searchTerms = array_filter(explode(' ', trim($query)));
        
        $productsQuery = Product::query();
        foreach ($searchTerms as $search) {
            $productsQuery->where(function($q) use ($search) {
                $q->where('product_name', 'LIKE', "%{$search}%")
                  ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        }

        $products = $productsQuery->select('id', 'product_name', 'barcode', 'selling_price', 'cost_price', 'category_id', 'brand_id', 'unit', 'qty')
                        ->limit(10)
                        ->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $branch_id = ($user->role == 'admin') ? $request->branch_id : $user->branch_id;

        if (!$branch_id) {
            return redirect()->back()->with('error', 'Branch selection is required!');
        }

        $barcode = $request->barcode;
        if (empty($barcode)) {
            $branch = Branch::find($branch_id);
            $prefix = $branch ? $branch->prefix : 'ITM';
            
            $lastProduct = Product::where('barcode', 'like', $prefix . '%')
                ->orderByRaw("LENGTH(barcode) DESC")
                ->orderBy('barcode', 'desc')
                ->first();
                
            if ($lastProduct) {
                $lastNumber = (int) str_replace($prefix, '', $lastProduct->barcode);
                $barcode = $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $barcode = $prefix . '0001';
            }
        }

        $existingProduct = Product::where('barcode', $barcode)->first();

        if ($existingProduct) {
            if (trim($existingProduct->product_name) === trim($request->product_name)) {
                $newQty = $existingProduct->qty + $request->qty;
                
                $existingProduct->update([
                    'qty' => $newQty,
                    'cost_price' => $request->cost_price,
                    'selling_price' => $request->selling_price,
                ]);

                AuditLog::record('Update Stock', "Stock updated for {$existingProduct->product_name} (Barcode: {$barcode}). New Quantity: {$newQty}");

                return redirect()->back()->with('success', 'Existing Product Found! Stock Updated. New Qty: ' . $newQty);
            } else {
                return redirect()->back()
                    ->withErrors(['barcode' => 'This Barcode is already used!'])
                    ->withInput();
            }
        }

        $request->validate([
            'product_name' => 'required|string|max:255',
            'selling_price' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'barcode' => 'unique:products,barcode',
        ]);

        $data = $request->all();
        $data['branch_id'] = $branch_id;
        $data['barcode'] = $barcode; 

        if ($request->has('has_warranty')) {
            $data['has_warranty'] = 1;
        } else {
            $data['has_warranty'] = 0;
            $data['warranty_months'] = 0;
        }

        $product = Product::create($data);

        if ($request->has('roll_length')) {
            foreach ($request->roll_length as $key => $length) {
                if (!empty($length) && !empty($request->roll_price[$key])) {
                    ProductRoll::create([
                        'product_id' => $product->id,
                        'roll_length' => $length,
                        'roll_price' => $request->roll_price[$key]
                    ]);
                }
            }
        }

        AuditLog::record('Create Product', "New product {$product->product_name} (Barcode: {$barcode}) was added to the system.");

        return redirect()->back()->with('success', 'New Product Added Successfully!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        if (Auth::user()->role !== 'admin' && $product->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized Action');
        }

        $data = $request->all();

        if (!$request->has('has_warranty')) {
            $data['has_warranty'] = 0;
            $data['warranty_months'] = 0;
        } else {
            $data['has_warranty'] = 1;
        }

        $product->update($data);

        if (in_array($product->unit, ['meter', 'feet', 'l', 'ml', 'kg', 'gram', 'bottle'])) {
            ProductRoll::where('product_id', $product->id)->delete();
            if ($request->has('roll_length')) {
                foreach ($request->roll_length as $key => $length) {
                    if (!empty($length) && !empty($request->roll_price[$key])) {
                        ProductRoll::create([
                            'product_id' => $product->id,
                            'roll_length' => $length,
                            'roll_price' => $request->roll_price[$key]
                        ]);
                    }
                }
            }
        }
        
        AuditLog::record('Update Product', "Details of {$product->product_name} (Barcode: {$product->barcode}) were updated.");

        return redirect()->back()->with('success', 'Product Updated Successfully!');
    }

    public function printBarcode(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $qty = $request->print_qty;
        
        AuditLog::record('Print Barcode', "Printed {$qty} barcodes for the product {$product->product_name}.");

        return view('inventory.products.barcode_print', compact('product', 'qty'));
    }

    public function addStock(Request $request, $id)
    {
        $request->validate([
            'added_qty' => 'required|numeric|min:1',
        ]);

        $product = Product::findOrFail($id);
        
        $product->increment('qty', $request->added_qty);

        AuditLog::record('Add Stock', "Cashier added {$request->added_qty} units to the stock of {$product->product_name} (Barcode: {$product->barcode}).");

        return back()->with('success', "Stock updated! {$request->added_qty} units added to {$product->product_name}.");
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if (Auth::user()->role !== 'admin' && $product->branch_id !== Auth::user()->branch_id) {
            abort(403);
        }
        
        $productName = $product->product_name; 
        $productBarcode = $product->barcode;   
        
        $product->delete();
        
        AuditLog::record('Delete Product', "Product {$productName} (Barcode: {$productBarcode}) was deleted from the system.");

        return redirect()->back()->with('success', 'Product Deleted!');
    }
}
