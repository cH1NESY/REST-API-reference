<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetByRadiusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:0'
        ];
    }
}
