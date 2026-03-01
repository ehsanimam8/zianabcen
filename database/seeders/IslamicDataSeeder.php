<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class IslamicDataSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure all required roles exist before assigning them
        $guardName = 'web';
        foreach (['Super Admin', 'Admin', 'Instructor', 'Student'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => $guardName]);
        }

        // Create Teacher
        $teacher = \App\Models\User::firstOrCreate(
            ['email' => 'teacher.islamic@zainabcenter.test'],
            [
                'name'     => 'Shaykh Abdullah',
                'password' => bcrypt('password'),
                'gender'   => 'Male',
            ]
        );
        $teacher->assignRole('Instructor');

        // Create Islamic Students
        $studentsData = [
            ['name' => 'Aisha Rahman', 'email' => 'aisha@zainabcenter.test', 'gender' => 'Female'],
            ['name' => 'Fatima Ali',   'email' => 'fatima@zainabcenter.test', 'gender' => 'Female'],
            ['name' => 'Khaled Sayed', 'email' => 'khaled@zainabcenter.test', 'gender' => 'Male'],
            ['name' => 'Zainab Qasim', 'email' => 'zainab@zainabcenter.test', 'gender' => 'Female'],
            ['name' => 'Omar Farooq',  'email' => 'omar@zainabcenter.test',   'gender' => 'Male'],
        ];

        $students = collect();
        foreach ($studentsData as $studentData) {
            $student = \App\Models\User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name'     => $studentData['name'],
                    'password' => bcrypt('password'),
                    'gender'   => $studentData['gender'],
                ]
            );
            $student->assignRole('Student');
            $students->push($student);
        }

        // Academic Year and Term
        $year = \App\Models\SIS\AcademicYear::firstOrCreate(
            ['name' => '1446 AH'],
            ['start_date' => '2025-07-01', 'end_date' => '2026-06-30']
        );

        $term = \App\Models\SIS\Term::firstOrCreate(
            ['name' => 'Fall 1446'],
            [
                'academic_year_id' => $year->id,
                'start_date'       => '2025-08-01',
                'end_date'         => '2025-12-15',
                'is_current'       => true,
            ]
        );

        // Program (no price — lives on Course now)
        $program = \App\Models\SIS\Program::firstOrCreate(
            ['name' => 'Diploma in Islamic Studies'],
            [
                'code'        => 'DIS-1',
                'description' => 'A comprehensive foundational Islamic studies program.',
                'is_active'   => true,
            ]
        );

        // Courses (price lives here)
        $courses = [
            \App\Models\SIS\Course::firstOrCreate(
                ['name' => 'Aqeedah 101'],
                ['code' => 'AQ101', 'description' => 'Introduction to Islamic Theology', 'credits' => 3, 'capacity' => 50, 'price' => 500.00, 'billing_cycle' => 'once', 'is_active' => true]
            ),
            \App\Models\SIS\Course::firstOrCreate(
                ['name' => 'Tafseer Al-Quran'],
                ['code' => 'TAF101', 'description' => 'Exegesis of Juz Amma', 'credits' => 3, 'capacity' => 50, 'price' => 500.00, 'billing_cycle' => 'once', 'is_active' => true]
            ),
            \App\Models\SIS\Course::firstOrCreate(
                ['name' => 'Fiqh of Worship'],
                ['code' => 'FIQ101', 'description' => 'Rules of Purification and Prayer', 'credits' => 3, 'capacity' => 50, 'price' => 500.00, 'billing_cycle' => 'once', 'is_active' => true]
            ),
        ];

        // Link courses to program via pivot (guard duplicates)
        foreach ($courses as $index => $course) {
            $exists = DB::table('program_courses')
                ->where('program_id', $program->id)
                ->where('course_id', $course->id)
                ->exists();
            if (!$exists) {
                DB::table('program_courses')->insert([
                    'id'          => (string) Str::uuid(),
                    'program_id'  => $program->id,
                    'course_id'   => $course->id,
                    'sequence'    => $index + 1,
                    'is_required' => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        // Assign teacher to course sessions
        foreach ($courses as $course) {
            \App\Models\LMS\CourseSession::firstOrCreate(
                ['course_id' => $course->id, 'session_date' => now()->addDays(rand(1, 10))->format('Y-m-d')],
                [
                    'instructor_user_id' => $teacher->id,
                    'session_start_time' => '18:00:00',
                    'session_end_time'   => '19:30:00',
                    'platform'           => 'Zoom',
                    'meeting_url'        => 'https://zoom.us/j/123456789',
                ]
            );
        }

        // Enroll students in first course (course-level enrollment)
        $primaryCourse = $courses[0];
        $statuses      = ['Enrolled', 'Pending', 'Enrolled', 'Enrolled', 'Pending'];
        $payments      = [500.00, 0, 500.00, 500.00, 0];

        foreach ($students as $index => $student) {
            \App\Models\SIS\Enrollment::firstOrCreate(
                ['user_id' => $student->id, 'course_id' => $primaryCourse->id, 'term_id' => $term->id],
                [
                    'status'         => $statuses[$index],
                    'enrolled_at'    => now(),
                    'amount_paid'    => $payments[$index],
                    'payment_method' => $payments[$index] > 0 ? 'Stripe' : null,
                ]
            );
        }
    }
}
