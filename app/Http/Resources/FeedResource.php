<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedResource extends JsonResource
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
            'category' => new FeedCategoryResource($this->feed_category),
            'employee' => new EmployeeResource($this->feed_employee),
            'text' => $this->text,
            'date' => $this->created_at
        ];

    }
}
