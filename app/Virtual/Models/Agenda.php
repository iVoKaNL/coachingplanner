<?php


namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Agenda",
 *     description="Agenda model",
 *     @OA\Xml(
 *         name="Agenda"
 *     )
 * )
 */
class Agenda
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *     title="Subject",
     *     description="Subject/Title of the agenda moment",
     *     format="string",
     *     example="Conversation"
     * )
     *
     * @var string
     */
    private $subject;

    /**
     * @OA\Property(
     *     title="Location",
     *     description="Location of the agenda moment",
     *     format="string",
     *     example="t1.01"
     * )
     *
     * @var string
     */
    private $location;

    /**
     * @OA\Property(
     *     title="Descripiton",
     *     description="Description of the agenda moment",
     *     format="string",
     *     example="Talking about school"
     * )
     *
     * @var string
     */
    private $description;

    /**
     * @OA\Property(
     *     title="Start time",
     *     description="Start time of agenda moment",
     *     example="2020-01-27 15:00:00",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $start_time;

    /**
     * @OA\Property(
     *     title="End time",
     *     description="End time of agenda moment",
     *     example="2020-01-27 17:00:00",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $end_time;

    /**
     * @OA\Property(
     *     title="Created at",
     *     description="Created at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $created_at;

    /**
     * @OA\Property(
     *     title="Updated at",
     *     description="Updated at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $updated_at;

    /**
     * @OA\Property(
     *      title="Coach (id)",
     *      description="Id of students coach",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $coach_id;

    /**
     * @OA\Property(
     *      title="Coach",
     *      description="Coach of the student model",
     * )
     *
     * @var User
     */
    public $coach;

    /**
     * @OA\Property(
     *      title="Student (id)",
     *      description="Id of the assigned student",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $student_id;

    /**
     * @OA\Property(
     *      title="Student",
     *      description="Student assigned to agenda moment model",
     * )
     *
     * @var User
     */
    public $student;
}