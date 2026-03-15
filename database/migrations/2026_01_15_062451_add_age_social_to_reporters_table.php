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
        Schema::table('reporters', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('designation');
            $table->json('social_media')->nullable()->after('social_links');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reporters', function (Blueprint $table) {
            $table->dropColumn(['age', 'social_media']);
        });
    }
};
