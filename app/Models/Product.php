<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'purchase_price', 'sale_price', 'day_price', 'night_price', 'weekend', 'bonus', 'store_id', 'category_id', 'brand_id', 'created_by'];

    public function getRouteKeyName() : string
    {
        return 'code';    
    }

    // MUTATORS

    // RELATIONSHIPS
    public function brand() : BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function store() : BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    
    /**
     * The user who creates the Product resource
     *
     * @return BelongsTo
     * 
     */
    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
