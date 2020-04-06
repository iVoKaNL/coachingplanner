<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaMomentRequest;
use App\Http\Resources\AgendaResponse;
use App\Http\Resources\NextCoachingMomentResponse;
use App\Mail\NotifyStudents;
use App\Http\Resources\WeekOverviewResponse;
use App\Models\Agenda;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AgendaController extends Controller
{

    //TODO: add description to agenda class

    public function index(Request $request)
    {
        $user = Auth::user();

        $startDate = Carbon::parse($request->input('StartDate'));
        $endDate = Carbon::parse($request->input('EndDate'));

        AgendaResponse::withoutWrapping();

        return AgendaResponse::collection($user->agendas()->whereDate('start_time', '>=', $startDate)->whereDate('start_time', '<=', $endDate)->get());
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if($request->input('action') == "insert" || ($request->input('action') == "batch" && $request->input('added') != null)) {
            $value = null;
            if($request->input('action') == "insert") {
                $value = $request->input('action');
            } else {
                $value = $request->input('added')[0];
            }
            $startTime = Carbon::parse($value['StartTime']);
            $endTime = Carbon::parse($value['EndTime']);
            $subject = $value['Subject'];

            Agenda::create([
                'subject' => $value['Subject'],
                'location' => $value['Location'] ?? null,
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

            $startTime = Carbon::parse($value['StartTime']);
            $endTime = Carbon::parse($value['EndTime']);
            $agendaItem = Agenda::where('id', $value['Id'])->first();

            $agendaItem->subject = $value['Subject'];
            $agendaItem->location = $value['Location'] ?? null;
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
                        'assigned' => $full]);
                }
                return ['date' => $startTime, 'moments' => $dates];
            });

        return $agendaMoments;
    }

    public function AssignCoachingMoment(User $user, AgendaMomentRequest $request)
    {
        $student = Student::where('guid', $request->input('guid'))->first();

        $studentName = $student->firstname . " ";
        $student->suffix != null ? $studentName .= $student->suffix . " " : null;
        $studentName .= $student->lastname;

        $location = Agenda::where('start_time', '<=', $request->input('start_time'))
            ->where('end_time', '>=', $request->input('end_time'))
            ->first('location')->location;

        Agenda::create([
            'subject' => $studentName,
            'location' => $location,
            'start_time' => Carbon::parse($request->input('start_time'))->toDateTimeString(),
            'end_time' => Carbon::parse($request->input('end_time'))->toDateTimeString(),
            'coach_id' => $user->id,
            'student_id' => $student->id
        ]);

        return response()->json('Coaching moment is succesfully assigned');
    }

    public function getNextCoachingMoment()
    {
        $user = Auth::user();

        return new NextCoachingMomentResponse($user->agendas()->whereNotNull("student_id")->whereDate("start_time", ">=", Carbon::now()->toDateTimeString())->orderBy("start_time")->first());
    }

    public function notifyStudents()
    {
        $user = Auth::user();

        $students = $user->students()->get();
        $when = now();
        foreach ($students as $student) {
            Mail::to($student)->later($when ,new NotifyStudents($student, $user->guid, $user->fullName));
            $when = $when->addSeconds(3);
        }

        return response()->json('Send mail to students');
    }

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
