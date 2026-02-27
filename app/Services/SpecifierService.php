<?php

namespace App\Services;

use App\Models\Architect;
use App\Models\Specifier;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpecifierService
{
    public function __construct(protected AddressService $addressService) {}

    public function storeSpecifier(Architect $architect, array $data): bool
    {
        try {
            DB::transaction(function () use ($architect, $data) {
                /** @var Specifier $specifier */
                $specifier = $architect->specifiers()->create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'job_title' => $data['job_title'],
                ]);

                $fullName = $data['last_name'] ?
                    $data['first_name'] . ' ' . $data['last_name'] :
                    $data['first_name'];

                $addressData = [
                    'phys_address1' => $data['physical_address1'] ?? 'TBD', // required for address
                    'name' => $fullName,
                    'central_phone_number' => $data['central_phone_number'],
                    'email_address' => $data['email_address'],
                ];

                $this->addressService->storeAddress($specifier, $addressData);
            });

            return true;
        } catch (Exception $e) {
            Log::error("Failed to create specifier for architect {$architect->id}", [
                'error' => $e->getMessage()
            ]);

            return false;
        };
    }

    public function updateSpecifierAndAddress(Specifier $specifier, array $data): bool
    {
        try {
            DB::transaction(function () use ($data, $specifier) {
                $specifier->update([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'job_title' => $data['job_title'],
                ]);

                $fullName = $data['last_name'] ?
                    $data['first_name'] . ' ' . $data['last_name'] :
                    $data['first_name'];

                $addressData = [
                    'phys_address1' => 'TBD',  // required for address
                    'name' => $fullName,
                    'central_phone_number' => $data['central_phone_number'],
                    'email_address' => $data['email_address'],
                ];

                $this->addressService->updateAddress($specifier, $specifier->address, $addressData);
            });
            return true;
        } catch (Exception $e) {
            Log::error("Failed to update specifier ID {$specifier->id}", ['error' => $e->getMessage()]);

            return false;
        };
    }

    public function deleteSpecifier(Specifier $specifier): bool
    {
        try {
            return (bool) $specifier->delete();
        } catch (Exception $e) {
            Log::error("Failed to delete specifier ID {$specifier->id}:", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
