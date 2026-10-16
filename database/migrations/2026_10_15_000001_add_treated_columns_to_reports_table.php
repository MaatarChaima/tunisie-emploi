<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Statut de traitement d'un signalement : date de traitement et administrateur.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->timestamp('treated_at')->nullable()->after('reason');
            $table->foreignId('treated_by')->nullable()->after('treated_at')
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('treated_by');
            $table->dropColumn('treated_at');
        });
    }
};
