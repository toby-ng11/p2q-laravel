<?php

namespace App\Http\Controllers;

use App\Models\ArchitectType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArchitectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        return ArchitectType::query()
            ->orderBy('architect_type_desc')
            ->get()
            ->toResourceCollection();
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
    public function show(ArchitectType $architectType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ArchitectType $architectType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArchitectType $architectType)
    {
        //
    }
}
