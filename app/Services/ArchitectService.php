<?php

namespace App\Services;

use App\Http\Resources\ArchitectResource;
use App\Models\Architect;
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArchitectService
{
    public function fetchArchitects(?User $user = null, ?string $userId = null): ResourceCollection
    {
        if ($user && $userId !== null) {
            $architects = Architect::where('architect_rep_id', $userId)
                ->get()
                ->toResourceCollection();

            return $architects;
        }

        if ($user && $user->isAdministrator()) {
            $architects = Architect::all()
                ->toResourceCollection();

            return $architects;
        }

        return ArchitectResource::collection(['User is not logged in.']);
    }
}
