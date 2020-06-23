<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentsResponse;
use App\Http\Resources\UserResponse;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Coaching planner API Server"
 * )
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/user/students/{user}",
     *      operationId="get_students_of_coach",
     *      tags={"User"},
     *      summary="Get list of students",
     *      description="Returns list of students of the coach",
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
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getStudents(User $user)
    {
        return StudentsResponse::collection($user->students);
    }

    /**
     *  @OA\Get(
     *      path="/user/{user}",
     *      operationId="get_user_details",
     *      tags={"User"},
     *      summary="Get Coach (user) details",
     *      description="Returns details of the user (coach)",
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
     * @param User $user
     * @return UserResponse
     */
    public function getUser(User $user)
    {
        return new UserResponse($user);
    }

}
