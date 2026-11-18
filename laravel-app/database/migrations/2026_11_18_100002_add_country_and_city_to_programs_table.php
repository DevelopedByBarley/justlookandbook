<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            // Nem kötelező; ország vagy város törlésekor NULL lesz
            $table->foreignId('country_id')->nullable()->after('slug')->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->after('country_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropForeign(['country_id']);
            $table->dropColumn(['city_id', 'country_id']);
        });
    }
};
