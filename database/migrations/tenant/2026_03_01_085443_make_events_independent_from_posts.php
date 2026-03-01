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
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropColumn('post_id');
            $table->string('title')->after('id')->nullable();
            $table->string('slug')->unique()->after('title')->nullable();
            $table->text('description')->nullable()->after('slug');
            $table->string('image')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('post_id')->nullable();
            $table->dropColumn(['title', 'slug', 'description', 'image']);
        });
    }
};
