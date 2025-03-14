<?php

namespace App\DTO;

class ProductDTO
{

    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public float $price,
        public int $stock,
        public string $image,
        public bool $active,
        public int $quantity
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? '',
            $data['name'] ?? '',
            $data['description'] ?? '',
            $data['price'] ?? 0,
            $data['stock'] ?? 0,
            $data['image'] ?? '',
            $data['active'] ?? false,
            $data['quantity'] ?? 0
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $this->image,
            'active' => $this->active,
            'quantity' => $this->quantity
        ];
    }
}
