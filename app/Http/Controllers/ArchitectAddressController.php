<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Models\Architect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ArchitectAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Architect $architect): JsonResponse
    {
        $addresses = $architect->addresses()->get();

        return response()->json($addresses->toArray());
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
    public function store(StoreAddressRequest $request, Architect $architect): RedirectResponse
    {
        $validated = $request->validated();
        $address = new Address($validated);
        $architect->addresses()->save($address);

        Inertia::flash('success', 'Address created successfully.');

        return to_route('architects.edit', $architect);
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
    public function update(UpdateAddressRequest $request, Architect $architect, Address $address): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        if ($user) {
            $result = $address->update($validated);

            if ($result) {
                Inertia::flash('success', 'Address saved!');

                return back();
            } else {
                Inertia::flash('error', 'Something went wrong, please try again.');

                return back();
            }
        }

        Inertia::flash('error', 'User is not logged in.');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Architect $architect)
    {
        //
    }
}
