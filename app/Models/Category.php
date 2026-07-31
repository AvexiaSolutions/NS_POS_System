<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class Category extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'branch_id',
        'name',
        'code'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
