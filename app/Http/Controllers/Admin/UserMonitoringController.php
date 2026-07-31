<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserMonitoringController extends Controller
{
    public function index()
    {
        $activeSessions = DB::table('sessions')
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
            ->get();

        return view('admin.monitoring.index', compact('activeSessions'));
    }

    public function toggleBan(Request $request, User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot block your own account!');
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        if ($user->is_banned) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        $status = $user->is_banned ? 'blocked.' : 'unblocked.';
        return back()->with('success', "User account {$status}");
    }

    public function getUserHistory($userId)
    {
        $logs = AuditLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get(['action', 'description', 'created_at']);

        return response()->json($logs);
    }
}
