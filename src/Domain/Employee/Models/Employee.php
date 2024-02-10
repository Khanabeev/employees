<?php

namespace Domain\Employee\Models;

use Domain\Department\Models\Department;
use Domain\Employee\Builders\EmployeeBuilder;
use Domain\EmploymentType\Models\EmploymentType;
use Domain\JobTitle\Models\JobTitle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'department_id',
        'job_title_id',
        'employment_type_id',
        'salary',
        'hourly_rate'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function employmentType(): BelongsTo
    {
        return $this->belongsTo(EmploymentType::class);
    }

    public function newEloquentBuilder($query): EmployeeBuilder
    {
        return new EmployeeBuilder($query);
    }
}
