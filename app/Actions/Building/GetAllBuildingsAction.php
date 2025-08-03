<?php

namespace App\Actions\Building;

use App\Models\Building;
use Illuminate\Database\Eloquent\Collection;

class GetAllBuildingsAction
{
    public function execute(): Collection
    {
        return Building::with('organizations')->get();
    }
} 