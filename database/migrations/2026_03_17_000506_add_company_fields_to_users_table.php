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
            $table->string('sector')->nullable()->after('website');
            $table->string('address')->nullable()->after('sector');
            $table->string('company_size')->nullable()->after('city');
            $table->string('tax_id')->nullable()->after('company_size');
            $table->string('logo_path')->nullable()->after('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'sector',
                'address',
                'company_size',
                'tax_id',
                'logo_path'
            ]);
        });
    }
};
