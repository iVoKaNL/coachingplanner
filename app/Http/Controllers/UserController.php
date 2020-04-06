<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentsResponse;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getStudents(User $user)
    {
        return StudentsResponse::collection($user->students);
    }

}
