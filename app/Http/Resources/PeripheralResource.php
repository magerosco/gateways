<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use SebastianBergmann\Type\VoidType;

class PeripheralResource extends JsonResource
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
            'UID' => $this->UID,
            'vendor' => $this->vendor,
            'status' => $this->status,
            'gateway_id' => $this->gateway_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


}
