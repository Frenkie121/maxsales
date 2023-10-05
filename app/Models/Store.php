<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // ACCESSORS
    /**
     * Translate name automatically for reading
     *
     * @return Attribute
     * 
     */
    public function name() : Attribute {
        return Attribute::make(
            fn (string $name) => __($name)
        );
    }

    // RELATIONSHIPS
    /**
     * Many-To-Many relationship with User Model
     *
     * @return BelongsToMany
     * 
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
