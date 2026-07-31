<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Cheque extends Model
{
    use HasUuid;

    protected $fillable = [
        'branch_id', 'type', 'cheque_number', 'bank_name', 'bank_branch', 
        'account_no', 'amount', 'cheque_date', 'realization_date', 
        'status', 'supplier_id', 'customer_name', 'is_supplier', 'note'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
