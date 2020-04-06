<?php

namespace App\Rules;

use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class StartTimeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($endTime)
    {
        $this->endTime = Carbon::parse($endTime);
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
        $startTime = Carbon::parse($value);

        //check if date is in future
        if($startTime->isPast()) {
            dd('test1');
            return false;
        }

        //check if end time is after begin time
        if(!$this->endTime->isAfter($startTime)) {
            dd('test2');
            return false;
        }

        //check if difference between begin and end time is 30 minutes
        if($this->endTime->diffInMinutes($startTime) != 30) {
            dd('test3');
            return false;
        }

        //check if time is in valid time of coach
        $possibleTime = Agenda::whereNull('student_id')
            ->where('start_time', '<=', $value)
            ->where('end_time', '>=', $this->endTime->toDateTimeString())
            ->exists();

        if(!$possibleTime) {
            dd('test4');
            return false;
        }

        //check if time is already assigned to other student
        $assigned = Agenda::whereNotNull('student_id')
            ->where('start_time', '>=', $value)
            ->where('end_time', '<=', $this->endTime->toDateTimeString())
            ->exists();

        if($assigned) {
            dd('test5');
            return false;
        }


        return true;


    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The given time is not valid';
    }
}
