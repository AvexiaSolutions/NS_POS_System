<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Branch;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            $categories = Category::with('branch')->orderBy('created_at', 'desc')->get();
            $brands = Brand::with('branch')->orderBy('created_at', 'desc')->get();
            $branches = Branch::all();
        } else {
            $categories = Category::where('branch_id', $user->branch_id)->orderBy('created_at', 'desc')->get();
            $brands = Brand::where('branch_id', $user->branch_id)->orderBy('created_at', 'desc')->get();
            $branches = Branch::where('id', $user->branch_id)->get();
        }
            
        return redirect()->route('products.index', ['tab' => 'categories']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50'
        ]);

        Category::create([
            'branch_id' => Auth::user()->role == 'admin' ? $request->branch_id : Auth::user()->branch_id,
            'name' => $request->name,
            'code' => $request->code
        ]);

        \App\Models\AuditLog::record('Create Category', "New category '{$request->name}' was added to the system.");

        return redirect()->back()->with('success', 'Category Created Successfully!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $category->update([
            'branch_id' => Auth::user()->role == 'admin' ? $request->branch_id : Auth::user()->branch_id,
            'name' => $request->name,
            'code' => $request->code
        ]);

        \App\Models\AuditLog::record('Update Category', "Details of the category '{$request->name}' were updated.");

        return redirect()->back()->with('success', 'Category Updated!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $categoryName = $category->name; 
        
        $category->delete();
        
        \App\Models\AuditLog::record('Delete Category', "Category '{$categoryName}' was deleted from the system.");

        return redirect()->back()->with('success', 'Category Deleted!');
    }
}
