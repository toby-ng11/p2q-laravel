<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phys_address1' => $this->phys_address1,
            'phys_address2' => $this->phys_address2,
            'phys_city' => $this->phys_city,
            'phys_state' => $this->phys_state,
            'phys_postal_code' => $this->phys_postal_code,
            'phys_country' => $this->phys_country,
            'central_phone_number' => $this->central_phone_number,
            'email_address' => $this->email_address,
            'url' => $this->url,
        ];
    }
}
