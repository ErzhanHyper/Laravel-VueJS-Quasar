<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DocsContractResource extends JsonResource
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
            'contract_id' => $this->contract_id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'agent' => $this->agent,
            'amount' => $this->amount,
            'date_start' => Carbon::parse($this->date_start)->format('Y-m-d'),
            'date_end' => Carbon::parse($this->date_end)->format('Y-m-d'),
            'date' => Carbon::parse($this->date)->format('Y-m-d'),
            'file' =>  ($this->file) ? config('app.url') . '/storage/uploads/' . $this->file->name : '',
            'department' => $this->department,
            'date_service' => Carbon::parse($this->date_service)->format('Y-m-d'),
        ];
    }
}
