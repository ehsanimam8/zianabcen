<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_session_id')->index();
            $table->uuid('student_user_id')->index();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamp('marked_at')->nullable();
            $table->uuid('marked_by_user_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('course_session_id')->references('id')->on('course_sessions')->cascadeOnDelete();
            $table->foreign('student_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('marked_by_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
