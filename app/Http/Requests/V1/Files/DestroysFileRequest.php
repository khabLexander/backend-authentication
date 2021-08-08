<?php

namespace App\Http\Requests\V1\Files;

use Illuminate\Foundation\Http\FormRequest;

class DestroysFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ids' => [
                'required'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'ids' => 'IDs'
        ];
    }
}
