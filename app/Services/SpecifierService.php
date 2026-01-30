<?php

namespace App\Services;

use App\Models\Architect;
use App\Models\Specifier;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpecifierService
{
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

                $fullName = "{$data['first_name']} {$data['last_name']}";

                $specifier->address()->create([
                    'phys_address1' => 'TBD', // required for address
                    'name' => $fullName,
                    'central_phone_number' => $data['central_phone_number'],
                    'email_address' => $data['email_address'],
                ]);
            });

            return true;
        } catch (Exception $e) {
            Log::error("Failed to create specifier for architect {$architect->id}: " . $e->getMessage());

            return false;
        }
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

                $fullName = "{$data['first_name']} {$data['last_name']}";

                $specifier->address()->updateOrCreate(
                    [
                        'addressable_id' => $specifier->id,
                        'addressable_type' => get_class($specifier)
                    ],
                    [
                        'phys_address1' => 'TBD',  // required for address
                        'name' => $fullName,
                        'central_phone_number' => $data['central_phone_number'],
                        'email_address' => $data['email_address'],
                    ]
                );
            });

            return true;
        } catch (Exception $e) {
            Log::error("Failed to update specifier ID {$specifier->id}: " . $e->getMessage());

            return false;
        }
    }

    public function deleteSpecifier(Specifier $specifier): bool
    {
        try {
            DB::transaction(function () use ($specifier) {
                $specifier->address()->delete();
                $specifier->delete();
            });
            return true;
        } catch (Exception $e) {
            Log::error("Failed to delete specifier ID {$specifier->id}: " . $e->getMessage());
            return false;
        }
    }
}
