<?php


namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Student",
 *     description="Student model",
 *     @OA\Xml(
 *         name="Student"
 *     )
 * )
 */
class Student
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
     *     title="GUID",
     *     description="GUID",
     *     format="string",
     *     example="kl2o-asle-12li-klsad"
     * )
     *
     * @var string
     */
    private $guid;

    /**
     * @OA\Property(
     *     title="Studentnumber",
     *     description="Studentnumber",
     *     format="int64",
     *     example=1112233
     * )
     *
     * @var integer
     */
    private $studentnumber;

    /**
     * @OA\Property(
     *      title="Firstname",
     *      description="Firstname of the student",
     *      example="Steve"
     * )
     *
     * @var string
     */
    public $firstname;

    /**
     * @OA\Property(
     *      title="Suffix",
     *      description="Suffix of the student",
     *      example="van"
     * )
     *
     * @var string
     */
    public $suffix;

    /**
     * @OA\Property(
     *      title="Lastname",
     *      description="Lastname of the student",
     *      example="Jobs"
     * )
     *
     * @var string
     */
    public $lastname;

    /**
     * @OA\Property(
     *      title="Email",
     *      description="Email of the user",
     *      format="string",
     *      example="example@example.nl"
     * )
     *
     * @var string
     */
    public $email;

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
}