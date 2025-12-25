<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArchitectRequest;
use App\Http\Requests\UpdateArchitectRequest;
use App\Models\Architect;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ArchitectController extends Controller implements HasMiddleware
{
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

        if ($userId) {
            $architects = Architect::select()
                ->where('architect_rep_id', $userId)
                ->with(['architectType:id,architect_type_desc', 'architectRep:id,name'])
                ->get();
            return response()->json($architects->toArray());
        }

        if ($user && $user->isAdministrator()) {
            $architects = Architect::select()
                ->with(['architectType:id,architect_type_desc', 'architectRep:id,name'])
                ->get();
            return response()->json($architects->toArray());
        }

        return response()->json(['User ID is required.']);
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
    public function store(StoreArchitectRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $architect = Architect::create([
            'architect_name'    => $validated['architect_name'],
            'architect_rep_id'  => $validated['architect_rep_id'],
            'architect_type_id' => $validated['architect_type_id'],
            'class_id'          => $validated['class_id'],
        ]);

        return to_route('architects.edit', $architect)
            ->with('message', 'Architect created successfully.');
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
    public function edit(Architect $architect): Response
    {
        return Inertia::render('architects/edit', ['architect' => $architect]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArchitectRequest $request, Architect $architect)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Architect $architect)
    {
        //
    }
}
