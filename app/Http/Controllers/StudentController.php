<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResponse;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class StudentController
 * @package App\Http\Controllers
 */
class StudentController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        //get all parameters of request
        $length = (int)$request->input('length', 25);
        $searchValue = $request->input('search');

        $user = Auth::user();

        $studentsResponse = $user->students();

        if($searchValue != null) {
            $studentsResponse = $studentsResponse->where(function ($query) use ($searchValue) {
                $query->where('firstname', 'ILIKE', '%' . $searchValue . '%');
                $query->orWhere('lastname', 'ILIKE', '%' . $searchValue . '%');
                $query->orWhere('email', 'ILIKE', '%' . $searchValue . '%');
                $query->orWhere('studentnumber', 'ILIKE', '%' . $searchValue . '%');
            });
        }

        return StudentResponse::collection($studentsResponse->paginate($length));
    }

    /**
     * @param StudentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(StudentRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        $validated['coach_id'] = $user->id;
        $validated['guid'] = Student::createGuid();

        try {
            Student::create($validated);
        } catch (\Exception $exception) {
            return response()->json(['Error'=>'Something went wrong, try again'], 500);
        }

        return response()->json('Student is succesfully created');

    }

    /**
     * @param Student $student
     * @return StudentResponse
     */
    public function show(Student $student)
    {
        return new StudentResponse($student);
    }

    /**
     * @param Student $student
     * @param StudentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Student $student, StudentRequest $request)
    {
        $student->update($request->all());

        return response()->json('Student is succesfully updated');
    }

    /**
     * @param Student $student
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Student $student)
    {
        $student->delete();

        return response()->json('Student is succesfully deleted', 204);
    }

}
