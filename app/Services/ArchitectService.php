<?php

namespace App\Services;

use App\Http\Resources\ArchitectResource;
use App\Models\Architect;
use App\Models\User;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;

class ArchitectService
{
    public function storeArchitect(array $data): Architect|false
    {
        try {
            $architect = Architect::create($data);
            return $architect;
        } catch (Exception $e) {
            Log::error("Architect creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function updateArchitect(Architect $architect, array $data): bool
    {
        try {
            $architect->update($data);
            return true;
        } catch (Exception $e) {
            Log::error("Architect update failed for architect {$architect->id}." . $e->getMessage());
            return false;
        }
    }

    public function deleteArchitect(Architect $architect): bool {
        try {
            $architect->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Architect deletion failed for architect {$architect->id}." . $e->getMessage());
            return false;
        }
    }

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
