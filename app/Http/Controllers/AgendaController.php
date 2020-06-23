<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaMomentRequest;
use App\Http\Resources\AgendaResponse;
use App\Http\Resources\NextCoachingMomentResponse;
use App\Mail\Confirmation;
use App\Mail\NotifyStudents;
use App\Http\Resources\WeekOverviewResponse;
use App\Models\Agenda;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/**
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Coaching planner API Server"
 * )
 * Class UserController
 * @package App\Http\Controllers
 */
class AgendaController extends Controller
{

    /**
     * @OA\Post(
     *      path="/agenda",
     *      operationId="get_agenda",
     *      tags={"Agenda"},
     *      summary="Get agenda of coach",
     *      description="Returns agenda of coaches to build agenda frontend",
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
        $user = Auth::user();

        $startDate = Carbon::parse($request->input('StartDate'));
        $endDate = Carbon::parse($request->input('EndDate'));

        AgendaResponse::withoutWrapping();

        return AgendaResponse::collection($user->agendas()->whereDate('start_time', '>=', $startDate)->whereDate('start_time', '<=', $endDate)->get());
    }

    /**
     * @OA\Post(
     *      path="/agenda/update",
     *      operationId="update_agenda",
     *      tags={"Agenda"},
     *      summary="Update agenda of coach",
     *      description="Update, create, delete agenda moment of coach",
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
     * @throws \Exception
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $date = new Carbon('2020-10-25');
        $addHours = 2;
        if(Carbon::now()->isAfter($date)) {
            $addHours = 1;
        }


        if($request->input('action') == "insert" || ($request->input('action') == "batch" && $request->input('added') != null)) {
            $value = null;
            if($request->input('action') == "insert") {
                $value = $request->input('action');
            } else {
                $value = $request->input('added')[0];
            }

            $startTime = Carbon::parse($value['StartTime'])->addHours($addHours);
            $endTime = Carbon::parse($value['EndTime'])->addHours($addHours);
            $subject = $value['Subject'];

            Agenda::create([
                'subject' => $value['Subject'],
                'location' => $value['Location'] ?? null,
                'description' => $value['Description'] ?? null,
                'start_time' => $startTime->toDateTimeString(),
                'end_time' => $endTime->toDateTimeString(),
                'coach_id' => $user->id
            ]);
        }

        if($request->input('action') == "update" || ($request->input('action') == "batch" && $request->input('changed') != null)) {
            $value = null;
            if($request->input('action') == "insert") {
                $value = $request->input('action');
            } else {
                $value = $request->input('changed')[0];
            }

            $startTime = Carbon::parse($value['StartTime'])->addHours($addHours);
            $endTime = Carbon::parse($value['EndTime'])->addHours($addHours);
            $agendaItem = Agenda::where('id', $value['Id'])->first();

            $agendaItem->subject = $value['Subject'];
            $agendaItem->location = $value['Location'] ?? null;
            $agendaItem->description = $value['Description'] ?? null;
            $agendaItem->start_time = $startTime->toDateTimeString();
            $agendaItem->end_time = $endTime->toDateTimeString();
            $agendaItem->save();
        }

        if($request->input('action') == "remove" || ($request->input('action') == "batch" && $request->input('deleted') != null)) {
            if($request->input('action') == "remove") {
                $id = $request->input('key');
                $agendaItem = Agenda::where('id', $value['Id'])->first();
                $agendaItem->delete();
            } else {
                foreach($request->input('deleted') as $apps) {
                    $agendaItem = Agenda::where('id', $apps['Id'])->first();
                    $agendaItem->delete();
                }
            }
        }
    }

    /**
     * @OA\Get(
     *      path="/agenda/moments/{user}",
     *      operationId="get_agenda_moments_coach",
     *      tags={"Agenda"},
     *      summary="Get agenda moments of coach per half an hour",
     *      description="Returns agenda moments per day and per half an hour of coaches for students of coach",
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
     * @return mixed
     */
    public function getCoachingMoments(User $user)
    {
        $agendaMoments = Agenda::where('coach_id', $user->id)
            ->whereDate('start_time', '>=', Carbon::now())
            ->whereDate('start_time', '<', Carbon::now()->addDays(14))
            ->whereNull('student_id')
            ->orderBy('start_time')
            ->get()
            ->transform(function ($agenda) {
                $startTime = Carbon::parse($agenda->start_time);
                $endTime = Carbon::parse($agenda->end_time);
                $amountHalfHour = $endTime->diffInMinutes($startTime) / 30;
                $dates = [];
                for($i = 0; $i < $amountHalfHour; $i++) {
                    $addMinutes = $i * 30;
                    $time = $startTime->clone()->addMinutes($addMinutes);
                    if($time->isBefore(Carbon::now()->setTimezone('Europe/Amsterdam')->toDateTimeString())) {
                        continue;
                    }
                    $full = Agenda::whereNotNull('student_id')
                        ->where(function($q) use ($time) {
                            $q->where('start_time', '>=' , $time->toDateTimeString());
                            $q->where('start_time', '<', $time->clone()->addMinutes(30)->toDateTimeString());
                        })
                        ->orWhere(function($q) use ($time) {
                            $q->where('end_time', '>' , $time->toDateTimeString());
                            $q->where('end_time', '<', $time->clone()->addMinutes(30)->toDateTimeString());
                        })
                        ->exists();
                    array_push($dates, [
                        'id' => $agenda->id,
                        'subject' => $agenda->subject,
                        'start_time' => $time,
                        'end_time' => $time->clone()->addMinutes(30),
                        'location' => $agenda->location,
                        'description' => $agenda->description,
                        'assigned' => $full]);
                }
                return ['date' => $startTime, 'moments' => $dates];
            })
            ->filter(function ($moment) {
                if(empty($moment['moments'])) {
                    return null;
                }
                return $moment;
            })->values();

        return $agendaMoments;
    }

