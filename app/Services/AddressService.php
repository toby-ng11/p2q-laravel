<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Architect;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AddressService
{
    public function storeArchitectAddress(Architect $architect, array $data): RedirectResponse
    {
        try {
            $architect->addresses()->create($data);

            Inertia::flash('success', 'Address created successfully.');
            return back();
        } catch (Exception $e) {
            Log::error("Address creation failed for Architect {$architect->id}: " . $e->getMessage());

            Inertia::flash('error', 'Could not save address. Please try again.');
            return back();
        }
    }

    public function updateArchitectAddress(Architect $architect, Address $address, array $data): RedirectResponse
    {
        try {
            $address->update($data);

            Inertia::flash('success', 'Address updated successfully.');
            return back();
        } catch (Exception $e) {
            Log::error("Address update failed for Architect {$architect->id}: " . $e->getMessage());

            Inertia::flash('error', 'Could not update address. Please try again.');
            return back();
        }
    }

    public function deleteAddress(Architect $architect, Address $address): RedirectResponse
    {
        try {
            $address->delete();

            Inertia::flash('success', 'Address deleted!');
            return back();
        } catch (Exception $e) {
            Log::error("Address deletion failed for Architect {$architect->id}: " . $e->getMessage());

            Inertia::flash('error', 'Could not delete address. Please try again.');
            return back();
        }
    }
}
