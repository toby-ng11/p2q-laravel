<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureRequestIsJson;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Models\Architect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ArchitectAddressController extends Controller implements HasMiddleware
{
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
        return $architect->addresses()->get()->toResourceCollection();
    }

    /**
     * Display the specified resource.
     */
    public function show(Architect $architect, Address $address): RedirectResponse
    {
        return to_route('architects.edit', $architect);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request, Architect $architect): RedirectResponse
    {
        $validated = $request->validated();
        $address = new Address($validated);
        $result = $architect->addresses()->save($address);

        if (! $result) {
            return back()->withErrors('Please contact admin to resolve the problem.');
        }

        Inertia::flash('success', 'Address created successfully.');

        return back();
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
    public function destroy(Architect $architect, Address $address): RedirectResponse
    {
        Gate::authorize('update', $architect);
        $result = $address->delete();

        if ($result) {
            Inertia::flash('success', 'Address deleted!');

            return back();
        } else {
            Inertia::flash('error', 'Something went wrong, please try again.');

            return back();
        }
    }
}
