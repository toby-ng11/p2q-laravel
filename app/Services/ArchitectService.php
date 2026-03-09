<?php

namespace App\Services;

use App\Http\Resources\ArchitectResource;
use App\Models\Architect;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;

class ArchitectService
{
    public function storeArchitect(array $data): Architect
    {
        try {
            return Architect::create($data);
        } catch (Exception $e) {
            Log::error("Architect creation failed", ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    public function updateArchitect(Architect $architect, array $data): bool
    {
        try {
            return $architect->update($data);
        } catch (Exception $e) {
            Log::error("Architect update failed #{$architect->id}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function deleteArchitect(Architect $architect): bool
    {
        try {
            return (bool) $architect->delete();
        } catch (Exception $e) {
            Log::error("Architect deletion failed #{$architect->id}", ['error' => $e->getMessage()]);
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

    public function architectGrowthCalculation(): array
    {
        $lastMonth = Carbon::now()->subMonth()->month;
        $thisMonth = Carbon::now()->month;

        $lastMonthCount = Architect::query()->whereMonth('created_at', $lastMonth)->count();
        $thisMonthCount = Architect::query()->whereMonth('created_at', $thisMonth)->count();
        $totalArchitect = Architect::count();

        $growthPercentage = $lastMonthCount > 0 ?
            round(($thisMonthCount - $lastMonthCount) * 100 / $lastMonthCount, 1):
            null;

        $statement = match (true) {
            $growthPercentage > 10 => 'Strong growth this month',
            $growthPercentage > 0  => 'Trending up this month',
            $growthPercentage < 0  => 'Down this month',
            $growthPercentage === null && $thisMonthCount > 0 => 'Not enough data for trending',
            default                => 'No growth this month',
        };

        return [
            'total_architect' => $totalArchitect,
            'new_architect_this_month' => $thisMonthCount,
            'growth_percentage' => $growthPercentage,
            'statement' => $statement,
        ];
    }
}
