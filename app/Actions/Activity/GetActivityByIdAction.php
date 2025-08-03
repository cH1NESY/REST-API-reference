<?php

namespace App\Actions\Activity;

use App\Models\Activity;

class GetActivityByIdAction
{
    public function execute(int $id): ?Activity
    {
        return Activity::with(['children', 'organizations'])->find($id);
    }
} 