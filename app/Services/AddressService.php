<?php

namespace App\Services;

use App\Models\Address;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AddressService
{
    public function storeAddress(Model $model, array $data): bool
    {
        try {
            $model->storeAddress($data);
            return true;
        } catch (Exception $e) {
            Log::error(
                "Address creation failed for " . get_class($model) . " ID: {$model->id}.",
                ['error' => $e->getMessage(), 'data' => $data]
            );
            return false;
        }
    }

    public function updateAddress(Model $model, Address $address, array $data): bool
    {
        try {
            return $address->update($data);
        } catch (Exception $e) {
            Log::error(
                "Address update failed for " . get_class($model) . " ID: {$model->id}, address ID: {$address->id}.",
                ['error' => $e->getMessage(), 'data' => $data]
            );
            return false;
        }
    }

    public function deleteAddress(Model $model, Address $address): bool
    {
        try {
            return (bool) $address->delete();
        } catch (Exception $e) {
            Log::error(
                "Address update failed for " . get_class($model) . " ID: {$model->id}, address ID: {$address->id}.",
                ['error' => $e->getMessage()]
            );
            return false;
        }
    }
}
