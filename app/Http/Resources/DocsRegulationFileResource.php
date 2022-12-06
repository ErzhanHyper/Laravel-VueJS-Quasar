<?php

namespace App\Http\Resources;

use App\Models\DocsRegulationFileAddition;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DocsRegulationFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $addition_file = [];
        if(count($this[0]->additionFile) > 0){
            foreach ($this[0]->additionFile as $item) {
                $item->file = config('app.url') . '/storage/uploads/' . $item->file;
                $addition_file[] = $item;
            }
        }

        return [
            'id' => $this[0]->id,
            'name' => $this[0]->name,
            'file' => config('app.url') . '/storage/uploads/' . $this[0]->file,
            'employee' => new EmployeeResource($this[0]->employee),
            'date' => $this[0]->created_at,
            'agency' => $this[0]->agency,
            'date_start' => Carbon::parse($this[0]->date_start)->format('Y-m-d'),
            'date_approve' => Carbon::parse($this[0]->date_approve)->format('Y-m-d'),
            'department_id' => $this[0]->department_id,
            'department' => $this[0]->department,
            'docs_regulation_id' => $this[0]->docs_regulation_id,
            'addition_file' => $addition_file,
            'dynamic_file' => $this[0]->dynamic_file
        ];
    }
}
