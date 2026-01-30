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
    public function storeArchitectAddress(Architect $architect, array $data): bool
    {
        try {
            $architect->addresses()->create($data);
            return true;
        } catch (Exception $e) {
            Log::error("Address creation failed for Architect {$architect->id}: " . $e->getMessage());
            return false;
        }
    }

    public function updateArchitectAddress(Architect $architect, Address $address, array $data): bool
    {
        try {
            $address->update($data);
            return true;
        } catch (Exception $e) {
            Log::error("Address update failed for Architect {$architect->id}: " . $e->getMessage());
            return false;
        }
    }

    public function deleteAddress(Architect $architect, Address $address): bool
    {
        try {
            $address->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Address deletion failed for Architect {$architect->id}: " . $e->getMessage());
            return false;
        }
    }
}
