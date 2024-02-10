<?php

namespace Database\Factories\Domain\EmploymentType\Models;

use Domain\EmploymentType\Models\EmploymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmploymentTypeFactory extends Factory
{
    protected $model = EmploymentType::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['Salary', 'Hourly']),
            'full_or_part_time' => $this->faker->randomElement(['F', 'P']),
        ];
    }
}
