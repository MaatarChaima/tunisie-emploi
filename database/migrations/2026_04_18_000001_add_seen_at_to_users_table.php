<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('reports_seen_at')->nullable();   // admin: last saw /admin/reports
            $table->timestamp('users_seen_at')->nullable();     // admin: last saw /admin/users
            $table->timestamp('offers_seen_at')->nullable();    // admin: last saw /admin/offers
            $table->timestamp('my_offers_seen_at')->nullable(); // company: last saw /my-offers
            $table->timestamp('my_apps_seen_at')->nullable();   // candidate: last saw /applications
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'reports_seen_at',
                'users_seen_at',
                'offers_seen_at',
                'my_offers_seen_at',
                'my_apps_seen_at',
            ]);
        });
    }
};
