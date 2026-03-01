<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'activity_log' => 'properties',
            'programs' => 'custom_fields',
            'course_schedules' => 'pattern_config',
            'courses' => 'custom_fields',
            'event_registrations' => 'custom_fields',
            'assessment_questions' => 'options',
            'contacts' => 'custom_fields',
            'posts' => 'custom_fields',
        ];

        foreach ($tables as $table => $column) {
            if (\Illuminate\Support\Facades\Schema::hasTable($table) && \Illuminate\Support\Facades\Schema::hasColumn($table, $column)) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} TYPE jsonb USING {$column}::text::jsonb");
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'activity_log' => 'properties',
            'programs' => 'custom_fields',
            'course_schedules' => 'pattern_config',
            'courses' => 'custom_fields',
            'event_registrations' => 'custom_fields',
            'assessment_questions' => 'options',
            'contacts' => 'custom_fields',
            'posts' => 'custom_fields',
        ];

        foreach ($tables as $table => $column) {
            if (\Illuminate\Support\Facades\Schema::hasTable($table) && \Illuminate\Support\Facades\Schema::hasColumn($table, $column)) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} TYPE json USING {$column}::text::json");
            }
        }
    }
};
