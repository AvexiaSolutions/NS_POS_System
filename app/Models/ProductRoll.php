<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRoll extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'roll_length',
        'roll_price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
