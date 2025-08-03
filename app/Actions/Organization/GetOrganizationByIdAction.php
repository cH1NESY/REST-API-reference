<?php

namespace App\Actions\Organization;

use App\Models\Organization;

class GetOrganizationByIdAction
{
    public function execute(int $id): ?Organization
    {
        return Organization::with(['building', 'phones', 'activities'])->find($id);
    }
} 