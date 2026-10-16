<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Keep only the latest report per (user_id, reported_user_id) pair
        \DB::statement('
            DELETE r1 FROM reports r1
            INNER JOIN reports r2
            ON r1.user_id = r2.user_id
               AND r1.reported_user_id = r2.reported_user_id
               AND r1.id < r2.id
        ');

        Schema::table('reports', function (Blueprint $table) {
            $table->unique(['user_id', 'reported_user_id'], 'reports_user_reported_unique');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropUnique('reports_user_reported_unique');
        });
    }
};
