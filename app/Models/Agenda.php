<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $table = 'agenda';

    protected $fillable = [
        'start_time',
        'end_time',
        'subject',
        'location',
        'coach_id',
        'student_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'coach_id', 'student_id'
    ];

    public function hasStudent()
    {
        if ($this->student_id !== null) {
            return true;
        }
        return false;
    }

}
