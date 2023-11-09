<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'user_id'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function store() : BelongsTo
    {
        return $this->belongsTo(Store::class);    
    }
    
    /**
     * Help to handle Repeater fields when save inventory
     *
     * @return HasMany
     * 
     */
    public function inventoryProducts(): HasMany
    {
        return $this->hasMany(InventoryProduct::class);
    }
}
