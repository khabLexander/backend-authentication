<?php

namespace App\Http\Requests\V1\Files;

use App\Http\Requests\V1\AppFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class DeleteFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'ids' => [
                'required'
            ]
        ];
        return AppFormRequest::rules($rules);
    }

    public function attributes()
    {
        $attributes = [
            'ids' => 'IDs'
        ];
        return AppFormRequest::attributes($attributes);
    }
}
