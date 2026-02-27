<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LmsAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize tenant for Stancl/Tenancy
        $this->tenant = \App\Models\Tenant::create(['id' => 'test-tenant']);
        $this->tenant->domains()->create(['domain' => 'test.localhost']);
        
        tenancy()->initialize($this->tenant);
        
        // Seed basic roles from spatie/permission
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function tearDown(): void
    {
        tenancy()->end();
        parent::tearDown();
    }

    public function test_unenrolled_student_cannot_access_media()
    {
        $student = \App\Models\User::factory()->create();
        $student->assignRole('Student');

        $lesson = \App\Models\LMS\Lesson::factory()->create([
            'file_url' => 'dummy_video.mp4',
            'type' => 'video',
            'is_published' => true,
        ]);

        $response = $this->actingAs($student)
            ->get(route('student.media.stream', ['lesson' => $lesson->id]));

        $response->assertStatus(403);
    }
}
