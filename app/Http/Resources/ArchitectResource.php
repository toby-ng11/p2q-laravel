<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArchitectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Architect $architect */
        $architect = $this->resource;

        return [
            'id' => $architect->id,
            'architect_name' => $architect->architect_name,
            'architect_type' => new ArchitectTypeResource($this->whenLoaded('architectType')),
            'architect_rep' => new UserResource($this->whenLoaded('architectRep')),
            'class_id' => $architect->class_id,
            'created_at' => $architect->created_at,
        ];
    }
}
