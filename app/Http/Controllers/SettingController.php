<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $setting = DB::table('shop_settings')->first();
        $users = \App\Models\User::orderBy('name')->get();
        $branches = \Illuminate\Support\Facades\Schema::hasTable('branches') ? DB::table('branches')->orderBy('name')->get() : collect([]);
        $activeSessions = \Illuminate\Support\Facades\Schema::hasTable('sessions') ? DB::table('sessions')
            ->whereNotNull('user_id')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->leftJoin('branches', 'users.branch_id', '=', 'branches.id')
            ->select(
                'sessions.id as session_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'sessions.latitude', 
                'sessions.longitude', 
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.role',
                'users.is_banned',
                'branches.name as branch_name'
            )
            ->orderBy('sessions.last_activity', 'desc')
            ->get() : collect([]);
        $currentVersion = \Illuminate\Support\Facades\File::exists(base_path('version.txt')) 
            ? trim(\Illuminate\Support\Facades\File::get(base_path('version.txt'))) 
            : '2.5.0';
        $systemInfo = [
            'app_version' => $currentVersion,
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'db_connection' => config('database.default'),
            'server_os' => PHP_OS,
        ];
        return view('settings.general', compact('setting', 'users', 'branches', 'activeSessions', 'systemInfo'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'nullable|string|max:255',
            'shop_phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $setting = DB::table('shop_settings')->first();
        $logoPath = $setting->logo ?? null;

        if ($request->hasFile('logo')) {
            if ($logoPath && Storage::exists('public/' . $logoPath)) {
                Storage::delete('public/' . $logoPath);
            }

            $logoPath = $request->file('logo')->store('logos', 'public');
            
            // Sync to public icons
            $fullLogoPath = storage_path('app/public/' . $logoPath);
            if (File::exists($fullLogoPath)) {
                File::copy($fullLogoPath, public_path('favicon.ico'));
                File::copy($fullLogoPath, public_path('icons/icon-192.png'));
                File::copy($fullLogoPath, public_path('icons/icon-512.png'));
            }
        }

        if ($setting) {
            DB::table('shop_settings')->where('id', $setting->id)->update([
                'shop_name' => $request->shop_name,
                'shop_address' => $request->shop_address,
                'shop_phone' => $request->shop_phone,
                'logo' => $logoPath,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('shop_settings')->insert([
                'id' => (string) Str::uuid(),
                'shop_name' => $request->shop_name,
                'shop_address' => $request->shop_address,
                'shop_phone' => $request->shop_phone,
                'logo' => $logoPath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update manifest.json
        $manifestPath = public_path('manifest.json');
        if (File::exists($manifestPath)) {
            $manifest = json_decode(File::get($manifestPath), true);
            $manifest['name'] = $request->shop_name;
            $manifest['short_name'] = substr($request->shop_name, 0, 10);
            File::put($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT));
        }

        return back()->with('success', 'Settings Updated Successfully!');
    }
}
