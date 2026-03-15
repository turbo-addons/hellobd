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
            // First, delete any tokens with NULL id
            $deleted = DB::delete("DELETE FROM personal_access_tokens WHERE id IS NULL");
            if ($deleted > 0) {
                echo "Deleted {$deleted} tokens with NULL id\n";
            }

            // Check if sequence exists, if not create it
            $sequenceExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_class WHERE relname = 'personal_access_tokens_id_seq' AND relkind = 'S')");
            
            if (!$sequenceExists[0]->exists) {
                // Create the sequence
                DB::statement("CREATE SEQUENCE personal_access_tokens_id_seq");
                echo "Created personal_access_tokens_id_seq\n";
            }

            // Make sure id column is set to NOT NULL and has default
            DB::statement("ALTER TABLE personal_access_tokens ALTER COLUMN id SET NOT NULL");
            DB::statement("ALTER TABLE personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('personal_access_tokens_id_seq')");

            // Set the sequence owner
            DB::statement("ALTER SEQUENCE personal_access_tokens_id_seq OWNED BY personal_access_tokens.id");

            // Reset the sequence to the correct value
            $maxId = DB::table('personal_access_tokens')->max('id') ?? 0;
            DB::statement("SELECT setval('personal_access_tokens_id_seq', " . ($maxId + 1) . ", false)");

            echo "Fixed personal_access_tokens table id column and sequence\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Remove default from id column
            DB::statement("ALTER TABLE personal_access_tokens ALTER COLUMN id DROP DEFAULT");
            
            // Drop the sequence if it exists
            DB::statement("DROP SEQUENCE IF EXISTS personal_access_tokens_id_seq CASCADE");
            
            echo "Rolled back personal_access_tokens fixes\n";
        }
    }
};
