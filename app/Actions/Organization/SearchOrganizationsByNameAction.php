<?php

namespace App\Actions\Organization;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class SearchOrganizationsByNameAction
{
    public function execute(string $name): Collection
    {
        return Organization::with(['building', 'phones', 'activities'])
            ->where('name', 'like', '%' . $name . '%')
            ->get();
    }
} 