<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent voir les documents
    }

    public function create(User $user): bool
    {
        return $user->hasRole('hr'); // Seuls les RH peuvent ajouter des documents
    }

    public function update(User $user, Document $document): bool
    {
        return $user->hasRole('hr');
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->hasRole('hr');
    }

    public function download(User $user, Document $document): bool
    {
        return true; // Tous les utilisateurs peuvent télécharger les documents
    }
}