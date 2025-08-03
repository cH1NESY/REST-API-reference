<?php

namespace App\Actions\Activity;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

class GetRootActivitiesAction
{
    public function execute(): Collection
    {
        return Activity::with('children')->whereNull('parent_id')->get();
    }
} 