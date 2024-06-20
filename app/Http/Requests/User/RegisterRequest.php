<?php

namespace App\Http\Requests\User;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users') // Asegura que el email sea único en la tabla de usuarios
            ],
            'password' => 'required|string|min:8',
            'c_password' => 'required|same:password', // Confirma que password y c_password son iguales
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe proporcionar un formato de correo electrónico válido.',
            'email.unique' => 'El correo electrónico ya ha sido registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'c_password.required' => 'La confirmación de la contraseña es obligatoria.',
            'c_password.same' => 'Las contraseñas no coinciden.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'mensaje' => 'Validación fallida',
            'errores' => $validator->errors()
        ], 200);

        throw new HttpResponseException($response);
    }
}
