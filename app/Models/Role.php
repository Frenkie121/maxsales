<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
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
     * One-To-Many relationship with User (One Role "has" Many Users)
     *
     * @return HasMany
     * 
     */
    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }
}
