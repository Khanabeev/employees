<?php

namespace Tests\Unit;

use Domain\EmploymentType\Models\EmploymentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmploymentTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_employment_type()
    {
        EmploymentType::factory()->create([
            'type' => 'Salary',
            'full_or_part_time' => 'F',
        ]);

        $this->assertDatabaseHas('employment_types', ['type' => 'Salary']);
    }
}
