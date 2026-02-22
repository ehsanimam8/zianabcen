<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('post_id')->index();
            $table->dateTime('event_start');
            $table->dateTime('event_end');
            $table->string('location')->nullable();
            $table->string('meeting_url')->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('current_registrations')->default(0);
            $table->string('status'); // upcoming, ongoing, past
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
