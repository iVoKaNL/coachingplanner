<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportStudentRequest;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResponse;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class StudentController
 * @package App\Http\Controllers
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Coaching planner API Server"
 * )
 */
class StudentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/students",
     *      operationId="get_students",
     *      tags={"Student"},
     *      summary="Get list of students",
     *      description="Returns list of coaches students",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *     security={
     *         {"coachingplanner_auth_key"}
     *     },
     * )
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        //get all parameters of request
        $length = (int)$request->input('length', 20);
        $searchValue = $request->input('search');

        $user = Auth::user();

        $studentsResponse = $user->students();

        if($searchValue != null) {
            $studentsResponse = $studentsResponse->where(function ($query) use ($searchValue) {
                $query->where('firstname', 'LIKE', '%' . $searchValue . '%');
                $query->orWhere('lastname', 'LIKE', '%' . $searchValue . '%');
                $query->orWhere('email', 'LIKE', '%' . $searchValue . '%');
                $query->orWhere('studentnumber', 'LIKE', '%' . $searchValue . '%');
            });
        }

        return StudentResponse::collection($studentsResponse->paginate($length));
    }

    /**
     * @OA\Post(
     *      path="/student",
     *      operationId="create_student",
     *      tags={"Student"},
     *      summary="Create new student",
     *      description="Created a new student for the coach that can assign coaching moment",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateStudentRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Student is successfully created",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Something went wrong",
     *       ),
     *      security={
     *          {"coachingplanner_auth_key"}
     *      },
     * )
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

        return response()->json('Student is successfully created');

    }

    /**
     * @OA\Get(
     *      path="/student/{student}",
     *      operationId="get_student",
     *      tags={"Student"},
     *      summary="Get details of specific student",
     *      description="Returns details of a specific student",
     *      @OA\Parameter(
     *          name="student",
     *          in="query",
     *          description="guid of the student",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              example="9c058e90-7ef1-11ea-a2bd-6db2999910ca"
     *          ),
     *          style="form"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *     security={
     *         {"coachingplanner_auth_key"}
     *     },
     * )
     * @param Student $student
     * @return StudentResponse
     */
    public function show(Student $student)
    {
        return new StudentResponse($student);
    }

    /**
     * @OA\Put(
     *      path="/student/{student}",
     *      operationId="edit_student",
     *      tags={"Student"},
     *      summary="Edit the given student when it's a student of the coach",
     *      description="Edit the given student",
     *      @OA\Parameter(
     *          name="student",
     *          in="query",
     *          description="guid of the student",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              example="9c058e90-7ef1-11ea-a2bd-6db2999910ca"
     *          ),
     *          style="form"
     *      ),
     *      @OA\RequestBody(
 *              required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateStudentRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Something went wrong, please try again."
     *      ),
     *      security={
     *         {"coachingplanner_auth_key"}
     *      },
     * )
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
     * @OA\Delete(
     *      path="/student/{student}",
     *      operationId="delete_student",
     *      tags={"Student"},
     *      summary="Delete the given student when it's a student of the coach",
     *      description="Delete the given student",
     *      @OA\Parameter(
     *          name="student",
     *          in="query",
     *          description="guid of the student",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              example="9c058e90-7ef1-11ea-a2bd-6db2999910ca"
     *          ),
     *          style="form"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Something went wrong, please try again."
     *      ),
     *      security={
     *          {"coachingplanner_auth_key"}
     *      },
     * )
     *
     * @param Student $student
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Student $student)
    {
        $student->delete();

        return response()->json('Student is succesfully deleted', 204);
    }

    /**
     * @OA\Post(
     *      path="/student/import",
     *      operationId="import_students",
     *      tags={"Student"},
     *      summary="Import students from .csv",
     *      description="Import student from csv to make it easier to add students",
     *      @OA\RequestBody(
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Something went wrong",
     *       ),
     *      security={
     *          {"coachingplanner_auth_key"}
     *      },
     * )
     * @param ImportStudentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(ImportStudentRequest $request)
    {
        $user = Auth::user();
        $students = $request['students'];

        if($this->checkUniqueValues($students)) {
            return response()->json("Studentnumber or email are duplicated", 422);
        }

        foreach($students as $student) {
            $student['guid'] = Student::createGuid();
            $student['coach_id'] = $user->id;
            Student::create($student);
        }

        return response()->json("Success", 200);
    }

    /**
     * @param $students
     * @return bool
     */
    protected function checkUniqueValues($students)
    {
        $tempArr = array_unique(array_column($students, 'email'));
        $newArr = array_intersect_key($students, $tempArr);

        if (count($students) != count($newArr)) {
            return true;
        }

        $tempArr = array_unique(array_column($students, 'studentnumber'));
        $newArr = array_intersect_key($students, $tempArr);

        if (count($students) != count($newArr)) {
            return true;
        }

        return false;
    }

}
