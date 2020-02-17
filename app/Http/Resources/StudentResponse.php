<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'publicId' => $this->guid,
            'studentnumber' => $this->studentnumber,
            'firstname' => $this->firstname,
            'suffix' => $this->suffix,
            'lastname' => $this->lastname,
            'email' => $this->email
        ];
    }
}
