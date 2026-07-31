<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;

class AuditLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'device_info',
        'location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record($action, $description = null)
    {
        $request = request();

        $session = DB::table('sessions')->where('id', session()->getId())->first();
                    
        $location = null;
        if ($session && $session->latitude && $session->longitude) {
            $location = $session->latitude . ',' . $session->longitude;
        }

        self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
            'device_info' => $request->header('User-Agent'),
            'location' => $location
        ]);
    }
}
