<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureRequestIsJson;
use App\Http\Requests\StoreSpecifierRequest;
use App\Http\Requests\UpdateSpecifierRequest;
use App\Models\Architect;
use App\Models\Specifier;
use App\Services\SpecifierService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ArchitectSpecifierController extends Controller implements HasMiddleware
{
    public function __construct(protected SpecifierService $specifierService) {}

    /**
     * Get the middleware that should be assigned to the controller.
     */
    #[\Override]
    public static function middleware()
    {
        return [
            new Middleware(EnsureRequestIsJson::class, only: ['index']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Architect $architect): ResourceCollection
    {
        return $architect->specifiers()->get()->toResourceCollection();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpecifierRequest $request, Architect $architect): RedirectResponse
    {
        $validated = $request->validated();
        $result = $this->specifierService->storeSpecifier($architect, $validated);

        if ($result) {
            Inertia::flash('success', 'Specifier created!');
            return back();
        } else {
            Inertia::flash('error', 'Creation failed. Please try again later or contact an admin.');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Architect $architect)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Architect $architect)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpecifierRequest $request, Architect $architect, Specifier $specifier): RedirectResponse
    {
        $validated = $request->validated();
        $result = $this->specifierService->updateSpecifierAndAddress($specifier, $validated);

        if ($result) {
            Inertia::flash('success', 'Specifier updated!');
            return back();
        } else {
            Inertia::flash('error', 'Update failed. Please try again later or contact an admin.');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Architect $architect, Specifier $specifier): RedirectResponse
    {
        Gate::authorize('update', $architect);
        $result = $this->specifierService->deleteSpecifier($specifier);

        if ($result) {
            Inertia::flash('success', 'Specifier deleted!');
            return back();
        } else {
            Inertia::flash('error', 'Delete failed. Please try again later or contact an admin.');
            return back();
        }
    }
}
