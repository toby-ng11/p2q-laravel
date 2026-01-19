<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArchitectTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var \App\Models\ArchitectType $architectType */
        $architectType = $this->resource;

        return [
            'id' => $architectType->id,
            'architect_type_desc' => $architectType->architect_type_desc,
        ];
    }
}
