<?php

namespace Database\Seeders;

use App\Models\CMS\Post;
use App\Models\CRM\Contact;
use App\Models\LMS\Assessment;
use App\Models\LMS\AssessmentQuestion;
use App\Models\LMS\AssessmentSubmission;
use App\Models\LMS\AssessmentAnswer;
use App\Models\LMS\Attendance;
use App\Models\LMS\CourseAnnouncement;
use App\Models\LMS\CourseSchedule;
use App\Models\LMS\CourseSession;
use App\Models\LMS\Grade;
use App\Models\LMS\Lesson;
use App\Models\LMS\LessonProgress;
use App\Models\LMS\Module;
use App\Models\SIS\AcademicYear;
use App\Models\SIS\Course;
use App\Models\SIS\Enrollment;
use App\Models\SIS\Program;
use App\Models\SIS\Term;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

class TenantSampleDataSeeder extends Seeder
{
    public function run()
    {
        // ─────────────────────────────────────────────
        // ROLES
        // ─────────────────────────────────────────────
        $guardName = 'web';
        $rolesAvailable = ['Super Admin', 'Admin', 'Instructor', 'Student', 'super_admin', 'admin', 'instructor', 'student', 'teacher', 'Teacher'];
        foreach ($rolesAvailable as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => $guardName]);
        }

        // ─────────────────────────────────────────────
        // PERSONA 1: ADMIN
        // ─────────────────────────────────────────────
        $admin = User::updateOrCreate(
            ['email' => 'admin@zainab.com'],
            ['name' => 'Sister Mariam (Admin)', 'password' => bcrypt('password')]
        );
        $admin->syncRoles(['Admin']);

        // ─────────────────────────────────────────────
        // PERSONA 2: TEACHER — Shaykh Ahmad
        // ─────────────────────────────────────────────
        // IDEMPOTENCY FIX: Clear anyone else who might have THIS roll number
        User::where('roll_number', 'INS-001')->where('email', '!=', 'shaykh.ahmad@zainabcenter.org')->update(['roll_number' => null]);
        
        $shaykh = User::updateOrCreate(
            ['email' => 'shaykh.ahmad@zainabcenter.org'],
            [
                'name'        => 'Shaykh Ahmad',
                'password'    => bcrypt('password'),
                'gender'      => 'male',
                'timezone'    => 'America/New_York',
                'roll_number' => 'INS-001',
            ]
        );
        $shaykh->syncRoles(['Instructor']);

        // ─────────────────────────────────────────────
        // PERSONA 3: TEACHER — Ustadha Fatima
        // ─────────────────────────────────────────────
        User::where('roll_number', 'INS-002')->where('email', '!=', 'ustadha.fatima@zainabcenter.org')->update(['roll_number' => null]);

        $ustadha = User::updateOrCreate(
            ['email' => 'ustadha.fatima@zainabcenter.org'],
            [
                'name'        => 'Ustadha Fatima',
                'password'    => bcrypt('password'),
                'gender'      => 'female',
                'timezone'    => 'America/Chicago',
                'roll_number' => 'INS-002',
            ]
        );
        $ustadha->syncRoles(['Instructor']);

        // ─────────────────────────────────────────────
        // PERSONA 4: STUDENT — Ali Raza (Diligent student, good grades)
        // ─────────────────────────────────────────────
        User::where('roll_number', 'STU-1001')->where('email', '!=', 'ali.raza@example.com')->update(['roll_number' => null]);

        $ali = User::updateOrCreate(
            ['email' => 'ali.raza@example.com'],
            [
                'name'        => 'Ali Raza',
                'password'    => bcrypt('password'),
                'gender'      => 'male',
                'timezone'    => 'America/New_York',
                'roll_number' => 'STU-1001',
            ]
        );
        $ali->syncRoles(['Student']);

        // ─────────────────────────────────────────────
        // PERSONA 5: STUDENT — Sara Khan (New student, just started)
        // ─────────────────────────────────────────────
        User::where('roll_number', 'STU-1002')->where('email', '!=', 'sara.khan@example.com')->update(['roll_number' => null]);

        $sara = User::updateOrCreate(
            ['email' => 'sara.khan@example.com'],
            [
                'name'        => 'Sara Khan',
                'password'    => bcrypt('password'),
                'gender'      => 'female',
                'timezone'    => 'America/Los_Angeles',
                'roll_number' => 'STU-1002',
            ]
        );
        $sara->syncRoles(['Student']);

        // ─────────────────────────────────────────────
        // ACADEMIC YEAR & TERMS
        // ─────────────────────────────────────────────
        $year = AcademicYear::updateOrCreate(
            ['name' => '2024-2025'],
            [
                'start_date' => Carbon::parse('2024-09-01'),
                'end_date'   => Carbon::parse('2025-06-30'),
                'is_active'  => true,
            ]
        );

        $termFall = Term::updateOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Fall 2024'],
            [
                'start_date' => Carbon::parse('2024-09-01'),
                'end_date'   => Carbon::parse('2024-12-15'),
                'is_current' => false,
            ]
        );

        $termSpring = Term::updateOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Spring 2025'],
            [
                'start_date' => Carbon::parse('2025-01-10'),
                'end_date'   => Carbon::parse('2025-05-30'),
                'is_current' => true,
            ]
        );

        // ─────────────────────────────────────────────
        // PROGRAMS
        // ─────────────────────────────────────────────
        $programAlim = Program::updateOrCreate(
            ['code' => 'ALIM-1'],
            [
                'name'            => 'Alim Programme – Year 1',
                'description'     => 'The foundational year of the comprehensive Alim course.',
                'duration_months' => 10,
                'level'           => 'Beginner',
                'is_active'       => true,
            ]
        );

        $programQuran = Program::updateOrCreate(
            ['code' => 'QUR-1'],
            [
                'name'            => 'Quranic Sciences',
                'description'     => 'A dedicated programme focused on Tafseer and Quranic recitation.',
                'duration_months' => 8,
                'level'           => 'Intermediate',
                'is_active'       => true,
            ]
        );

        // ─────────────────────────────────────────────
        // COURSES
        // ─────────────────────────────────────────────
        $courseTafseer = Course::updateOrCreate(
            ['code' => 'TAF101'],
            [
                'name'          => 'Tafseer — Introduction to Quranic Exegesis',
                'description'   => 'An in-depth exploration of the sciences of the Quran, including Asbab al-Nuzool and various schools of Tafseer.',
                'credits'       => 3,
                'capacity'      => 30,
                'term_id'       => $termSpring->id,
                'price'         => 120.00,
                'billing_cycle' => 'monthly',
                'is_active'     => true,
            ]
        );
        $courseTafseer->programs()->sync([$programAlim->id]);

        $courseHadith = Course::updateOrCreate(
            ['code' => 'HAD101'],
            [
                'name'          => 'Hadith Principles — Usul al-Hadith',
                'description'   => 'A rigorous study of the chain of narration (isnad) and text criticism in Islamic hadith science.',
                'credits'       => 3,
                'capacity'      => 30,
                'term_id'       => $termSpring->id,
                'price'         => 120.00,
                'billing_cycle' => 'monthly',
                'is_active'     => true,
            ]
        );
        $courseHadith->programs()->sync([$programAlim->id]);

        $courseFiqh = Course::updateOrCreate(
            ['code' => 'FQH101'],
            [
                'name'          => 'Islamic Jurisprudence — Fiqh Fundamentals',
                'description'   => 'Covers the four major schools of Fiqh, their founders, and foundational rulings in worship.',
                'credits'       => 3,
                'capacity'      => 25,
                'term_id'       => $termSpring->id,
                'price'         => 100.00,
                'billing_cycle' => 'monthly',
                'is_active'     => true,
            ]
        );
        $courseFiqh->programs()->sync([$programAlim->id]);

        // ─────────────────────────────────────────────
        // ENROLLMENTS
        // Ali → Tafseer + Hadith (both with Shaykh Ahmad)
        // Sara → Fiqh (with Ustadha Fatima, new student)
        // ─────────────────────────────────────────────
        $enrollAliTafseer = Enrollment::updateOrCreate(
            ['user_id' => $ali->id, 'course_id' => $courseTafseer->id],
            [
                'term_id'        => $termSpring->id,
                'status'         => 'Enrolled',
                'enrolled_at'    => Carbon::parse('2025-01-10'),
                'amount_paid'    => 120.00,
                'payment_method' => 'stripe_online',
            ]
        );

        $enrollAliHadith = Enrollment::updateOrCreate(
            ['user_id' => $ali->id, 'course_id' => $courseHadith->id],
            [
                'term_id'        => $termSpring->id,
                'status'         => 'Enrolled',
                'enrolled_at'    => Carbon::parse('2025-01-10'),
                'amount_paid'    => 120.00,
                'payment_method' => 'stripe_online',
            ]
        );

        $enrollSaraFiqh = Enrollment::updateOrCreate(
            ['user_id' => $sara->id, 'course_id' => $courseFiqh->id],
            [
                'term_id'        => $termSpring->id,
                'status'         => 'Enrolled',
                'enrolled_at'    => Carbon::parse('2025-01-15'),
                'amount_paid'    => 100.00,
                'payment_method' => 'stripe_online',
            ]
        );

        // ─────────────────────────────────────────────
        // MODULES & LESSONS — Tafseer Course
        // ─────────────────────────────────────────────
        $modWahy = Module::updateOrCreate(
            ['course_id' => $courseTafseer->id, 'title' => 'Revelation (Wahy)'],
            [
                'description'  => 'Understanding divine revelation, its categories, and its preservation.',
                'sequence'     => 1,
                'is_published' => true,
            ]
        );

        $lessonWahy1 = Lesson::updateOrCreate(
            ['module_id' => $modWahy->id, 'title' => 'The First Revelation'],
            [
                'content'      => '<p>Surah Al-Alaq was the first revelation received by the Prophet ﷺ in the Cave of Hira. This lesson examines the historical context and the significance of the command "Iqra" (Read/Recite).</p>',
                'type'         => 'text',
                'sequence'     => 1,
                'is_published' => true,
                'is_required'  => true,
            ]
        );

        $lessonWahy2 = Lesson::updateOrCreate(
            ['module_id' => $modWahy->id, 'title' => 'Types of Revelation'],
            [
                'content'      => '<p>Revelation came to the Prophet ﷺ in multiple forms: the ringing of a bell, the form of a man, and direct inspiration. This lesson details each type and when they occurred.</p>',
                'type'         => 'text',
                'sequence'     => 2,
                'is_published' => true,
                'is_required'  => true,
            ]
        );

        $modMakki = Module::updateOrCreate(
            ['course_id' => $courseTafseer->id, 'title' => 'Makki & Madani Surahs'],
            [
                'description'  => 'Distinguishing Surahs revealed in Makkah from those in Madinah and their thematic differences.',
                'sequence'     => 2,
                'is_published' => true,
            ]
        );

        $lessonMakki = Lesson::updateOrCreate(
            ['module_id' => $modMakki->id, 'title' => 'Definitions and Criteria'],
            [
                'content'      => '<p>Scholars have defined Makki and Madani Surahs using three criteria: time, place, and content. This lesson covers each criterion in detail.</p>',
                'type'         => 'text',
                'sequence'     => 1,
                'is_published' => true,
                'is_required'  => true,
            ]
        );

        // ─────────────────────────────────────────────
        // MODULES & LESSONS — Hadith Course
        // ─────────────────────────────────────────────
        $modIsnad = Module::updateOrCreate(
            ['course_id' => $courseHadith->id, 'title' => 'The Chain of Narration (Isnad)'],
            [
                'description'  => 'Understanding why the isnad is the backbone of hadith scholarship.',
                'sequence'     => 1,
                'is_published' => true,
            ]
        );

        $lessonIsnad1 = Lesson::updateOrCreate(
            ['module_id' => $modIsnad->id, 'title' => 'Introduction to Isnad'],
            [
                'content'      => '<p>The isnad system is unique to Islamic scholarship. This lesson introduces students to how scholars over generations preserved the sayings of the Prophet ﷺ.</p>',
                'type'         => 'text',
                'sequence'     => 1,
                'is_published' => true,
                'is_required'  => true,
            ]
        );

        // ─────────────────────────────────────────────
        // LESSON PROGRESS — Ali has completed lessons
        // ─────────────────────────────────────────────
        LessonProgress::updateOrCreate(
            ['user_id' => $ali->id, 'lesson_id' => $lessonWahy1->id],
            ['completed_at' => Carbon::parse('2025-01-20 10:00:00'), 'time_spent_seconds' => 720]
        );

        LessonProgress::updateOrCreate(
            ['user_id' => $ali->id, 'lesson_id' => $lessonWahy2->id],
            ['completed_at' => Carbon::parse('2025-01-27 11:30:00'), 'time_spent_seconds' => 900]
        );

        LessonProgress::updateOrCreate(
            ['user_id' => $ali->id, 'lesson_id' => $lessonIsnad1->id],
            ['completed_at' => Carbon::parse('2025-02-03 09:15:00'), 'time_spent_seconds' => 600]
        );

        // Sara has started but not finished lesson 1
        LessonProgress::updateOrCreate(
            ['user_id' => $sara->id, 'lesson_id' => $lessonMakki->id],
            ['completed_at' => null, 'time_spent_seconds' => 200]
        );

        // ─────────────────────────────────────────────
        // ASSESSMENTS — Shaykh Ahmad gives an assignment in Tafseer
        // ─────────────────────────────────────────────
        $assessTafseer = Assessment::updateOrCreate(
            ['course_id' => $courseTafseer->id, 'title' => 'Week 3 Quiz — Revelation'],
            [
                'type'         => 'quiz',
                'description'  => 'A short quiz covering Module 1: Revelation (Wahy).',
                'due_date'     => Carbon::parse('2025-02-10 23:59:00'),
                'max_score'    => 100,
                'is_published' => true,
            ]
        );

        $q1 = AssessmentQuestion::updateOrCreate(
            ['assessment_id' => $assessTafseer->id, 'question_text' => 'What was the first Surah revealed to the Prophet ﷺ?'],
            [
                'type'           => 'multiple_choice',
                'options'        => json_encode(['Al-Fatiha', 'Al-Alaq', 'Al-Ikhlas', 'Al-Baqarah']),
                'correct_answer' => 'Al-Alaq',
                'marks'          => 25,
                'sequence'       => 1,
            ]
        );

        $q2 = AssessmentQuestion::updateOrCreate(
            ['assessment_id' => $assessTafseer->id, 'question_text' => 'Where did the first revelation occur?'],
            [
                'type'           => 'multiple_choice',
                'options'        => json_encode(['Cave of Thawr', 'Cave of Hira', 'Mount Sinai', 'Masjid al-Haram']),
                'correct_answer' => 'Cave of Hira',
                'marks'          => 25,
                'sequence'       => 2,
            ]
        );

        $q3 = AssessmentQuestion::updateOrCreate(
            ['assessment_id' => $assessTafseer->id, 'question_text' => 'What does "Iqra" mean?'],
            [
                'type'           => 'multiple_choice',
                'options'        => json_encode(['Pray', 'Fast', 'Read/Recite', 'Submit']),
                'correct_answer' => 'Read/Recite',
                'marks'          => 25,
                'sequence'       => 3,
            ]
        );

        $q4 = AssessmentQuestion::updateOrCreate(
            ['assessment_id' => $assessTafseer->id, 'question_text' => 'How many Ayat does Surah Al-Alaq have?'],
            [
                'type'           => 'multiple_choice',
                'options'        => json_encode(['10', '14', '19', '20']),
                'correct_answer' => '19',
                'marks'          => 25,
                'sequence'       => 4,
            ]
        );

        // ─────────────────────────────────────────────
        // ASSESSMENT SUBMISSION — Ali completes the quiz (scores 75%)
        // ─────────────────────────────────────────────
        $submission = AssessmentSubmission::updateOrCreate(
            ['assessment_id' => $assessTafseer->id, 'user_id' => $ali->id],
            [
                'submitted_at'        => Carbon::parse('2025-02-09 18:30:00'),
                'total_score'         => 75,
                'graded_at'           => Carbon::parse('2025-02-11 10:00:00'),
                'graded_by'           => $shaykh->id,
                'status'              => 'graded',
                'instructor_feedback' => 'Excellent work Ali! You showed a strong understanding of the early revelations. Review question 4 about the number of Ayat.',
            ]
        );

        // Ali's individual answers
        AssessmentAnswer::updateOrCreate(
            ['submission_id' => $submission->id, 'question_id' => $q1->id],
            ['answer_text' => 'Al-Alaq', 'is_correct' => true, 'marks_awarded' => 25]
        );
        AssessmentAnswer::updateOrCreate(
            ['submission_id' => $submission->id, 'question_id' => $q2->id],
            ['answer_text' => 'Cave of Hira', 'is_correct' => true, 'marks_awarded' => 25]
        );
        AssessmentAnswer::updateOrCreate(
            ['submission_id' => $submission->id, 'question_id' => $q3->id],
            ['answer_text' => 'Read/Recite', 'is_correct' => true, 'marks_awarded' => 25]
        );
        AssessmentAnswer::updateOrCreate(
            ['submission_id' => $submission->id, 'question_id' => $q4->id],
            ['answer_text' => '14', 'is_correct' => false, 'marks_awarded' => 0]  // wrong answer
        );

        // ─────────────────────────────────────────────
        // GRADES — Shaykh Ahmad records final grades for Fall 2024
        // ─────────────────────────────────────────────
        Grade::updateOrCreate(
            ['enrollment_id' => $enrollAliTafseer->id, 'course_id' => $courseTafseer->id],
            [
                'letter_grade'        => 'A',
                'percentage'          => 91,
                'comments'            => 'Ali has consistently demonstrated strong engagement with the course material.',
                'recorded_at'         => Carbon::parse('2025-03-01 12:00:00'),
                'recorded_by_user_id' => $shaykh->id,
            ]
        );

        Grade::updateOrCreate(
            ['enrollment_id' => $enrollAliHadith->id, 'course_id' => $courseHadith->id],
            [
                'letter_grade'        => 'B+',
                'percentage'          => 87,
                'comments'            => 'Good understanding of isnad methodology. Needs to strengthen knowledge of narrator criticism.',
                'recorded_at'         => Carbon::parse('2025-03-01 12:30:00'),
                'recorded_by_user_id' => $shaykh->id,
            ]
        );

        // ─────────────────────────────────────────────
        // COURSE SCHEDULE & SESSIONS — Tafseer Live classes
        // ─────────────────────────────────────────────
        $schedule = CourseSchedule::updateOrCreate(
            ['course_id' => $courseTafseer->id, 'name' => 'Spring 2025 Weekly Schedule'],
            [
                'instructor_user_id' => $shaykh->id,
                'recurrence_type'    => 'weekly',
                'recurrence_days'    => json_encode(['Saturday']),
                'start_time'         => '10:00',
                'end_time'           => '11:30',
                'platform'           => 'Zoom',
                'meeting_url'        => 'https://zoom.us/j/999000111',
                'start_date'         => Carbon::parse('2025-01-11'),
                'end_date'           => Carbon::parse('2025-05-31'),
            ]
        );

        // Past sessions
        CourseSession::updateOrCreate(
            ['course_id' => $courseTafseer->id, 'session_date' => '2025-01-18'],
            [
                'instructor_user_id' => $shaykh->id,
                'course_schedule_id' => $schedule->id,
                'session_start_time' => '10:00:00',
                'session_end_time'   => '11:30:00',
                'platform'           => 'Zoom',
                'meeting_url'        => 'https://zoom.us/j/999000111',
                'topic'              => 'Introduction to Tafseer & Course Overview',
                'is_cancelled'       => false,
            ]
        );

        $session2 = CourseSession::updateOrCreate(
            ['course_id' => $courseTafseer->id, 'session_date' => '2025-02-01'],
            [
                'instructor_user_id' => $shaykh->id,
                'course_schedule_id' => $schedule->id,
                'session_start_time' => '10:00:00',
                'session_end_time'   => '11:30:00',
                'platform'           => 'Zoom',
                'meeting_url'        => 'https://zoom.us/j/999000111',
                'topic'              => 'The First Revelation — Cave of Hira',
                'is_cancelled'       => false,
            ]
        );

        // ─────────────────────────────────────────────
        // ATTENDANCE — Ali attended both
        // ─────────────────────────────────────────────
        Attendance::updateOrCreate(
            ['course_session_id' => $session2->id, 'student_user_id' => $ali->id],
            [
                'status'              => 'present',
                'marked_at'           => Carbon::parse('2025-02-01 10:03:00'),
                'marked_by_user_id'   => $shaykh->id,
            ]
        );

        // ─────────────────────────────────────────────
        // COURSE ANNOUNCEMENTS
        // ─────────────────────────────────────────────
        CourseAnnouncement::updateOrCreate(
            ['course_id' => $courseTafseer->id, 'title' => 'Week 3 Assignment Released'],
            [
                'content'              => 'Assalamu Alaikum students. The Week 3 Quiz on Revelation is now live. You have until February 10th to complete it. Jazakum Allahu Khairan.',
                'instructor_user_id'   => $shaykh->id,
                'is_published'         => true,
            ]
        );

        // ─────────────────────────────────────────────
        // CMS POST
        // ─────────────────────────────────────────────
        Post::updateOrCreate(
            ['slug' => 'welcome-spring-2025'],
            [
                'title'          => 'Welcome to Spring 2025 Semester at Zainab Center!',
                'post_type'      => 'post',
                'content'        => '<p>Bismillah. We are excited to welcome all new and returning students to the Spring 2025 term.</p>',
                'status'         => 'published',
                'published_at'   => Carbon::parse('2025-01-08'),
                'author_user_id' => $admin->id,
            ]
        );

        // ─────────────────────────────────────────────
        // CRM CONTACT
        // ─────────────────────────────────────────────
        Contact::updateOrCreate(
            ['email' => 'ahmad.raza.parent@example.com'],
            [
                'name'         => 'Ahmad Raza (Parent)',
                'contact_type' => 'Parent',
                'phone'        => '+1-312-555-0190',
            ]
        );

        $this->command->info('✅ 5-Persona seed data loaded:');
        $this->command->info('   👤 Admin: admin@zainab.com / password');
        $this->command->info('   👨‍🏫 Teacher: shaykh.ahmad@zainabcenter.org / password');
        $this->command->info('   👩‍🏫 Teacher: ustadha.fatima@zainabcenter.org / password');
        $this->command->info('   🎓 Student: ali.raza@example.com / password');
        $this->command->info('   🎓 Student: sara.khan@example.com / password');
    }
}
