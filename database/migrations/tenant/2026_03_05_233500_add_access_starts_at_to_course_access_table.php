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
        Schema::table('course_access', function (Blueprint $table) {
            $table->timestamp('access_starts_at')->nullable();
            $table->timestamp('access_ends_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_access', function (Blueprint $table) {
            $table->dropColumn(['access_starts_at', 'access_ends_at']);
        });
    }
};
