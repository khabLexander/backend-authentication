<?php

namespace App\Http\Requests\V1\Authentications;


use Illuminate\Foundation\Http\FormRequest;

class LoginAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => ['required'],
            'device_name' => ['required', 'max:100'],
        ];
        return AppFormRequest::attributes($rules);
    }

    public function attributes()
    {
       return [
            'username' => 'nombre de usuario',
            'password' => 'contraseña',
            'device_name' => 'nombre del dispositivo',
        ];

    }
}
