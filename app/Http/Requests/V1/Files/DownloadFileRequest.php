<?php

namespace App\Http\Requests\V1\Files;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\V1\AppFormRequest;

class DownloadFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'full_path' => [
                'required',
            ],
        ];
        return AppFormRequest::rules($rules);
    }

    public function attributes()
    {
        $attributes = [
            'full_path' => 'ruta completa',
        ];
        return AppFormRequest::attributes($attributes);
    }
}
