<?php

namespace App\Services;

use App\Models\Architect;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ArchitectService
{
    public function fetchArchitects(?User $user = null, ?string $userId = null): JsonResponse
    {
        if ($user && $userId !== null) {
            $architects = Architect::where('architect_rep_id', $userId)
                ->with(['architectType:id,architect_type_desc', 'architectRep:id,name'])
                ->get();

            return response()->json($architects->toArray());
        }

        if ($user && $user->isAdministrator()) {
            $architects = Architect::with(['architectType:id,architect_type_desc', 'architectRep:id,name'])
                ->get();

            return response()->json($architects->toArray());
        }

        return response()->json(['User ID is required.']);
    }
}
