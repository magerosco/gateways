<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GatewayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'serial_number' => $this->serial_number,
            'name' => $this->name,
            'IPv4_address' => $this->IPv4_address,
            'peripheral' => $this->peripheral,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
