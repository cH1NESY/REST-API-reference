<?php

namespace App\Actions\Organization;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class GetAllOrganizationsAction
{
    public function execute(): Collection
    {
        return Organization::with(['building', 'phones', 'activities'])->get();
    }
}
