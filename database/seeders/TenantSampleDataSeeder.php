<?php

namespace Database\Seeders;

use App\Models\CMS\Post;
use App\Models\CRM\Contact;
use App\Models\LMS\Lesson;
use App\Models\LMS\Module;
use App\Models\SIS\AcademicYear;
use App\Models\SIS\Course;
use App\Models\SIS\CourseAccess;
use App\Models\SIS\Enrollment;
use App\Models\SIS\Program;
use App\Models\SIS\Term;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TenantSampleDataSeeder extends Seeder
{
    public function run()
    {
        // Add Admin user
        User::firstOrCreate(
            ['email' => 'admin@zainab.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Add sample users
        $instructor1 = User::firstOrCreate(
            ['email' => 'instructor1@zainabcenter.org'],
            [
                'name' => 'Shaykh Ahmad',
                'password' => bcrypt('password123'),
                'roll_number' => 'INS-001',
            ]
        );

        $instructor2 = User::firstOrCreate(
            ['email' => 'instructor2@zainabcenter.org'],
            [
                'name' => 'Ustadha Fatima',
                'password' => bcrypt('password123'),
                'roll_number' => 'INS-002',
            ]
        );

        $student1 = User::firstOrCreate(
            ['email' => 'student1@example.com'],
            [
                'name' => 'Ali Raza',
                'password' => bcrypt('password123'),
                'roll_number' => 'STU-1001',
            ]
        );

        $student2 = User::firstOrCreate(
            ['email' => 'student2@example.com'],
            [
                'name' => 'Sara Khan',
                'password' => bcrypt('password123'),
                'roll_number' => 'STU-1002',
            ]
        );

        // Academic Year
        $year = AcademicYear::firstOrCreate(
            ['name' => '2024-2025'],
            [
                'start_date' => Carbon::parse('2024-09-01'),
                'end_date' => Carbon::parse('2025-06-30'),
                'is_active' => true,
            ]
        );

        // Term
        $termFall = Term::firstOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Fall 2024'],
            [
                'start_date' => Carbon::parse('2024-09-01'),
                'end_date' => Carbon::parse('2024-12-15'),
                'is_current' => false,
            ]
        );

        $termSpring = Term::firstOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Spring 2025'],
            [
                'start_date' => Carbon::parse('2025-01-10'),
                'end_date' => Carbon::parse('2025-05-30'),
                'is_current' => true,
            ]
        );

        // Program
        $program1 = Program::firstOrCreate(
            ['code' => 'UA1'],
            [
                'name' => 'Urdu Aama 1',
                'description' => 'First year of the foundational Alim course.',
                'price' => 500.00,
                'billing_cycle' => 'monthly',
                'duration_months' => 9,
                'level' => 'Beginner',
                'is_active' => true,
            ]
        );

        // Courses
        $course1 = Course::firstOrCreate(
            ['code' => 'TAF101'],
            [
                'name' => 'Tafseer Introduction',
                'description' => 'Introduction to the sciences of the Quran.',
                'credits' => 3,
                'capacity' => 50,
                'term_id' => $termSpring->id,
                'is_active' => true,
            ]
        );

        $course2 = Course::firstOrCreate(
            ['code' => 'HAD101'],
            [
                'name' => 'Hadith Principles',
                'description' => 'Study of Usul al-Hadith and memorization.',
                'credits' => 3,
                'capacity' => 50,
                'term_id' => $termSpring->id,
                'is_active' => true,
            ]
        );

        // Enrollment
        $enrollment1 = Enrollment::firstOrCreate(
            [
                'user_id' => $student1->id,
                'program_id' => $program1->id,
                'term_id' => $termSpring->id,
            ],
            [
                'status' => 'Enrolled',
                'enrolled_at' => Carbon::now(),
                'amount_paid' => 100.00,
                'payment_method' => 'stripe_online',
            ]
        );

        // Course Access
        CourseAccess::firstOrCreate(
            [
                'enrollment_id' => $enrollment1->id,
                'course_id' => $course1->id,
            ],
            [
                'is_active' => true,
            ]
        );

        // Module
        $module1 = Module::firstOrCreate(
            ['course_id' => $course1->id, 'title' => 'Revelation (Wahy)'],
            [
                'description' => 'Understanding how revelation descends and its categorizations.',
                'sequence' => 1,
                'is_published' => true,
            ]
        );

        // Lesson
        Lesson::firstOrCreate(
            ['module_id' => $module1->id, 'title' => 'The First Revelation'],
            [
                'content' => '<p>Overview of Surah Al-Alaq and the historical event in Cave Hira.</p>',
                'type' => 'text',
                'sequence' => 1,
                'is_published' => true,
            ]
        );

        // Post
        Post::firstOrCreate(
            ['slug' => 'welcome-spring-2025'],
            [
                'title' => 'Welcome to Spring 2025 Semester!',
                'post_type' => 'post',
                'content' => '<p>We are excited to welcome all new and returning students to the Spring term.</p>',
                'status' => 'published',
                'published_at' => Carbon::now(),
                'author_user_id' => $instructor1->id,
            ]
        );

        // Contact
        Contact::firstOrCreate(
            ['email' => 'parent@example.com'],
            [
                'name' => 'Ahmad Raza',
                'contact_type' => 'Parent',
                'phone' => '+1234567890',
            ]
        );
    }
}
