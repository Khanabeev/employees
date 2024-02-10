<?php

namespace Domain\EmploymentType\Models;

use Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'full_or_part_time'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
