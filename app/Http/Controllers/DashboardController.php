<?php

namespace App\Http\Controllers;

use App\Services\ArchitectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(protected ArchitectService $architectService) {}

    public function home(): Response
    {
        return Inertia::render('dashboard/home');
    }

    public function admin(): Response
    {
        return Inertia::render('dashboard/admin');
    }

    public function architect(): Response
    {
        return Inertia::render('dashboard/architect');
    }

    public function totalArchitects(Request $request): JsonResponse
    {
        $user = $request->user();
        $userId = $request->input('user_id', null);

        if ($user) {
            if ($userId === null && $user->isManagerOrAbove()) {
                return response()->json($this->architectService->architectGrowthCalculation());
            } else {
                return response()->json($this->architectService->architectGrowthCalculation($userId));
            }
        }

        return response()->json('Something wrong in the backend.');
    }

    public function opportunity(): Response
    {
        return Inertia::render('dashboard/opportunity');
    }

    public function project(): Response
    {
        return Inertia::render('dashboard/project');
    }

    public function quote(): Response
    {
        return Inertia::render('dashboard/quote');
    }
}
