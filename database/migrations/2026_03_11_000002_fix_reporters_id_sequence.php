<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // First, delete any reporters with NULL id
            $deleted = DB::delete("DELETE FROM reporters WHERE id IS NULL");
            if ($deleted > 0) {
                echo "Deleted {$deleted} reporters with NULL id\n";
            }

            // Check if sequence exists, if not create it
            $sequenceExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_class WHERE relname = 'reporters_id_seq' AND relkind = 'S')");
            if (!$sequenceExists[0]->exists) {
                // Create the sequence
                DB::statement("CREATE SEQUENCE reporters_id_seq");
                echo "Created reporters_id_seq\n";
            }

            // Make sure id column is set to NOT NULL and has default
            DB::statement("ALTER TABLE reporters ALTER COLUMN id SET NOT NULL");
            DB::statement("ALTER TABLE reporters ALTER COLUMN id SET DEFAULT nextval('reporters_id_seq')");

            // Set the sequence owner
            DB::statement("ALTER SEQUENCE reporters_id_seq OWNED BY reporters.id");

            // Reset the sequence to the correct value
            $maxId = DB::table('reporters')->max('id') ?? 0;
            DB::statement("SELECT setval('reporters_id_seq', " . ($maxId + 1) . ", false)");

            echo "Fixed reporters table id column and sequence\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Remove the default value from id column
            DB::statement("ALTER TABLE reporters ALTER COLUMN id DROP DEFAULT");
            
            // Drop the sequence if it exists
            DB::statement("DROP SEQUENCE IF EXISTS reporters_id_seq CASCADE");
            
            echo "Reverted reporters table id column and sequence changes\n";
        }
    }
};