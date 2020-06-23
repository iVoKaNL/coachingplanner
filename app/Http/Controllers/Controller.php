<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Coaching planner backend Documentation",
     *      description="L5 Swagger OpenApi description",
     *      @OA\Contact(
     *          email="rubenvdkamp08@gmail.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     * @OA\SecurityScheme(
     *     type="apiKey",
     *     in="header",
     *     name="Authorization",
     *     securityScheme="coachingplanner_auth_key",
     * )
     */
}
