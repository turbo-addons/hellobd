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
            // First, delete any subscribers with NULL id
            $deleted = DB::delete("DELETE FROM subscribers WHERE id IS NULL");
            if ($deleted > 0) {
                echo "Deleted {$deleted} subscribers with NULL id\n";
            }

            // Check if sequence exists, if not create it
            $sequenceExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_class WHERE relname = 'subscribers_id_seq' AND relkind = 'S')");
            if (!$sequenceExists[0]->exists) {
                // Create the sequence
                DB::statement("CREATE SEQUENCE subscribers_id_seq");
                echo "Created subscribers_id_seq\n";
            }

            // Make sure id column is set to NOT NULL and has default
            DB::statement("ALTER TABLE subscribers ALTER COLUMN id SET NOT NULL");
            DB::statement("ALTER TABLE subscribers ALTER COLUMN id SET DEFAULT nextval('subscribers_id_seq')");

            // Set the sequence owner
            DB::statement("ALTER SEQUENCE subscribers_id_seq OWNED BY subscribers.id");

            // Reset the sequence to the correct value
            $maxId = DB::table('subscribers')->max('id') ?? 0;
            DB::statement("SELECT setval('subscribers_id_seq', " . ($maxId + 1) . ", false)");

            echo "Fixed subscribers table id column and sequence\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Remove the default value from id column
            DB::statement("ALTER TABLE subscribers ALTER COLUMN id DROP DEFAULT");
            
            // Drop the sequence if it exists
            DB::statement("DROP SEQUENCE IF EXISTS subscribers_id_seq CASCADE");
            
            echo "Reverted subscribers table id column and sequence changes\n";
        }
    }
};