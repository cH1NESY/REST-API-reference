<?php

namespace App\Actions\Organization;

use App\Models\Organization;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

class GetOrganizationsByActivityWithDescendantsAction
{
    public function execute(int $activityId): Collection
    {
        $activity = Activity::find($activityId);
        if (!$activity) {
            return collect();
        }

        // Get all descendant activity IDs
        $descendantIds = $this->getDescendantActivityIds($activityId);
        $allActivityIds = array_merge([$activityId], $descendantIds);

        return Organization::with(['building', 'phones', 'activities'])
            ->whereHas('activities', function ($query) use ($allActivityIds) {
                $query->whereIn('activity_id', $allActivityIds);
            })
            ->get();
    }

    /**
     * Get descendant activity IDs recursively
     */
    private function getDescendantActivityIds(int $activityId): array
    {
        $descendants = Activity::where('parent_id', $activityId)->get();
        $ids = [];

        foreach ($descendants as $descendant) {
            $ids[] = $descendant->id;
            $ids = array_merge($ids, $this->getDescendantActivityIds($descendant->id));
        }

        return $ids;
    }
} 