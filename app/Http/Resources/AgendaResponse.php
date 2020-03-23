<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResponse extends JsonResource
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
            'Id' => $this->id,
            'Subject' => $this->subject,
            'Location' => $this->location,
            'StartTime' => $this->start_time,
            'EndTime' => $this->end_time,
            $this->mergeWhen($this->hasStudent(), function () {
                return [
                    'CategoryColor' => '#ea7a57'
                ];
            }),
        ];
    }
}
