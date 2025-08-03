<?php

namespace App\Actions\Organization;

use App\DTO\GeoParamsDTO;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class GetOrganizationsByRadiusAction
{
    public function execute(GeoParamsDTO $params): Collection
    {
        return Organization::with(['building', 'phones', 'activities'])
            ->whereHas('building', function ($query) use ($params) {
                $query->selectRaw('*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                sin(radians(latitude)))) AS distance', [
                    $params->latitude,
                    $params->longitude,
                    $params->latitude
                ])
                    ->having('distance', '<', $params->radius);
            })
            ->get();
    }
}
