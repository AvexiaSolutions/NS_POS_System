<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return redirect()->route('settings.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,cashier',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $userId = (string) Str::uuid();

        $user = User::create([
            'id' => $userId,
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => 1,
        ]);

        DB::table('user_settings')->insert([
            'user_id' => $userId,
            'theme_mode' => 'light',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'New User Created Successfully!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,cashier',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        
        if ($user->role === 'admin' && $request->role !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'System Restricted: You cannot change the role of the only remaining Admin!');
            }
        }

       
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->branch_id = $request->branch_id;
        
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'User Updated Successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        
        if (auth()->id() == $id) {
            return back()->with('error', 'System Restricted: You cannot delete your own account!');
        }

        
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'System Restricted: You cannot delete the only remaining Admin account!');
            }
        }

        $firstAdmin = User::orderBy('created_at', 'asc')->first();
        if ($firstAdmin && $firstAdmin->id == $id) {
            return back()->with('error', 'System Restricted: You cannot delete the primary System Admin!');
        }
        
        DB::table('user_settings')->where('user_id', $id)->delete();
        $user->delete();
        
        return back()->with('success', 'User Deleted Successfully!');
    }
}
