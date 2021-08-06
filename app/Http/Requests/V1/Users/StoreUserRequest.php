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
            'username' => ['required', 'max:1'],
            'name' => ['required', 'min:2', 'max:100'],
            'lastname' => ['required', 'min:2', 'max:50'],
            'email' => ['required', 'max:50','email'],
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'nombre de usuario',
            'name' => 'nombre',
            'lastname' => 'apellido',
            'email' => 'correo electrónico'
        ];
    }
}
