<?php

namespace Domain\JobTitle\Models;

use Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }
}
