<?php

namespace App\Policies;

use App\Models\Files;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FilesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
  public function view(User $user, Files $file)
{
    return $user->id === $file->user_id || $file->can_view;
}

public function delete(User $user, Files $file)
{
    return $user->id === $file->user_id && $file->can_delete;
}

public function update(User $user, Files $file)
{
    return $user->id === $file->user_id && $file->can_update;
}

public function print(User $user, Files $file)
{
    return $user->id === $file->user_id || $file->can_print;
}
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Files $files): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Filess $files): bool
    {
        return false;
    }
}
