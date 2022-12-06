<?php

namespace App\Http\Resources;

use App\Models\Employee;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $employee_ids = [];
        $files = [];

        foreach ($this->employees as $item){
            $employee_ids[] = $item->employee_id;
        }

        $employees = Employee::whereIn('id', $employee_ids)->orderBy('lastname')->get();

        foreach ($this->files as $item){
            $files[] = [
                'name' => $item->name,
                'file' => config('app.url') . '/storage/uploads/' . $item->file,
                'date' => $item->created_at
            ];
        }
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'employee' => $this->employee,
            'employee_id' => $this->employee_id,
            'employee_ids' => $employee_ids,
            'employees' => $employees,
            'files' => $files,
        ];
    }
}
