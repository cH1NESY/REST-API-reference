<?php

namespace App\Actions\Organization;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class GetOrganizationsByActivityAction
{
    public function execute(int $activityId): Collection
    {
        return Organization::with(['building', 'phones', 'activities'])
            ->whereHas('activities', function ($query) use ($activityId) {
                $query->where('activity_id', $activityId);
            })
            ->get();
    }
} 