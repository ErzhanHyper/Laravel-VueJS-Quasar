<?php

namespace App\Http\Resources;

use App\Models\ApplicationComment;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'text' => $this->text,
            'category' => new ApplicationCategoryResource($this->application_category),
            'employee' => new EmployeeResource($this->application_employee),
            'files' => new ApplicationFileResource($this->application_file),
            'date' => $this->created_at,
            'status' =>  new StatusResource($this->status),
            'comments' =>  ApplicationCommentResource::collection($this->comments),
        ];
    }
}
