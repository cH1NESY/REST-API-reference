<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'building_id' => 'sometimes|integer|exists:buildings,id',
            'activity_id' => 'sometimes|integer|exists:activities,id',
            'include_descendants' => 'sometimes|boolean',
            'latitude' => 'required_with:longitude,radius|numeric',
            'longitude' => 'required_with:latitude,radius|numeric',
            'radius' => 'required_with:latitude,longitude|numeric|min:0',
            'min_lat' => 'required_with:max_lat,min_lng,max_lng|numeric',
            'max_lat' => 'required_with:min_lat,min_lng,max_lng|numeric',
            'min_lng' => 'required_with:min_lat,max_lat,max_lng|numeric',
            'max_lng' => 'required_with:min_lat,max_lat,min_lng|numeric',
        ];
    }
}
