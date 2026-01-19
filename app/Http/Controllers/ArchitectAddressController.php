<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureRequestIsJson;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Models\Architect;
use App\Services\AddressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class ArchitectAddressController extends Controller implements HasMiddleware
{
    public function __construct(protected AddressService $addressService) {}

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
        return $this->addressService->storeArchitectAddress($architect, $validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Architect $architect, Address $address): RedirectResponse
    {
        $validated = $request->validated();
        return $this->addressService->updateArchitectAddress($architect, $address, $validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Architect $architect, Address $address): RedirectResponse
    {
        Gate::authorize('update', $architect);
        return $this->addressService->deleteAddress($architect, $address);
    }
}
