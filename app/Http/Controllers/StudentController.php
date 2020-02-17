<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResponse;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return StudentResponse::collection($user->students);
    }

    public function create()
    {
        $user = Auth::user();

    }

    public function show(Student $student)
    {

    }

}
