<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // First, delete any posts with NULL id
            $deleted = DB::delete("DELETE FROM posts WHERE id IS NULL");
            if ($deleted > 0) {
                echo "Deleted {$deleted} posts with NULL id\n";
            }
            
            // Check if sequence exists, if not create it
            $sequenceExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_class WHERE relname = 'posts_id_seq' AND relkind = 'S')");
            
            if (!$sequenceExists[0]->exists) {
                // Create the sequence
                DB::statement("CREATE SEQUENCE posts_id_seq");
                echo "Created posts_id_seq\n";
            }
            
            // Make sure id column is set to NOT NULL and has default
            DB::statement("ALTER TABLE posts ALTER COLUMN id SET NOT NULL");
            DB::statement("ALTER TABLE posts ALTER COLUMN id SET DEFAULT nextval('posts_id_seq')");
            
            // Set the sequence owner
            DB::statement("ALTER SEQUENCE posts_id_seq OWNED BY posts.id");
            
            // Reset the sequence to the correct value
            $maxId = DB::table('posts')->max('id') ?? 0;
            DB::statement("SELECT setval('posts_id_seq', " . ($maxId + 1) . ", false)");
            
            echo "Fixed posts table id column and sequence\n";
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
