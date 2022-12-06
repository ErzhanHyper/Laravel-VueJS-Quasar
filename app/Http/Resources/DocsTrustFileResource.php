<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocsTrustFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'warrant_id' => $this->warrant_id,
            'file' => config('app.url') . '/storage/uploads/' . $this->file,
            'employee' => new EmployeeResource($this->employee),
            'date' => $this->date,
            'date_expiration_start' => $this->date_expiration_start,
            'date_expiration_end' => $this->date_expiration_end,
            'entrust' => $this->entrust,
            'direction' => $this->direction,
            'department' => new DepartmentResource($this->department),
            'profession' => new ProfessionResource($this->profession),
            'agent' => $this->agent,
            'docs_type' => $this->docs_type,
        ];
    }
}
