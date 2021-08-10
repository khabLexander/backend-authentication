<?php

namespace App\Http\Requests\V1\Authentications;

use Illuminate\Foundation\Http\FormRequest;

class LogoutAuthRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
        ];
    }

    public function attributes()
    {
       return  [
        ];

    }
}
