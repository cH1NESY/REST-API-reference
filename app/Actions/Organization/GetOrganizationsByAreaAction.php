<?php

namespace App\Actions\Organization;

use App\DTO\AreaParamsDTO;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class GetOrganizationsByAreaAction
{
    public function execute(AreaParamsDTO $params): Collection
    {
        return Organization::with(['building', 'phones', 'activities'])
            ->whereHas('building', function ($query) use ($params) {
                $query->whereBetween('latitude', [$params->minLat, $params->maxLat])
                    ->whereBetween('longitude', [$params->minLng, $params->maxLng]);
            })
            ->get();
    }
}
