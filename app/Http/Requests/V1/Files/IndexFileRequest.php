<?php

namespace App\Http\Requests\V1\Files;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\V1\AppFormRequest;

class IndexFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        return AppFormRequest::rules($rules);
    }

    public function attributes()
    {
        $attributes = [
        ];
        return AppFormRequest::attributes($attributes);
    }
}
