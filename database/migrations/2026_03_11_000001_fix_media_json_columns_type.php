<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, fix all existing corrupted data
        DB::statement("
            UPDATE media 
            SET manipulations = '[]'
            WHERE LENGTH(manipulations) = 4 
            AND manipulations LIKE '\"%]%\"'
        ");

        DB::statement("
            UPDATE media 
            SET custom_properties = '[]'
            WHERE LENGTH(custom_properties) = 4 
            AND custom_properties LIKE '\"%}%\"'
        ");

        DB::statement("
            UPDATE media 
            SET generated_conversions = '[]'
            WHERE LENGTH(generated_conversions) = 4 
            AND generated_conversions LIKE '\"%}%\"'
        ");

        DB::statement("
            UPDATE media 
            SET responsive_images = '[]'
            WHERE LENGTH(responsive_images) = 4 
            AND responsive_images LIKE '\"%]%\"'
        ");

        // Now change column types to JSONB for proper PostgreSQL handling
        DB::statement('ALTER TABLE media ALTER COLUMN manipulations TYPE JSONB USING manipulations::jsonb');
        DB::statement('ALTER TABLE media ALTER COLUMN custom_properties TYPE JSONB USING custom_properties::jsonb');
        DB::statement('ALTER TABLE media ALTER COLUMN generated_conversions TYPE JSONB USING generated_conversions::jsonb');
        DB::statement('ALTER TABLE media ALTER COLUMN responsive_images TYPE JSONB USING responsive_images::jsonb');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to JSON (which is VARCHAR in PostgreSQL)
        DB::statement('ALTER TABLE media ALTER COLUMN manipulations TYPE JSON USING manipulations::json');
        DB::statement('ALTER TABLE media ALTER COLUMN custom_properties TYPE JSON USING custom_properties::json');
        DB::statement('ALTER TABLE media ALTER COLUMN generated_conversions TYPE JSON USING generated_conversions::json');
        DB::statement('ALTER TABLE media ALTER COLUMN responsive_images TYPE JSON USING responsive_images::json');
    }
};
