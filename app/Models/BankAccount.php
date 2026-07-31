<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BankAccount extends Model {
    protected $fillable = ['branch_id', 'bank_name', 'account_name', 'account_number', 'current_balance', 'is_primary'];
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot() {
        parent::boot();
        static::creating(fn ($model) => $model->id = (string) Str::uuid());
    }
}
