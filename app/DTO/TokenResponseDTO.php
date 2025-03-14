<?php

namespace App\DTO;

class TokenResponseDTO
{

    public function __construct(
        public string $accessToken,
        public string $expiresAt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['accessToken'] ?? '',
            $data['expiresAt'] ?? ''
        );
    }

    public function toArray(): array
    {
        return [
            'accessToken' => $this->accessToken,
            'expiresAt' => $this->expiresAt,
        ];
    }
}
