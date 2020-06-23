<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportStudentRequest extends FormRequest
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
            'students' => 'required|array',
            '*.*.firstname' => 'required|string',
            '*.*.lastname' => 'required|string',
            '*.*.studentnumber' => 'required|integer|digits:7',
            '*.*.email' => 'required|email|unique:student,email',
        ];
    }
}
