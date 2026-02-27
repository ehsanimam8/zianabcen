<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('submission_id')->index();
            $table->uuid('question_id')->index();
            $table->text('student_answer')->nullable();
            $table->boolean('is_correct')->nullable(); 
            $table->integer('points_awarded')->nullable();
            $table->text('instructor_comment')->nullable();
            $table->timestamps();
            
            $table->foreign('submission_id')->references('id')->on('assessment_submissions')->cascadeOnDelete();
            $table->foreign('question_id')->references('id')->on('assessment_questions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_answers');
    }
};
