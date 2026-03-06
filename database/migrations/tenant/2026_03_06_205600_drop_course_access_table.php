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
        Schema::dropIfExists('course_access');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('course_access', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('enrollment_id')->index();
            $table->uuid('course_id')->index();
            $table->boolean('is_active')->default(true);
            $table->timestamp('access_starts_at')->nullable();
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('enrollments')->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
        });
    }
};
