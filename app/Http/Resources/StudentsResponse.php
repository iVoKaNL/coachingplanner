<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentsResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //TODO: spacing for suffix
        return [
            'value' => $this->guid,
            'label' => $this->firstname . $this->suffix . " " . $this->lastname
        ];
    }
}
