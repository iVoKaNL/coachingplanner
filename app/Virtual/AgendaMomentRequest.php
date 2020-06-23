<?php


namespace App\Virtual;

/**
 * @OA\Schema(
 *      title="Assign agenda moment request",
 *      description="Assign agenda moment to student of coach request body data",
 *      type="object",
 *      required={"guid", "start_time", "end_time"}
 * )
 */
class AgendaMomentRequest
{
    /**
     * @OA\Property(
     *      title="guid",
     *      description="Guid of the student that want to assign",
     *      example="9c058e90-7ef1-11ea-a2bd-6db2999910ca"
     * )
     *
     * @var string
     */
    public $guid;

    /**
     * @OA\Property(
     *      title="start_time",
     *      description="Start time of the assigned moment",
     *      example="2020-03-10 15:00:00",
     *      format="datetime",
     * )
     *
     * @var \DateTime
     */
    public $start_time;

    /**
     * @OA\Property(
     *      title="end_time",
     *      description="End time of the assigned moment",
     *      example="2020-03-10 15:30:00",
     *      format="datetime",
     * )
     *
     * @var \DateTime
     */
    public $end_time;
}