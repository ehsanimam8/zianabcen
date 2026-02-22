<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id')->index();
            $table->uuid('course_schedule_id')->nullable()->index();
            $table->uuid('instructor_user_id')->nullable()->index();
            $table->date('session_date');
            $table->time('session_start_time')->nullable();
            $table->time('session_end_time')->nullable();
            $table->string('timezone')->nullable();
            $table->string('platform')->nullable();
            $table->string('meeting_url')->nullable();
            $table->boolean('is_cancelled')->default(false);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->foreign('course_schedule_id')->references('id')->on('course_schedules')->nullOnDelete();
            $table->foreign('instructor_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_sessions');
    }
};
