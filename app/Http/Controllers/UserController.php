<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentsResponse;
use App\Http\Resources\UserResponse;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getStudents(User $user)
    {
        return StudentsResponse::collection($user->students);
    }

    public function getUser(User $user)
    {
        return new UserResponse($user);
    }

}
