<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';

    protected $fillable = [
        'firstname',
        'suffix',
        'lastname',
        'studentnumber',
        'email',
        'guid',
        'coach_id'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName() : string
    {
        return 'guid';
    }

    /**
     * Create a random guid
     * To prevent duplicates the function is recursive
     *
     * @param $tries , default is 5
     * @return string
     */
    public static function createGuid($tries = 5): string
    {
        if ($tries == 0) {
            return md5(microtime() . microtime());
        }

        $guid = md5(microtime());
        $students = Student::select('guid')->where('guid', $guid)->count();
        if ($students) {
            return self::createGuid($tries - 1);
        }
        return $guid;
    }

    public function getFullNameAttribute()
    {
        if($this->suffix != null) {
            return "{$this->firstname} {$this->suffix} {$this->lastname}";
        }
        return "{$this->firstname} {$this->lastname}";
    }
}
