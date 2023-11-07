<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    protected $permissions;

    public function __construct()
    {
        $role = auth()->user()->roles->first();
        if ($role) {
            $this->permissions = $role->permissions->pluck('name')->toArray();
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array('View users', $this->permissions);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array('Create user', $this->permissions);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return in_array('Edit user', $this->permissions);
    }
}
