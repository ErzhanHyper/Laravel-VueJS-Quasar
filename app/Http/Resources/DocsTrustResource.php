<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocsTrustResource extends JsonResource
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
            'name' => $this->name,
            'warrant_id' => $this->docs_trust_file->warrant_id,
            'file' => config('app.url') . '/storage/uploads/' . $this->docs_trust_file->file,
            'employee' => new EmployeeResource($this->docs_trust_file->employee),
            'date' => $this->docs_trust_file->date,
            'date_expiration_start' => $this->docs_trust_file->date_expiration_start,
            'date_expiration_end' => $this->docs_trust_file->date_expiration_end,
            'entrust' => $this->docs_trust_file->entrust,
            'direction' => $this->docs_trust_file->direction,
            'department' =>  new DepartmentResource($this->docs_trust_file->department),
            'profession' => new ProfessionResource($this->docs_trust_file->profession),
            'agent' => $this->docs_trust_file->agent,
            'docs_type' => $this->docs_trust_file->docs_type,
        ];
    }
}
