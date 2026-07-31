<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Supplier extends Model
{
    protected $fillable = [
        'branch_id', 
        'company_name', 
        'contact_person', 
        'phone', 
        'email', 
        'address', 
        'credit_balance'
    ];
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->credit_balance)) {
                $model->credit_balance = 0.00;
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
