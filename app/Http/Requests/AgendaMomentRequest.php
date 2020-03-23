<?php

namespace App\Http\Requests;

use App\Rules\EndTimeRule;
use App\Rules\StartTimeRule;
use App\Rules\StudentMomentRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AgendaMomentRequest extends FormRequest
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
        $user = $this->route('user');
        return [
            'guid' => ['required', 'string', 'exists:student', new StudentMomentRule($user)],
            'start_time' => ['required' , 'date', new StartTimeRule($this->get('end_time'))],
            'end_time' => ['required', 'date']
        ];
    }
}
