<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Convert timestamp columns to timestamptz (timestamp with time zone)
     * Existing data remains unchanged, only column type changes
     * New posts will save with Asia/Dhaka timezone
     */
    public function up(): void
    {
        // Change column types to timestamptz without modifying existing data
        DB::statement("ALTER TABLE posts ALTER COLUMN created_at TYPE timestamptz");
        DB::statement("ALTER TABLE posts ALTER COLUMN updated_at TYPE timestamptz");
        DB::statement("ALTER TABLE posts ALTER COLUMN published_at TYPE timestamptz");
    }

    /**
     * Reverse the migrations.
     * 
     * Rollback to original timestamp without timezone
     * Existing data remains unchanged
     */
    public function down(): void
    {
        // Convert back to timestamp without timezone
        DB::statement("ALTER TABLE posts ALTER COLUMN created_at TYPE timestamp");
        DB::statement("ALTER TABLE posts ALTER COLUMN updated_at TYPE timestamp");
        DB::statement("ALTER TABLE posts ALTER COLUMN published_at TYPE timestamp");
    }
};
