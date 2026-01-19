<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Address $address */
        $address = $this->resource;

        return [
            'id' => $address->id,
            'name' => $address->name,
            'phys_address1' => $address->phys_address1,
            'phys_address2' => $address->phys_address2,
            'phys_city' => $address->phys_city,
            'phys_state' => $address->phys_state,
            'phys_postal_code' => $address->phys_postal_code,
            'phys_country' => $address->phys_country,
            'central_phone_number' => $address->central_phone_number,
            'email_address' => $address->email_address,
            'url' => $address->url,
        ];
    }
}
