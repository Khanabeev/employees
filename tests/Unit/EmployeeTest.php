<?php

namespace Tests\Unit;

use Domain\Employee\Models\Employee;
use Domain\JobTitle\Models\JobTitle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_employee()
    {
        Employee::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertDatabaseHas('employees', ['first_name' => 'John', 'last_name' => 'Doe']);
    }
}
