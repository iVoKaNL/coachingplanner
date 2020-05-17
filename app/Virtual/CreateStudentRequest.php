<?php


namespace App\Virtual;

/**
 * @OA\Schema(
 *      title="Create student request",
 *      description="Create new student request body data",
 *      type="object",
 *      required={"studentnumber", "firstname", "lastname", "email"}
 * )
 */
class CreateStudentRequest
{
    /**
     * @OA\Property(
     *      title="studentnumber",
     *      description="Studentnumber of the new student",
     *      example="1112200"
     * )
     *
     * @var string
     */
    public $studentnumber;

    /**
     * @OA\Property(
     *      title="firstname",
     *      description="Firstname of the new student",
     *      example="Peter"
     * )
     *
     * @var string
     */
    public $firstname;

    /**
     * @OA\Property(
     *      title="suffix",
     *      description="Suffix of the new student",
     *      example="de"
     * )
     *
     * @var string
     */
    public $suffix;

    /**
     * @OA\Property(
     *      title="lastname",
     *      description="Lastname of the new student",
     *      example="Johnson"
     * )
     *
     * @var string
     */
    public $lastname;

    /**
     * @OA\Property(
     *      title="email",
     *      description="School mailof the new student",
     *      example="s1112200@student.windesheim.nl"
     * )
     *
     * @var string
     */
    public $email;
}
