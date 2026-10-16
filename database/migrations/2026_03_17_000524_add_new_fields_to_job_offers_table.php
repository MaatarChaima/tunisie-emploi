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
        Schema::table('job_offers', function (Blueprint $table) {
            $table->text('requirements')->nullable()->after('description');
            $table->date('expiration_date')->nullable()->after('requirements');
            $table->string('languages')->nullable()->after('expiration_date');
            $table->integer('experience_years')->nullable()->after('languages');
            $table->string('keywords')->nullable()->after('education_level');
            $table->integer('vacancies')->default(1)->after('keywords');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            $table->dropColumn([
                'requirements',
                'expiration_date',
                'languages',
                'experience_years',
                'keywords',
                'vacancies'
            ]);
        });
    }
};
