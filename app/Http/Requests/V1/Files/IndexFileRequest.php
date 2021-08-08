<?php

namespace App\Http\Requests\V1\Files;

use Illuminate\Foundation\Http\FormRequest;

class IndexFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function attributes()
    {
        return [
        ];
    }
}