    /**
     * @OA\Post(
     *      path="/agenda/moment/{user}",
     *      operationId="assign_agenda_moment_to_student",
     *      tags={"Agenda"},
     *      summary="Assign coaching moment to student",
     *      description="Assigning coaching moment to student of the coach",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AgendaMomentRequest")
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
     * @param User $user
     * @param AgendaMomentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AssignCoachingMoment(User $user, AgendaMomentRequest $request)
    {
        $student = Student::where('guid', $request->input('guid'))->first();

        $studentName = $student->firstname . " ";
        $student->suffix != null ? $studentName .= $student->suffix . " " : null;
        $studentName .= $student->lastname;

        $parent = Agenda::where('start_time', '<=', $request->input('start_time'))
            ->where('end_time', '>=', $request->input('end_time'))
            ->first();

        $agenda = Agenda::create([
            'subject' => $studentName,
            'location' => $parent->location,
            'description' => $parent->description,
            'start_time' => Carbon::parse($request->input('start_time'))->toDateTimeString(),
            'end_time' => Carbon::parse($request->input('end_time'))->toDateTimeString(),
            'coach_id' => $user->id,
            'student_id' => $student->id
        ]);

        Mail::to($student)->send(new Confirmation($student, $agenda));

        return response()->json('Coaching moment is succesfully assigned');
    }

    /**
     * @OA\Get(
     *      path="/agenda/next",
     *      operationId="get_next_agenda_moment",
     *      tags={"Agenda"},
     *      summary="Get next coaching moment",
     *      description="Returns details of the next coaching moment for the coach",
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
     * @return NextCoachingMomentResponse
     */
    public function getNextCoachingMoment()
    {
        $user = Auth::user();

        return new NextCoachingMomentResponse($user->agendas()->whereNotNull("student_id")->whereDate("start_time", ">=", Carbon::now()->toDateTimeString())->orderBy("start_time")->first());
    }

    /**
     * @OA\Get(
     *      path="agenda/students/notify",
     *      operationId="notify_students",
     *      tags={"Agenda"},
     *      summary="Notify students to assign to coaching moment",
     *      description="Sends mail to all the students of the coach to assign for a coaching moment",
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function notifyStudents()
    {
        $user = Auth::user();

        $students = $user->students()->get();
        foreach ($students as $student) {
            Mail::to($student)->send(new NotifyStudents($student, $user->guid, $user->fullName));
        }

        return response()->json('Send mail to students');
    }

    /**
     * @OA\Get(
     *      path="agenda/overview/week",
     *      operationId="agenda_overview_week",
     *      tags={"Agenda"},
     *      summary="Get week overview of the coaching moments",
     *      description="Returns number per day of the week with the amount of coaching moments",
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
     * @return WeekOverviewResponse
     */
    public function getWeekOverview()
    {
        $user = Auth::user();
        $days = [
            'monday' => 0,
            'tuesday' => 0,
            'wednesday' => 0,
            'thursday' => 0,
            'friday' => 0
        ];

        return new WeekOverviewResponse($user->agendas()
            ->whereNotNull("student_id")
            ->whereDate("start_time", ">=", Carbon::now()->startOfWeek()->toDateTimeString())
            ->whereDate("end_time", "<=", Carbon::now()->endOfWeek()->toDateTimeString())
            ->get()
            ->transform(function($moment) use (&$days) { // The & before $days means it is a reference (so we can edit it)
                switch (Carbon::parse($moment->start_time)->dayOfWeek) {
                    case 1:
                        $days['monday']++;
                        break;
                    case 2:
                        $days['tuesday']++;
                        break;
                    case 3:
                        $days['wednesday']++;
                        break;
                    case 4:
                        $days['thursday']++;
                        break;
                    case 5:
                        $days['friday']++;
                        break;
                }

                return $days;
            })->last());
    }
}
