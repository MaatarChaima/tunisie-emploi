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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_validated')->default(true)->after('role');
            $table->boolean('is_blocked')->default(false)->after('is_validated');
        });

        Schema::table('job_offers', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('type')->constrained()->onDelete('set null');
            // Change status from string with default 'open' to 'pending_validation' by default
            $table->string('status')->default('pending_validation')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_validated', 'is_blocked']);
        });

        Schema::table('job_offers', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->string('status')->default('open')->change();
        });
    }
};
