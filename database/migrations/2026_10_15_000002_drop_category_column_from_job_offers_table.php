<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La colonne texte « category » n'est plus utilisée : la catégorie d'une offre
     * passe par category_id (relation JobOffer::offerCategory()).
     */
    public function up(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            $table->string('category')->nullable()->after('type');
        });
    }
};
