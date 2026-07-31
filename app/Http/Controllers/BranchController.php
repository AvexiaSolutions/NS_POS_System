<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        return redirect()->route('settings.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prefix' => 'required|string|max:5|unique:branches,prefix',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        DB::table('branches')->insert([
            'id' => (string) Str::uuid(), 
            'name' => $request->name,
            'prefix' => strtoupper($request->prefix), 
            'address' => $request->address,
            'phone' => $request->phone,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'New Branch Created Successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prefix' => 'required|string|max:5|unique:branches,prefix,' . $id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        DB::table('branches')->where('id', $id)->update([
            'name' => $request->name,
            'prefix' => strtoupper($request->prefix),
            'address' => $request->address,
            'phone' => $request->phone,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Branch Updated Successfully!');
    }

    public function destroy($id)
    {
        DB::table('branches')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Branch Deleted!');
    }
}
