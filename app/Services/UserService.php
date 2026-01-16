<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserService
{
    public function fetchArchitectRepsByUserRole(User $user): ResourceCollection
    {
        if ($user->isManagerOrAbove()) {
            return User::where('user_role_id', '>=',  UserRole::ARCHREP)
                ->orderBy('name')
                ->get()
                ->toResourceCollection();
        }

        return UserResource::collection([$user]);
    }
}
