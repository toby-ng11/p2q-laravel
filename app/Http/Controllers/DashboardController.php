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

    public function totalArchitects(): JsonResponse
    {
        return response()->json($this->architectService->architectGrowthCalculation());
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
