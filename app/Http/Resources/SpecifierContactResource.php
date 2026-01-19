<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecifierContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Address $address */
        $address = $this->resource;

        return [
            'central_phone_number' => $address->central_phone_number,
            'email_address' => $address->email_address,
        ];
    }
}
