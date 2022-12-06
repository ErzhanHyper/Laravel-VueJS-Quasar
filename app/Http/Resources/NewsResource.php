<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'image' => ($this->image) ? config('app.url') . '/storage/uploads/' . $this->image : '',
            'file' => ($this->file) ? config('app.url') . '/storage/uploads/' . $this->file : '',
            'title' => $this->title,
            'text' => $this->text,
            'publish' => ($this->publish === 1),
            'created_at' => $this->created_at,
            'chat' => ($this->chat === 1),
            'imgFullWidth' => ($this->img_full_width === 1),
        ];
    }
}
