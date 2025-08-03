<?php

namespace App\DTO;

class AreaParamsDTO
{
    public function __construct(
        public readonly float $minLat,
        public readonly float $maxLat,
        public readonly float $minLng,
        public readonly float $maxLng
    ) {}
}
