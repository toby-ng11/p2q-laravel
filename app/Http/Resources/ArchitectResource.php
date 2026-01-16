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
        return [
            'id' => $this->id,
            'architect_name' => $this->architect_name,
            'architect_type' => new ArchitectTypeResource($this->whenLoaded('architectType')),
            'architect_rep' => new UserResource($this->whenLoaded('architectRep')),
            'class_id' => $this->class_id,
            'created_at' => $this->created_at,
        ];
    }
}
