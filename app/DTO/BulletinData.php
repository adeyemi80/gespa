<?php

namespace App\DTO;

class BulletinData
{
    public function __construct(
        public readonly array $bulletin
    ) {}

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return $this->bulletin;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->bulletin[$key] ?? $default;
    }
}