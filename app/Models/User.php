<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
    ];

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

    /**
     * Reverse One-To-Many relationship with Role (One User "belongs To" One Role)
     *
     * @return BelongsTo
     * 
     */
    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
