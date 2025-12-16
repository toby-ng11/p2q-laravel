<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Architect;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArchitectPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }

    public function isOwner(User $user, Architect $architect): bool
    {
        return $architect->architect_rep_id === $user->id;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->user_role_id->atLeast(UserRole::SALES);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Architect $architect): bool
    {
        if ($user->isManagerOrAbove()) {
            return true;
        }

        if ($user->user_role_id === UserRole::SALES) {
            return true;
        }

        return $user->user_role_id === UserRole::ARCHREP &&
            $this->isOwner($user, $architect);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->user_role_id->atLeast(UserRole::ARCHREP);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Architect $architect): bool
    {
        if ($user->isManagerOrAbove()) {
            return true;
        }

        return $user->user_role_id === UserRole::ARCHREP &&
            $this->isOwner($user, $architect);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Architect $architect): bool
    {
        if ($user->isManagerOrAbove()) {
            return true;
        }

        return $user->user_role_id === UserRole::ARCHREP &&
            $this->isOwner($user, $architect);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Architect $architect): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Architect $architect): bool
    {
        return false;
    }
}
