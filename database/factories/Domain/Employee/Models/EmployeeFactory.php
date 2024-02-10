<?php

namespace Database\Factories\Domain\Employee\Models;

use Domain\Department\Models\Department;
use Domain\Employee\Models\Employee;
use Domain\EmploymentType\Models\EmploymentType;
use Domain\JobTitle\Models\JobTitle;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->lastName,
            'department_id' => Department::factory(),
            'job_title_id' => JobTitle::factory(),
            'employment_type_id' => EmploymentType::factory(),
            'salary' => $this->faker->optional()->numberBetween(40000, 100000),
            'hourly_rate' => $this->faker->optional()->randomFloat(2, 20, 50),
        ];
    }
}
