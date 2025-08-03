<?php

namespace App\DTO;

class GeoParamsDTO
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly float $radius
    ) {}
}
