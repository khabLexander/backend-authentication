<?php

namespace App\Http\Requests\V1\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'identificationType' => ['required'],
            'username' => ['required', 'max:100'],
            'name' => ['required', 'min:2', 'max:100'],
            'lastname' => ['required', 'min:2', 'max:50'],
            'email' => ['required', 'max:50', 'email'],
            'password' => ['required', 'min:6', 'max:16', 'confirmed'],
        ];
    }

    public function attributes()
    {
        return [
            'identificationType' => 'tipo de documento',
            'username' => 'nombre de usuario',
            'name' => 'nombres',
            'lastname' => 'apellidos',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
        ];
    }
}
