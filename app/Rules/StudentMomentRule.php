<?php

namespace App\Rules;

use App\Models\Student;
use Illuminate\Contracts\Validation\Rule;

class StudentMomentRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Student::where('guid', $value)->where('coach_id', $this->user->id)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not the right coach/student';
    }
}
