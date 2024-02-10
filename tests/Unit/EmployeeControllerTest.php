<?php

namespace Tests\Unit;

use Domain\Department\Models\Department;
use Domain\Employee\Models\Employee;
use Domain\EmploymentType\Models\EmploymentType;
use Domain\JobTitle\Models\JobTitle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function can_search_for_employees_by_name()
    {
        $jobTitle = JobTitle::factory()->create([
            'title' => 'Software Engineer',
        ]);
        $employmentType = EmploymentType::factory()->create([
            'type' => 'Salary',
            'full_or_part_time' => 'F',
        ]);
        $department = Department::factory()->create([
            'name' => 'Engineering',
        ]);

        Employee::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'job_title_id' => $jobTitle->id,
            'employment_type_id' => $employmentType->id,
            'department_id' => $department->id,
        ]);

        $response = $this->json('GET', '/api/employees/search', ['first_name' => 'John']);

        $response->assertStatus(200)
            ->assertJsonFragment([
                "first_name" => "John",
                "last_name" => "Doe",
                "job_title" => "Software Engineer",
                "employment_type" => "Salary",
                "full_or_part_time" => "F",
                "department" => "Engineering",
            ]);
    }
}
