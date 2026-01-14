<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArchitectRequest;
use App\Http\Requests\UpdateArchitectRequest;
use App\Models\Architect;
use App\Services\ArchitectService;
use Exception;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ArchitectController extends Controller implements HasMiddleware
{
    public function __construct(protected ArchitectService $architectService) {}

    /**
     * Get the middleware that should be assigned to the controller.
     */
    #[\Override]
    public static function middleware()
    {
        return [
            new Middleware(HandlePrecognitiveRequests::class, only: ['store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $userId = $request->input('user_id', null);

        return $this->architectService->fetchArchitects($user, $userId);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): RedirectResponse
    {
        return to_route('dashboard.architect');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArchitectRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $architect = new Architect($validated);
        $result = $architect->save();

        if (! $result) {
            return back()->withErrors('Please contact admin to resolve the problem.');
        }

        Inertia::flash('success', 'Architect created successfully.');

        return to_route('architects.edit', $architect);
    }

    /**
     * Display the specified resource.
     */
    public function show(Architect $architect): RedirectResponse
    {
        return to_route('architects.edit', $architect);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Architect $architect): Response
    {
        Gate::authorize('view', $architect);
        return Inertia::render('architects/edit', ['architect' => $architect]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArchitectRequest $request, Architect $architect): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        if ($user && ! $user->isManagerOrAbove()) {
            /** @var \Illuminate\Support\ValidatedInput */
            $data = $request->safe();
            $validated = $data->except('architect_rep_id');
        }

        $result = $architect->update($validated);

        if ($result) {
            Inertia::flash('success', 'Architect saved!');

            return back();
        }

        Inertia::flash('error', 'Something went wrong, please try again.');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Architect $architect): RedirectResponse
    {
        Gate::authorize('delete', $architect);
        try {
            $architect->delete();

            Inertia::flash('success', 'Architect deleted!');
            return back();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Inertia::flash('error', 'Deletion failed.');
            return back();
        }
    }
}
