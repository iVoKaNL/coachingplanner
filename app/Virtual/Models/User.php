<?php


namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User
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
     *      title="Firstname",
     *      description="Firstname of the user",
     *      example="Steve"
     * )
     *
     * @var string
     */
    public $firstname;

    /**
     * @OA\Property(
     *      title="Suffix",
     *      description="Suffix of the user",
     *      example="van"
     * )
     *
     * @var string
     */
    public $suffix;

    /**
     * @OA\Property(
     *      title="Lastname",
     *      description="Lastname of the user",
     *      example="Jobs"
     * )
     *
     * @var string
     */
    public $lastname;

    /**
     * @OA\Property(
     *      title="Password",
     *      description="Password of the user",
     *      example="password"
     * )
     *
     * @var string
     */
    public $password;

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
     *      title="Username",
     *      description="Username of the user",
     *      format="string",
     *      example="username"
     * )
     *
     * @var string
     */
    public $username;

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
}