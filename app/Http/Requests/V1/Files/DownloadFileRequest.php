<?php

namespace App\Http\Requests\V1\Files;

use Illuminate\Foundation\Http\FormRequest;

class DownloadFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_path' => [
                'required',
            ],
        ];
    }

    public function attributes()
    {
       return [
            'full_path' => 'ruta completa',
        ];
    }
}
