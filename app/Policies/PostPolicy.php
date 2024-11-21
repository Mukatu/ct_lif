<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('publish_posts');
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->hasPermissionTo('manage_posts');
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->hasPermissionTo('manage_posts');
    }
}