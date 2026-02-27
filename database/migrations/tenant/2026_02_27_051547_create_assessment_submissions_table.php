<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_id')->index();
            $table->uuid('user_id')->index(); // The student
            $table->string('status')->default('submitted'); // submitted, grading, graded
            $table->integer('total_score')->nullable();
            $table->text('instructor_feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->uuid('graded_by')->nullable(); // Instructor ID
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            
            $table->foreign('assessment_id')->references('id')->on('assessments')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('graded_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_submissions');
    }
};
