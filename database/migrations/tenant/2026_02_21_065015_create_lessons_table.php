<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('module_id')->index();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('type');
            $table->string('file_url')->nullable();
            $table->string('meeting_url')->nullable();
            $table->integer('sequence')->default(0);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('modules')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
