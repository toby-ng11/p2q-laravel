<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OpportunityPolicy
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

    public function isOwner(User $user, Opportunity $opportunity): bool
    {
        return $opportunity->created_by === $user->id;
    }

    public function isAssigned(User $user, Opportunity $opportunity): bool
    {
        return $opportunity->architect->architect_rep_id === $user->id;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isManagerOrAbove() || $user->user_role_id === UserRole::SALES;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Opportunity $opportunity): bool
    {
        if ($user->isManagerOrAbove()) {
            return true;
        }

        if ($user->user_role_id === UserRole::SALES) {
            return true;
        }

        if (
            $user->user_role_id === UserRole::ARCHREP &&
            ($this->isOwner($user, $opportunity) || $this->isAssigned($user, $opportunity))
        ) {
            return true;
        }

        return false;
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
    public function update(User $user, Opportunity $opportunity): bool
    {
        if ($user->isManagerOrAbove()) {
            return true;
        }

        if (
            $user->user_role_id === UserRole::ARCHREP &&
            ($this->isOwner($user, $opportunity) || $this->isAssigned($user, $opportunity))
        ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Opportunity $opportunity): bool
    {
        if ($user->isManagerOrAbove()) {
            return true;
        }

        if (
            $user->user_role_id === UserRole::ARCHREP &&
            ($this->isOwner($user, $opportunity) || $this->isAssigned($user, $opportunity))
        ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Opportunity $opportunity): bool
    {
        if ($user->isManagerOrAbove()) {
            return true;
        }

        if (
            $user->user_role_id === UserRole::ARCHREP &&
            ($this->isOwner($user, $opportunity) || $this->isAssigned($user, $opportunity))
        ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Opportunity $opportunity): bool
    {
        if ($user->isManagerOrAbove()) {
            return true;
        }

        if (
            $user->user_role_id === UserRole::ARCHREP &&
            ($this->isOwner($user, $opportunity) || $this->isAssigned($user, $opportunity))
        ) {
            return true;
        }

        return false;
    }
}
