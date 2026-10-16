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
            $table->date('birth_date')->nullable()->after('role');
            $table->string('city')->nullable()->after('birth_date');
            $table->string('domain')->nullable()->after('city');
            $table->string('education_level')->nullable()->after('domain');
            $table->integer('experience_years')->nullable()->after('education_level');
            $table->string('linkedin_url')->nullable()->after('website');
            $table->string('cv_path')->nullable()->after('linkedin_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'city',
                'domain',
                'education_level',
                'experience_years',
                'linkedin_url',
                'cv_path'
            ]);
        });
    }
};
