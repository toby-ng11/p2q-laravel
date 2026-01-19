<?php

namespace App\Services;

use App\Models\Specifier;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SpecifierService
{
    public function updateSpecifierAndAddress(Specifier $specifier, array $data): RedirectResponse
    {
        try {
            DB::transaction(function () use ($data, $specifier) {
                $specifier->update([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'job_title' => $data['job_title'],
                ]);
            });

            $specifier->address()->updateOrCreate(
                [
                    'addressable_id' => $specifier->id,
                    'addressable_type' => get_class($specifier)
                ],
                [
                    'phys_address1' => $data['first_name'] . ' ' . $data['last_name'], // required for address
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'central_phone_number' => $data['central_phone_number'],
                    'email_address' => $data['email_address'],
                ]
            );

            Inertia::flash('success', 'Specifier updated!');
            return back();
        } catch (Exception $e) {
            Log::error("Failed to update specifier ID {$specifier->id}: " . $e->getMessage());
            Inertia::flash('error', 'Deletion failed.');
            return back();
        }
    }
}
