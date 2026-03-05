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
        // First handle renaming and dropping the old foreign key
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->renameColumn('program_id', 'course_id');
        });

        // After renaming to course_id, we MUST ensure the data is consistent
        // Before adding the foreign key to the courses table.
        // We delete any records that don't exist in the courses table.
        \Illuminate\Support\Facades\DB::table('enrollments')
            ->whereNotExists(function ($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw(1))
                    ->from('courses')
                    ->whereRaw('courses.id = enrollments.course_id');
            })
            ->delete();

        // Now we can safely add the foreign key
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->renameColumn('course_id', 'program_id');
            $table->foreign('program_id')->references('id')->on('programs')->cascadeOnDelete();
        });
    }
};
