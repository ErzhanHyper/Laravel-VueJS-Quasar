<?php

namespace App\Http\Resources;

use App\Models\DocsRegulationFiles;
use App\Models\DocsType;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocsRegulationResource extends JsonResource
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
            'name' => $this->name,
            'file_type' => $this->docs_type,
            'agency' => $this->agency,
            'date_start' => Carbon::parse($this->date_start)->format('Y-m-d'),
            'date_approve' => Carbon::parse($this->date_approve)->format('Y-m-d'),
            'department' => $this->department,
            'department_id' => $this->department_id,
            'file_type_id' => $this->file_type_id,
            'date' => $this->created_at,
            'docs_regulation_id' => $this->docs_regulation_id,
            'docs_regulation' => new DocsRegulationFileResource($this->docs_regulation_file),
            'viewers' => $this->viewers
        ];
    }
}
