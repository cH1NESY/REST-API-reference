<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetByAreaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'min_lat' => 'required|numeric',
            'max_lat' => 'required|numeric',
            'min_lng' => 'required|numeric',
            'max_lng' => 'required|numeric'
        ];
    }
}
