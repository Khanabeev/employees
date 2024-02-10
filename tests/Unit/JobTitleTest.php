<?php

namespace Tests\Unit;

use Domain\JobTitle\Models\JobTitle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobTitleTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_can_create_a_job_title()
    {
        $jobTitle = JobTitle::factory()->create(['title' => 'Software Engineer']);

        $this->assertDatabaseHas('job_titles', ['title' => 'Software Engineer']);
    }
}
