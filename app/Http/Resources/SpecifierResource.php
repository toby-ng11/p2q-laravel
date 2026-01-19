<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecifierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Specifier $address */
        $specifier = $this->resource;

        return [
            'id' => $specifier->id,
            'first_name' => $specifier->first_name,
            'last_name' => $specifier->last_name,
            'job_title' => $specifier->job_title,
            'contact' => new SpecifierContactResource($this->whenLoaded('address')),
        ];
    }
}
