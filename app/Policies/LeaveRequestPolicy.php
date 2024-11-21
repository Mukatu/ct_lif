<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent voir leurs propres demandes
    }

    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->id === $leaveRequest->user_id || $user->hasRole('hr');
    }

    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent créer des demandes
    }

    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasRole('hr'); // Seuls les RH peuvent mettre à jour le statut
    }

    public function cancel(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->id === $leaveRequest->user_id && $leaveRequest->status === 'pending';
    }
}