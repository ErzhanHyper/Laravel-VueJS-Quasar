<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $photo_base64 = '';
        if ($this->photo) {
            $file = @file_get_contents($this->photo);
            if ($file === FALSE) {
                $photo_base64 = '';
            }else{
                $photo_base64 = 'data:image/jpeg;base64,' . base64_encode($file);
            }
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'text' => $this->text,
            'photo' => ($this->photo) ? config('app.url') . '/storage/uploads/' . $this->photo : '',
            'email' => $this->email,
            'phone' => $this->phone,
            'extension' => $this->extension,
            'birthdate' => $this->birthdate,
            'cabinet' => $this->cabinet,
            'fullname' => $this->lastname . ' ' . $this->firstname . ' ' . $this->middlename,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'employee_status_id' => $this->employee_status_id,
            'status' => new EmployeeStatusResource($this->employee_status),
            'files' => new EmployeeFileResource($this->employee_file),
            'profession' => new ProfessionResource($this->profession),
            'department' => new DepartmentResource($this->department),
            'date' => $this->created_at,
            'user' => new UserResource($this->user),

            'photo_base64' => $photo_base64

        ];
    }
}
