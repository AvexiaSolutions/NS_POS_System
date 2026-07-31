<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Brand::create([
            'branch_id' => Auth::user()->role == 'admin' ? $request->branch_id : Auth::user()->branch_id,
            'name' => $request->name
        ]);

        \App\Models\AuditLog::record('Create Brand', "New brand '{$request->name}' was added to the system.");

        return redirect()->back()->with('success', 'Brand / Company Added Successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $brand = Brand::findOrFail($id);
        
        $brand->update([
            'branch_id' => Auth::user()->role == 'admin' ? $request->branch_id : Auth::user()->branch_id,
            'name' => $request->name
        ]);

        \App\Models\AuditLog::record('Update Brand', "Brand '{$brand->name}' was updated in the system.");

        return redirect()->back()->with('success', 'Brand / Company Updated Successfully!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brandName = $brand->name;
        
        $brand->delete();

        \App\Models\AuditLog::record('Delete Brand', "Brand '{$brandName}' was deleted from the system.");

        return redirect()->back()->with('success', 'Brand Deleted!');
    }
}
