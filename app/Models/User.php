<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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


    public function students()
    {
        return $this->hasMany(Student::class, 'coach_id', 'id');
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'coach_id', 'id');
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
        $users = User::select('guid')->where('guid', $guid)->count();
        if ($users) {
            return self::createGuid($tries - 1);
        }
        return $guid;
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if($this->suffix){
            return "{$this->firstname} {$this->suffix} {$this->lastname}";
        }
        return "{$this->firstname} {$this->lastname}";
    }
}
