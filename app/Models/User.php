<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'password',
        'login',
        'phone', // Phone Number
        'location', // Residence location
        'nic', // National Identity Card Number
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean'
    ];

    // 
    public function getRouteKeyName() : string
    {
        return 'slug';    
    }

    // MUTATORS
    public function setLoginAttribute($value): void
    {
        $this->attributes['login'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // public function setPasswordAttribute($value): void
    // {
    //     $this->attributes['password'] = Hash::make($value);
    // }

    // public function password(): Attribute
    // {
    //     return Attribute::set(fn ($value) => Hash::make($value));
    // }

    // RELATIONSHIPS
    /**
     * Many-To-Many relationship with Store Model
     *
     * @return BelongsToMany
     * 
     */
    public function stores() : BelongsToMany
    {
        return $this->belongsToMany(Store::class);
    }
}