<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function message() : array
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email is not an email format',
            'password.required' => 'Password is required'
        ];
    }
}
