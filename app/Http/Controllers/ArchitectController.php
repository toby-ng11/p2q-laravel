<?php

namespace App\Http\Controllers;

use App\Models\Architect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchitectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $architects = Architect::select()
            ->with(['architectType:id,architect_type_desc', 'architectRep:id,name'])
            ->get();
        return response()->json($architects->toArray());
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, Architect $architect)
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
