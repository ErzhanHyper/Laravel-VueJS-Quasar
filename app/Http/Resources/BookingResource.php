<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'employee' => new EmployeeResource($this->employee),
            'department' => $this->department,
            'time_start' => Carbon::parse($this->time_start)->format('H:i'),
            'time_end' =>  Carbon::parse($this->time_end)->format('H:i'),
            'room' => $this->room,
        ];
    }
}
