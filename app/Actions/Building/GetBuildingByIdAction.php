<?php

namespace App\Actions\Building;

use App\Models\Building;

class GetBuildingByIdAction
{
    public function execute(int $id): ?Building
    {
        return Building::with('organizations')->find($id);
    }
} 