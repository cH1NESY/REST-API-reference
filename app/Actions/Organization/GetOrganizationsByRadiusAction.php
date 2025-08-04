<?php

namespace App\Actions\Organization;

use App\DTO\GeoParamsDTO;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class GetOrganizationsByRadiusAction
{
    public function execute(GeoParamsDTO $params): Collection
    {
        return Organization::query()
            ->with(['building', 'phones', 'activities'])
            ->select('organizations.*')
            ->join('buildings', 'organizations.building_id', '=', 'buildings.id')
            ->whereRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                sin(radians(latitude)))) < ?',
                [
                    $params->latitude,
                    $params->longitude,
                    $params->latitude,
                    $params->radius
                ]
            )
            ->get();
    }
}
