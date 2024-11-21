<?php

namespace App\Policies;

use App\Models\Training;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage_trainings');
    }

    public function update(User $user, Training $training): bool
    {
        return $user->hasPermissionTo('manage_trainings');
    }

    public function delete(User $user, Training $training): bool
    {
        return $user->hasPermissionTo('manage_trainings');
    }
}