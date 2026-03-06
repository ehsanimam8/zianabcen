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
        Schema::table('donations', function (Blueprint $table) {
            $table->uuid('sponsored_student_id')->nullable()->after('user_id')->index();
            $table->foreign('sponsored_student_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::dropIfExists('sponsorships');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['sponsored_student_id']);
            $table->dropColumn('sponsored_student_id');
        });

        Schema::create('sponsorships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sponsor_id')->index();
            $table->uuid('student_id')->index();
            $table->decimal('amount', 10, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('sponsor_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('student_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
