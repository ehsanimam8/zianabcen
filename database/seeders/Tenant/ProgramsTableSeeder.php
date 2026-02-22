<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\SIS\Program;

class ProgramsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'English : Foundations - 2026',
                'description' => 'A three-day-a-week program covering Tafseer I, Islamic Law I (Fiqh I), Arabic Morphology (Sarf Awwal), and Tajweed. Schedule: Mon - Wed, 7:00 pm - 8:30 pm EST.',
                'price' => 120.00,
                'billing_cycle' => 'termly',
                'duration_months' => 12,
                'level' => 'Foundational',
                'is_active' => true,
            ],
            [
                'name' => 'English : Fundamentals 1 - 2026',
                'description' => 'A two-day-a-week class for children covering stories of the Prophets (A.S), Taleem ul Haqq I, Basic Tajweed, and Masnoon Duas. Schedule: Tue - Thu, 6:00 pm - 7:00 pm EST.',
                'price' => 100.00,
                'billing_cycle' => 'termly',
                'duration_months' => 12,
                'level' => 'Foundational',
                'is_active' => true,
            ],
            [
                'name' => 'Individual Quran Hifz Class',
                'description' => 'One-on-one Quran memorization classes with qualified reciters of the Quran. 3 days a week, 30-minute sessions.',
                'price' => 100.00,
                'billing_cycle' => 'monthly',
                'duration_months' => 1,
                'level' => 'Advanced',
                'is_active' => true,
            ],
            [
                'name' => 'Individual Quran Recitation Class',
                'description' => 'One-on-one Quran recitation/Nazira classes with qualified reciters. 2 days a week, 30-minute sessions.',
                'price' => 50.00,
                'billing_cycle' => 'monthly',
                'duration_months' => 1,
                'level' => 'Open Level',
                'is_active' => true,
            ],
            [
                'name' => 'Group Quran Recitation Class',
                'description' => 'Quran recitation classes for a group of 3-4 students, taught by qualified reciters. 2 days a week, 30-minute sessions.',
                'price' => 50.00,
                'billing_cycle' => 'monthly',
                'duration_months' => 1,
                'level' => 'Open Level',
                'is_active' => true,
            ],
        ];

        foreach ($programs as $programData) {
            $programData['code'] = strtoupper(Str::random(6));
            $program = Program::create($programData);
            
            $course = \App\Models\SIS\Course::create([
                'name' => $programData['name'] . ' (Core Course)',
                'code' => strtoupper(Str::random(6)) . '-C',
                'credits' => 3,
                'description' => $programData['description'],
                'is_active' => true,
            ]);
            
            \Illuminate\Support\Facades\DB::table('program_courses')->insert([
                'id' => (string) Str::uuid(),
                'program_id' => $program->id,
                'course_id' => $course->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
