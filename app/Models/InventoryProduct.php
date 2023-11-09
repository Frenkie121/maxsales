<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryProduct extends Model
{
    use HasFactory;

    protected $table = 'inventory_product';

    protected $fillable = ['inventory_id', 'product_id', 'theoretical_quantity', 'physical_quantity', 'gap'];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
