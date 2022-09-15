<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignUpRequest extends FormRequest
{

    protected $redirect = '/';


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
            'first_name' => ['required', 'min:1', 'max:50'],
            'last_name' => ['required', 'min:1', 'max:50'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ];
    }


    public function attributes()
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
        ];
    }


    public function messages()
    {
        return [
            'email.email' => "Invalid email address",
            'password.confirmed' => "Passwords do not match",
        ];
    }
}
