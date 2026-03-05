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
        $connection = Schema::getConnection();

        // 1. Handle renaming logic safely
        if (Schema::hasColumn('enrollments', 'program_id')) {
            Schema::table('enrollments', function (Blueprint $table) {
                // Drop the foreign key if it exists
                try {
                    $table->dropForeign(['program_id']);
                } catch (\Exception $e) {
                    // Ignore if constraint doesn't exist
                }
                
                $table->renameColumn('program_id', 'course_id');
            });
        }

        // 2. Clean up invalid data using a direct statement to avoid any query builder scoping issues
        // This ensures the enrollments table is clean before adding the foreign key.
        $connection->statement('DELETE FROM enrollments WHERE course_id NOT IN (SELECT id FROM courses)');

        // 3. Now we can safely add the foreign key
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
