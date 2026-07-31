<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Product extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'branch_id',
        'category_id',
        'brand_id',
        'supplier_id',
        'barcode',
        'product_name',
        'cost_price',
        'selling_price',
        'wholesale_price',
        'discount_price',
        'discount_type',
        'qty',
        'unit',
        'alert_qty',
        'has_warranty',
        'warranty_months'
    ];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }

    public function rolls()
    {
        return $this->hasMany(ProductRoll::class);
    }
}
