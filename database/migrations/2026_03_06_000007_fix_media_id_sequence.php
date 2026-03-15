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
            // First, delete any media with NULL id
            $deleted = DB::delete("DELETE FROM media WHERE id IS NULL");
            if ($deleted > 0) {
                echo "Deleted {$deleted} media with NULL id\n";
            }

            // Check if sequence exists, if not create it
            $sequenceExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_class WHERE relname = 'media_id_seq' AND relkind = 'S')");
            
            if (!$sequenceExists[0]->exists) {
                // Create the sequence
                DB::statement("CREATE SEQUENCE media_id_seq");
                echo "Created media_id_seq\n";
            }

            // Make sure id column is set to NOT NULL and has default
            DB::statement("ALTER TABLE media ALTER COLUMN id SET NOT NULL");
            DB::statement("ALTER TABLE media ALTER COLUMN id SET DEFAULT nextval('media_id_seq')");

            // Set the sequence owner
            DB::statement("ALTER SEQUENCE media_id_seq OWNED BY media.id");

            // Reset the sequence to the correct value
            $maxId = DB::table('media')->max('id') ?? 0;
            DB::statement("SELECT setval('media_id_seq', " . ($maxId + 1) . ", false)");

            echo "Fixed media table id column and sequence\n";
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
