<?php

namespace App\Actions\Organization;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class GetOrganizationsByBuildingAction
{
    public function execute(int $buildingId): Collection
    {
        return Organization::with(['phones', 'activities'])
            ->where('building_id', $buildingId)
            ->get();
    }
} 