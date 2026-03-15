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
            // First, delete any users with NULL id
            $deleted = DB::delete("DELETE FROM users WHERE id IS NULL");
            if ($deleted > 0) {
                echo "Deleted {$deleted} users with NULL id\n";
            }

            // Check if sequence exists, if not create it
            $sequenceExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_class WHERE relname = 'users_id_seq' AND relkind = 'S')");
            
            if (!$sequenceExists[0]->exists) {
                // Create the sequence
                DB::statement("CREATE SEQUENCE users_id_seq");
                echo "Created users_id_seq\n";
            }

            // Make sure id column is set to NOT NULL and has default
            DB::statement("ALTER TABLE users ALTER COLUMN id SET NOT NULL");
            DB::statement("ALTER TABLE users ALTER COLUMN id SET DEFAULT nextval('users_id_seq')");

            // Set the sequence owner
            DB::statement("ALTER SEQUENCE users_id_seq OWNED BY users.id");

            // Reset the sequence to the correct value
            $maxId = DB::table('users')->max('id') ?? 0;
            DB::statement("SELECT setval('users_id_seq', " . ($maxId + 1) . ", false)");

            echo "Fixed users table id column and sequence\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse
    }
};
