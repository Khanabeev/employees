<?php

namespace Domain\Employee\Controllers\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'job_title' => $this->jobTitle->title,
            'employment_type' => $this->employmentType->type,
            'full_or_part_time' => $this->employmentType->full_or_part_time,
            'department' => $this->department->name,
            'salary' => $this->salary,
            'hourly_rate' => $this->hourly_rate
        ];
    }
}
