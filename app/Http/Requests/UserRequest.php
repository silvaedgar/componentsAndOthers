<?php

namespace App\Http\Requests;

use App\Extensions\Utils;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    protected $utils;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:30|min:3',
            'password' => 'required|string|min:8',
            'email' => ['required','string','email',
                $this->id == 0 ? Rule::unique('users') : Rule::unique('users')->ignore($this->id)]
        ];
        if ($this->shouldConfirmPassword()) {
            $rules['password'] .= '|confirmed';
            $rules ['password_confirmation'] = 'required';
        }

        return $rules;

    }

    public function messages() {

        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de :max caracteres',
            'name.min' => 'El nombre debe contener :min caracteres o mas',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password_confirmation.required' => 'El campo confirmar contraseña es obligatorio.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errores = implode(' | ', $validator->errors()->all());
        $message = "El form request presento los siguiente errores: $errores. Grabando  datos del usuario: " . $this->input('email');
        $request = new Request($this->all());
        $jsonInput = $this->utils->hideFieldLog($request,['password']);

        $this->utils->makeLog("Modulo de Usuarios",$message, $jsonInput,null, $message);

        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }

    protected function shouldConfirmPassword(): bool
    {
        return $this->routeIs('signup'); // solo en creación
    }



}
